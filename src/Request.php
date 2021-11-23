<?php
namespace Wapi;

class Request
{
    /** @var string */
    private $user;

    /** @var string */
    private $auth;

    /** @var string */
    private $command;

    /** @var mixed */
     private $data;

     private static $cnt = 0;

    public function __construct($user, $auth, $command, $data=null)
    {
        $this->user = $user;
        $this->auth = $auth;
        $this->command = $command;
        $this->data = $data;
    }

    private function arrayToXml(\XMLWriter $writer, $array)
    {
        foreach ($array as $k => $v) {
            $writer->startElement($k);
            if (is_scalar($v)) {
                $writer->text($v);
            } else {
                throw new \Exception('Not implemented');
            }

            $writer->endElement();
        }
    }


    public function toXML():string
    {
        $xw = new \XMLWriter();
        $xw->openMemory();
        $xw->setIndent(true);

        $xw->startDocument('1.0');
        $xw->startElement('request');

        $xw->writeElement('user', $this->user);
        $xw->writeElement('auth', $this->auth);
        $xw->writeElement('command', $this->command);
        $xw->writeElement('clTRID', implode('_', ['wapi', static::$cnt++, $this->command]));

        if ( $this->data ) {
            $xw->startElement('data');
            $this->arrayToXml($xw, $this->data);
            $xw->endElement();
        }

        $xw->endElement();
        $xw->endDocument();
        return $xw->outputMemory();
    }
}
