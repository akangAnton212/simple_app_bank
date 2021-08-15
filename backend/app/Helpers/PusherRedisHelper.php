<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class PusherRedisHelper {
    /**
     * FUNGSI UNTUK SET DATA TO REDIS UNTUK SINKRONISASI DENGAN SERVER CLOUD
     * SEMENTARA YANG DISET ITU :
     * KEY => BERUPA ENDPOINT|UID_PROFILE|UID(untuk kuncian key biar ga kereplace)
     * VALUE => BERUPA JSON
     */
    public function pushDataToRedis($key, $data,$typeData){
        $dt_now = Carbon::now()->format('YmdHis');
        $dt_tomorrow = Carbon::now()->addDays(1)->format('Ymd');
        $dt_tomorrow = $dt_tomorrow."010000";
        $date = Carbon::parse($dt_tomorrow)->format('Y-m-d H:i:s');
        $beda = Carbon::parse($dt_now)->diffInSeconds($date);
        $strUid = (string) Str::uuid();
        Redis::set($key."|".$strUid."|".$typeData, json_encode($data));
        Redis::expire($key, $beda);

        return true;
    }
}