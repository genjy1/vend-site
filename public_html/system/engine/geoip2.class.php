<?php
/**
 * GeoIp
 * 
 * GeoIP для HostCMS, версия 2.0
 * 
 * @package GeoIp  
 * @author James V. Kotov
 * @copyright James V. Kotov
 * @version 2.0
 * @access public
 */
class GeoIp
{

    /**
     * GeoIp::GetLocation()
     * 
     * @param mixed $ip
     * @return array or false
     */
    public function GetLocation($ip)
    {
        $result['country'] = false;
        $result['location'] = false;
        $result['city'] = false;

        $geodata = $this->GetGeoIPResponseArray($ip);

        // if ($geodata) {
        //     if (isset($geodata['country'])) {

        //         if ($geodata['country'] == 'RU') {
        //             // это Россия

        //             // если это спорный регион - запоминаем его
        //             if (isset($geodata['region']) && isset($this->locations[$geodata['region']])) {
        //                 $result['location'] = $this->locations[$geodata['region']];
        //             }

        //             if (isset($geodata['city'])) {
        //                 // пытаемся определить локацию, если передан город
        //                 // если это спорный регион - учитываем его
        //                 $result = $this->detectCity($geodata['city'], $result['location']);
        //             }

        //         } else {
        //             // это не Россия'
        //             if (isset($geodata['city'])) {
        //                 // пытаемся определить локацию, если передан город
        //                 $result = $this->detectCity($geodata['city']);
        //             }
        //         }
        //     }
        // }
        // if (is_array($result) && !$result['country'] && !$result['location'] && !$result['city'])
        //     $result = false;

        return $result;

    }

    /**
     * GeoIp::GetGeoIPResponseXML()
     * 
     * @param mixed $ip
     * @return
     */
    public function GetGeoIPResponseXML($ip)
    {
        // return [];
        $request = $this->geoserver_method . '://' . $this->geoserver . ':' . $this->
            geoserver_port . '/geo?ip=' . $ip;
        $request = strval($request);

        $xml = @file_get_contents($request);
        if ($xml) {
            $xml = strval(iconv("CP1251", "UTF-8", $xml));
            $xml = str_replace('Windows-1251', 'UTF-8', $xml);
        }

        return $xml;
    }

    /**
     * GeoIp::GetGeoIPResponseArray()
     * 
     * @param mixed $ip
     * @return
     */
    public function getCity($ip)
    {
        // $ip = "94.19.156.239";
        // $ip = "46.242.102.43";
        $data = @file_get_contents("http://ipgeobase.ru:7020/geo?ip=".$ip);
        $xml = simplexml_load_string($data);
        // var_dump($xml);
        if(!$xml){
            return '';
        }
        return $xml->ip->city;

    }
}


?>