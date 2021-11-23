<?php
namespace Wapi;

use DateTime;

class DataObject
{
    protected function loadFrom($data) 
    {
        foreach($data as $k => $v) {
            if ($k == 'expiration'){
                $v = DateTime::createFromFormat('Y-m-d H:i:s', $v.' 23:59:59');
            } elseif (strpos($k, '_date')){
                $v = DateTime::createFromFormat('Y-m-d H:i:s', $v);
            } else {
                $v = (string) $v;
            }
            $this->$k = $v;
        }
    }


    public static function from($data) {
        $i = new self();
        $i->loadFrom($data);
        return $i;
    }
}