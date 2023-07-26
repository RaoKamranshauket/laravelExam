<?php 
use Hashids\Hashids;
if(!function_exists('encode')){
    function encode($id)
    {
        $hashids = new Hashids();
        return $hashids->encode($id);
    }
}

if(!function_exists('decode')){
    function decode($id)
    {
        $hashids = new Hashids();
        $ids = $hashids->decode($id);
        return isset($ids[0]) ? $ids[0]:-1;
    }
}