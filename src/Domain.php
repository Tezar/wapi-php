<?php
namespace Wapi;

class Domain
{
    /** @var Wapi */
    private $wapi;    

    // todo getters and whitelisted params

    public function __construct(Wapi $wapi, $data)
    {
        $this->wapi = $wapi;
        $this->loadFrom($data);
    }

    public function dnsRecords()
    {
        return $this->wapi->dnsRowsList($this->name);
    }

    protected function loadFrom($data) 
    {
        foreach($data as $k => $v) {
            // todo convert type by $k 
            $this->$k = (string) $v;
        }
    }
}