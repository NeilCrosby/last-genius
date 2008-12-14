<?php

/**
 * A little library for making curl calls a little easier.
 * 
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall {
    
    public function __construct() {
        
    }
    
    public function getFromPhpSource($url, $aOptions=array()) {
        $aOptions['type'] = 'php';
        return $this->get($url, $aOptions);
    }

    public function getFromJsonSource($url, $aOptions=array()) {
        $aOptions['type'] = 'json';
        return $this->get($url, $aOptions);
    }

    public function getFromXmlSource($url, $aOptions=array()) {
        $aOptions['type'] = 'xml';
        return $this->get($url, $aOptions);
    }

    private function get($url, $aOptions=array()) {
        $cacheTime = isset($aOptions['cache-time']) ? $aOptions['cache-time'] : 60 * 60 * 24 * 30; // 30 Days default
        $type = isset($aOptions['type']) ? $aOptions['type'] : null;
        $cacheIdent = isset($aOptions['cache-ident']) ? $aOptions['cache-ident'] : '';
        
        $cache = new PhpCache( $url, $cacheTime, $cacheIdent );

        if ( $cache->check() ) {

            $result = $cache->get();
            $datatype = $result['datatype'];
            $result = $result['data'];
            if ( 'xml' == $datatype ) {
                $result = simplexml_load_string($result);
            }

        } else {

            $session = curl_init();

            curl_setopt( $session, CURLOPT_URL, $url );
            curl_setopt( $session, CURLOPT_HEADER, false );
            curl_setopt( $session, CURLOPT_RETURNTRANSFER, 1 );    

            $result = curl_exec( $session );
            $cacheResult = $result;
            curl_close( $session );

            switch ($type) {
                case 'php':
                    $result = unserialize($result);
                    $cacheResult = $result;
                    $datatype = 'php';
                    break;
                case 'json':
                    $result = json_decode($result, true);
                    $cacheResult = $result;
                    $datatype = 'php'; // ya rly
                    break;
                case 'xml':
                    $result = simplexml_load_string($result);
                    $datatype = 'xml';
                    break;
                default:
                    break;
            }
            
            $cache->set(
                array(
                    'url'=>$url,
                    'method'=>'get',
                    'datatype'=>$datatype,
                    'data'=>$cacheResult
                )
            );
        }
        
        return $result;
    }
    
    public function getFromPhpSourceAsPost($url, $aOptions=array()) {
        $cacheTime = isset($aOptions['cache-time']) ? $aOptions['cache-time'] : 60 * 60 * 24 * 30; // 30 Days default
        $postFields = isset($aOptions['post-fields']) ? $aOptions['post-fields'] : '';
        $cacheIdent = isset($aOptions['cache-ident']) ? $aOptions['cache-ident'] : '';
        
        $cache = new PhpCache( $url.'?'.$postFields, $cacheTime, $cacheIdent );

        if ( $cache->check() ) {

            $result = $cache->get();
            $result = $result['data'];

        } else {

            $session = curl_init();

            curl_setopt( $session, CURLOPT_URL, $url );
            curl_setopt( $session, CURLOPT_HEADER, false );
            curl_setopt( $session, CURLOPT_RETURNTRANSFER, 1 );    
            curl_setopt( $session, CURLOPT_POST, 1);
            curl_setopt( $session, CURLOPT_POSTFIELDS, $postFields );

            $result = curl_exec( $session );
            curl_close( $session );

            $result = unserialize($result);

            $cache->set(
                array(
                    'url'=>$url,
                    'method'=>'getFromPhpSourceAsPost',
                    'data'=>$result
                )
            );
        }
        
        return $result;
    }
    
}

