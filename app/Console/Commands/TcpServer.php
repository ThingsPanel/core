<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Swoole\Server;
use Swoole\Table;

class TcpServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telemetry:tcp_server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TCP Server: forward message to MQ';

    /** @var Server $server */
    protected $server = null;

    /** @var Table $table */
    protected $table;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('[Y-m-d H:i:s]') . ' Server running');

        $this->table = new Table(1024);
        $this->table->column('value', Table::TYPE_INT);
        $this->table->create();

        $connection = new AMQPStreamConnection(config('servers.rabbitmq.host'), config('servers.rabbitmq.port'), config('servers.rabbitmq.username'), config('servers.rabbitmq.password'));
        $channel = $connection->channel();

        //listen 127.0.0.1:9505
        $this->server = new Server('0.0.0.0', 9505);
        if (config('servers.tcp_pack_enable')) {
            $this->server->set([
                'open_length_check' => true,
                'package_max_length' => 81920,
                'package_length_type' => 'n',
                'package_length_offset' => 0,
                'package_body_offset' => 2
            ]);
        }

        //connecting
        $this->server->on('Connect', function ($server, $fd) {
            Log::info('telemetry:tcp_server Client: Connect.', ['fd' => $fd]);
        });

        //receive message
        $this->server->on('Receive', function ($server, $fd, $from_id, $data) use ($channel) {
            Log::info("telemetry:tcp_server Receive: " . $data, ['fd' => $fd]);

            //TCP分包粘包问题
            if (config('servers.tcp_pack_enable')) {
                /**
                 * C语言需要在上行字符前面加2个字节 sprintf(newstring, "%2c", strlen(body))
                 * PHP语言需要在上行字符前面加2个字节 pack('n',strlen($body))
                 */
                $data = strchr($data, '{');
                Log::info("telemetry:tcp_server after filter: " . $data);
            }

            $dataArray = json_decode($data, true);

            if (isset($dataArray['token']) && $dataArray['token']) {
                if (isset($dataArray['send_to_client'])) {
                    unset($dataArray['send_to_client']);
                    $data = $this->table->get('token:' . $dataArray['token']);
                    if ($data !== false) {
                        $this->server->send($data['value'], json_encode($dataArray));
                    }
                } else {
                    $this->table->set('token:' . $dataArray['token'], ['value' => $fd]);
                    $channel->basic_publish(new AMQPMessage($data), 'amq.topic', 'v1.devices.me.telemetry'); //转发到MQ
                }
            } else {
                Log::info("telemetry:tcp_server Token missing: ", ['fd' => $fd, 'data' => $dataArray]);
            }
        });

        //close listen
        $this->server->on('Close', function ($server, $fd) {
            Log::info('telemetry:tcp_server Client: Close.', ['fd' => $fd]);
        });

        //start server
        $this->server->start();
    }
}
