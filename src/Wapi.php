<?php
namespace Wapi;

class Wapi
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $hashedPass;    


    public function __construct($user, $wpass)
    {
        $this->user = $user;
        $this->hashedPass = sha1($wpass);
    }

    protected function getAuth()
    {
        return sha1($this->user.$this->hashedPass.date('H', time()));
    }

    public function send($command, $data = null, $type = 'xml')
    {
        if ( ! in_array($type, ['json', 'xml'])) {
            throw new Exception("$type not supported");
        }

        $request = new Request(
            $this->user,
            $this->getAuth(),
            $command,
            $data
        );
        

        // address
        $url = 'https://api.wedos.com/wapi/'.$type;

        // POST data
        $post = 'request='.urlencode( $type == 'xml' ? $request->toXML() : $request->toJSON() );
        bdump($post);
        // initialization cURL session
        $ch = curl_init();

        // setting URL and data POST 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        curl_setopt($ch, CURLOPT_FAILONERROR, true); 

        // response we want as a return value curl_exec()
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

        // timeout which the script waits for a response
        curl_setopt($ch, CURLOPT_TIMEOUT,100);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // execution
        $res = curl_exec($ch);
        
        $errorCode = curl_errno($ch);

        if ($errorCode) {
            throw new ConnectionException(curl_error($ch), $errorCode);
        }
        curl_close($ch);

        $response = $type == 'xml' ? Response::fromXML($res) : Response::fromJSON($res) ;

        if ( ! $response->isOk()) {
            throw new Exception($response->command.': '.$response->result, $response->code);
        }

        return $response;
    }

    public function ping():bool
    {
        try {
            $response = $this->send('ping');
        } catch (Exception $e) {
            // internal exception => failed
            return false;
        }

        return $response->isOk();
    }

    public function domains()
    {
        $response = $this->send('domains-list');
        
        $result = [];
        foreach ($response->data->domain as $domain) {
            $d = new Domain($this, $domain);
            $result[] = $d;
        }
        return $result;
    }
    
    public function dnsRowsList(string $domainName) 
    {
        $response = $this->send('dns-rows-list', ['domain' => $domainName]);

        return $response->data->row;
    }
}
