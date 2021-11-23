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

    public function __construct(int $code, string $result, string $command=null, $data=null)
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

    static function fromXML(string $data):self
    {
        try {
            $response = new \SimpleXMLElement($data, LIBXML_ERR_ERROR );
        } catch (\Exception $e) {
            throw new Exception('Unable to parse input XML '.substr($data, 0, 256), 1, $e);
        }
        
        return new self( 
            (int) $response->code,
            $response->result,
            $response->command ?? null,
            $response->data ?? null
        );
    }

    static function fromJSON(string $data):self
    {
        $decoded = json_decode($data);
        $response = $decoded->response;

        if (json_last_error()) {
            throw new Exception('Unable to parse input JSON '
                .json_last_error_msg()
                . ' '. substr($data, 0, 256),
                 json_last_error()
            );
        }
        
        return new self( 
            (int) $response->code,
            $response->result,
            $response->command ?? null,
            $response->data ?? null
        );
    }    

}