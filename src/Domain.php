<?php
namespace Wapi;

class Domain extends DataObject
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
}