<?php


namespace App\Http\Controllers\Api\Kv;


use App\Http\Controllers\Api\Controller;
use App\Models\Assets\Device;
use App\Models\export\Export;
use App\Models\Telemetry\Kv;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KvController extends Controller
{
    public function index(){
        $post = request()->all();
        $kv = new kv();
        $limit = 10;
        if (isset($post['entity_id']) && !empty($post['entity_id'])) {
            $kv = $kv->where('entity_id', $post['entity_id']);
        }
        if (isset($post['type']) && !empty($post['type'])) {
            switch ($post['type']) {
                case 1:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format(date('Y-m-d 00:00:00.000')),$this->get_data_format(date('Y-m-d 23:59:59.000'))]);
                    break;
                case 2:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format(date("Y-m-d 00:00:00.000", mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y")))),$this->get_data_format(date("Y-m-d 00:00:00.000", mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))))]);
                    break;
                case 3:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format(date('Y-m-01 00:00:00.000')),$this->get_data_format(date('Y-m-t 23:59:59.000'))]);
                    break;
                case 4:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format($post['start_time'].'.000'),$this->get_data_format($post['end_time'].'.000')]);
                    break;
                default:
                    break;
            }
        }
        if (isset($post['limit']) && is_numeric($post['limit'])) {
            $limit = $post['limit'];
        }
        $kv = $kv->orderby('ts','desc');
        $kv = $kv->paginate($limit);
        foreach ($kv as $key => $value){
            $kv[$key]['ts'] = $this->getMsecToMescdate($value['ts']);
        }
        return $this->jsonResponse(200, 'success', $kv);
    }

    public function list(){
        $data = Device::get();
        return $this->jsonResponse(200, 'success', $data);
    }

    public function export(){
        $post = request()->all();
        $kv = new kv();
        if (isset($post['entity_id']) && !empty($post['entity_id'])) {
            $kv = $kv->where('entity_id', $post['entity_id']);
        }
        if (isset($post['type']) && !empty($post['type'])) {
            switch ($post['type']) {
                case 1:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format(date('Y-m-d 00:00:00.000')),$this->get_data_format(date('Y-m-d 23:59:59.000'))]);
                    break;
                case 2:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format(date("Y-m-d 00:00:00.000", mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y")))),$this->get_data_format(date("Y-m-d 00:00:00.000", mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))))]);
                    break;
                case 3:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format(date('Y-m-01 00:00:00.000')),$this->get_data_format(date('Y-m-t 23:59:59.000'))]);
                    break;
                case 4:
                    $kv = $kv->whereBetween('ts',[$this->get_data_format($post['start_time'].'.000'),$this->get_data_format($post['end_time'].'.000')]);
                    break;
                default:
                    break;
            }
        }
        $kv = $kv->orderby('ts','desc')->select('entity_type','entity_id','key','ts','dbl_v')->get();
        $kv = json_decode(json_encode($kv,JSON_UNESCAPED_UNICODE),true);
        foreach ($kv as $key => $value){
            $kv[$key]['ts'] = $this->getMsecToMescdate($value['ts']);
        }
        $rows[] = ['entity_type'=>'????????????','entity_id'=>'??????ID','key'=>'??????key','ts'=>'??????','dbl_v'=>'?????????'];
        $filename = 'excel/'.date('YmdHis').'????????????.xls';
        if (!Excel::store(new Export($rows,$kv), 'public/' . $filename)) {
            return $this->jsonResponse(400, '????????????', null);
        } else {
            $url = Storage::url($filename);
            return $this->jsonResponse(200, '????????????', $url);
        }
    }

    /**
     * ???????????????????????????????????????????????????
     */
    function get_data_format($time)
    {
        list($usec, $sec) = explode(".", $time);
        $date = strtotime($usec);
        $return_data = str_pad($date.$sec,13,"0",STR_PAD_RIGHT); //??????13???????????????0
        return $return_data;
    }

    /**
     * ???????????????
     */
    public function getMsecToMescdate($msectime)
    {
        $msectime = $msectime * 0.001;
        if(strstr($msectime,'.')){
            sprintf("%01.3f",$msectime);
            list($usec, $sec) = explode(".",$msectime);
            $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
        }else{
            $usec = $msectime;
            $sec = "000";
        }
        $date = date("Y-m-d H:i:s.x",$usec);
        return $mescdate = str_replace('x', $sec, $date);
    }
}
