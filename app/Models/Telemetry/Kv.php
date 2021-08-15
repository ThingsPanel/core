<?php

namespace App\Models\Telemetry;

use App\Events\Alert;
use App\Events\Strategy;
use App\Models\Base;
use App\Models\Assets\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kv extends Base
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ts_kv';

    protected $primaryKey = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entity_type', 'entity_id', 'key', 'ts', 'bool_v', 'str_v', 'long_v', 'dbl_v'];

    /**
     * @param $token
     * @param $values
     * @param int $ts
     *
     */
    public static function insertByToken($token, $values, $ts = 0)
    {
        Log::debug('insertByToken', [$token, $values, $ts]);
        $device_id = Device::where('token', $token)->value('id');
        if ($device_id) {
            self::insertByDeviceID($device_id, $values, $ts);
        } else {
            Log::warning('token not matched', [$token]);
        }
    }

    /**
     * @param $device_id
     * @param $values
     * @param int $ts
     *
     */
    public static function insertByDeviceID($device_id, $values, $ts = 0)
    {
        if (!$ts) {
            $ts = \App\Facades\Telemetry::microtime();
        }
        Log::debug('insertByDeviceID', [$device_id, $values, $ts]);
        if ($device_id && !empty($values)) {
            $data = [];
            foreach ($values as $key => $val) {
                if (is_numeric($val)) {
                    $data[] = [
                        'entity_type' => 'DEVICE',
                        'entity_id' => $device_id, //device_id
                        'key' => $key,
                        'ts' => $ts,
                        'dbl_v' => $val
                    ];

                    DB::table('ts_kv_latest')->updateOrInsert(
                        [
                            'entity_type' => 'DEVICE',
                            'entity_id' => $device_id,
                            'key' => $key
                        ],
                        [
                            'ts' => $ts,
                            'dbl_v' => $val
                        ]
                    );
                }
            }
            DB::table('ts_kv')->insert($data);

            event(new Alert($data));

            event(new Strategy($data));

        } else {
            Log::warning('Asset id not matched or values empty', [$device_id]);
        }
    }

    /**
     * 取字段最新数值
     * @param $device_id
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function getLatestValue($device_id, $key, $default = NULL)
    {
        $val = Kv::where(['entity_id' => $device_id, 'entity_type' => 'DEVICE', 'key' => $key])->orderBy('ts', 'desc')->value('dbl_v');
        if ($val === NULL) {
            return $default;
        } else {
            return $val;
        }
    }

    /**
     * 内置时序数据查询
     * @param array $device_id
     * @param int $latestTime last minutes
     * @param int $startTs millisecond
     * @param int $endTs millisecond
     * @param int $interval
     * @param string $operator
     * @param array $fields
     * @param int $limit
     * @return array
     */
    public static function getTelemetryData(array $device_id, int $latestTime, int $startTs, int $endTs, string $operator, array $fields): array
    {
        if (config('app.debug')) {
            DB::enableQueryLog();
        }

        //process timing
        $timing = [];
        $interval = 1000; //default 1000
        if ($latestTime > 0) {
            $timing[] = ['ts', '>=', \App\Facades\Telemetry::microtime() - $latestTime * 60 * 1000];

            //超过1000秒
            if ($latestTime * 60 > 1000) {
                $interval = $latestTime * 60;
            }

        } else {
            $timing[] = ['ts', '>=', $startTs];
            $timing[] = ['ts', '<=', $endTs];

            //超过1000秒
            if (($endTs - $startTs) / 1000 > 1000) {
                $interval = round(($endTs - $startTs) / 1000, 2);
            }
        }

        $data = [];

        foreach ($fields as $field => $name) {
            $results = Kv::select(DB::raw("DIV(ts, {$interval})*{$interval} as r_ts"), DB::raw($operator . '(dbl_v) as r_val'))->where($timing)->where([
                'entity_type' => 'DEVICE',
                'key' => $field
            ])->whereIn('entity_id', $device_id)->groupBy('r_ts')->orderBy('r_ts', 'desc')->get();

            if (config('app.debug')) {
                //Log::debug('SQL Run time: ', [DB::getQueryLog()]);
            }

            $values = [];
            if ($results->count() > 0) {
                foreach ($results as $result) {
                    $values[] = [
                        'name' => round($result->r_val, 2) . ' (' . date('Y-m-d H:i:s', $result->r_ts / 1000) . ')',
                        'value' => [intval($result->r_ts), round($result->r_val, 2)] //0 is time string, 1 is value
                    ];
                }
            }

            $values = array_reverse($values);

            //get latest value
            $latestData = Kv::whereIn('entity_id', $device_id)->where(['entity_type' => 'DEVICE', 'key' => $field])->orderBy('ts', 'desc')->first();

            $data[$field] = [
                'name' => is_array($name) ? $name['name'] : $name,
                'latest_value' => Kv::whereIn('entity_id', $device_id)->where(['entity_type' => 'DEVICE', 'key' => $field])->orderBy('ts', 'desc')->value('dbl_v'), //Will be discarded
                'latest' => $latestData ? [date('Y-m-d H:i:s', $latestData->ts / 1000), round($latestData->dbl_v, 2)] : '',
                'values' => $values,
            ];
        }

        return $data;
    }
}
