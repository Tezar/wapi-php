<?php
namespace Wapi;

use DateTime;

class Account 
{
    /** @var Wapi */
    private $wapi;    

    public function __construct(Wapi $wapi)
    {
        $this->wapi = $wapi;
    }

    public function movements($from=null, $to=null)
    {
        if ($from == null) {
            $from = new DateTime('-30 days');
        } elseif( is_string($from) ) {
            $from = new DateTime($from);
        } 

        if ($to == null) {
            $to = new DateTime();
        } elseif( is_string($to) ) {
            $to = new DateTime($to);
        }         

        $params = [
            'date_from' => $from->format('Y-m-d'),
            'date_to' => $to->format('Y-m-d'),
        ];
        
        // force JSON since XML is malformated (numeric tag names)
        $data = $this->wapi->send('account-list', $params, 'json')->data;

        return $data;

    }

    public function credit()
    {
        $data = $this->wapi->send('credit-info')->data;
        return DataObject::from($data[0]);
    }
}