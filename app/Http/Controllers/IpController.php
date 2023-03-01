<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use GeoIp2\Database\Reader;

class IpController extends Controller
{
    public function index(Request $request)
    {
        $ip = $request -> ip;
        $reader = new Reader(storage_path('app/geoip/') . 'GeoLite2-City.mmdb');

        $record = $reader -> city($ip);
        $data = $record -> raw;
        $data['country']['country_id'] = Country ::query() -> where('iso2', $data['country']['iso_code']) -> first() -> id;
        return $data;
    }
}
