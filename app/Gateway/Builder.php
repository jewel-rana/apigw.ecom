<?php

namespace App\Gateway;


use Illuminate\Support\Facades\Http;

class Builder
{
    private array $header;
    private ?string $body;
    public string $baseUrl;
    private string $url;

    public function __setHeader($header): Builder
    {
        $this->header = $header;
        return $this;
    }

    public function __setBody($body): Builder
    {
        $this->body = $body;
        return $this;
    }

    public function __setUrl($url): Builder
    {
        $this->url = $this->baseUrl . $url;
        return $this;
    }

    public function _call()
    {
        $url = curl_init($this->url);
        curl_setopt($url,CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url,CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($url, CURLOPT_PROXY, $proxy);
        $resultdata = curl_exec($url);
        curl_close($url);

        return json_decode($resultdata);
    }
}
