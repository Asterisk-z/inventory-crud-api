<?php

class Response {

    public $response;

    function __construct()  {

        $this->response = new SplObjectStorage;

    }

    function error($message) {

        $this->response->status   = "Error";

        $this->response->message  = $message;

        return $this->response;
        
    }

    function success($message) {

        $this->response->status   = "Success";

        $this->response->message  = $message;

        return $this->response;
        
    }


}

























?>