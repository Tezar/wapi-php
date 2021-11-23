<?php
namespace Wapi;

class Response
{
    /** @var int */
    public $code;
    /** @var string */
    public $result;
    /** @var string */
    public $command;

    public function __construct(int $code, string $result, string $command, $data=null)
    {
        $this->code = $code;
        $this->result = $result;
        $this->command = $command;
        $this->data = $data;
    }

    public function isOk()
    {
        return $this->result == 'OK';
    }

    static function fromString(string $data):self
    {
        $response = new \SimpleXMLElement($data);
        bdump($response);
        return new self( 
            (int) $response->code,
            $response->result,
            $response->command,
            $response->data ?? null
        );
    }

}