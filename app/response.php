<?php

class Response{

    private $_data;
    private $_success;
    private $_messages = array();
    private $_httpStatusCode;
    private $_responseData = array();


    public function setData($data)
    {
        $this->_data = $data;
    }

    public function setSuccess($success)
    {
        $this->_success = $success;
    }

    public function addMessages($message)
    {

        $this->_messages[] = $message;
    }

    public function setHttpStatusCode($httpStatusCode)
    {
        $this->_httpStatusCode = $httpStatusCode;
    }
  

    public function send(){

        header('Content-type: application/json;charset=utf-8');


        if(!is_bool($this->_success) || $this->_httpStatusCode != 200 ){

            http_response_code($this->_httpStatusCode);

            $this->_responseData['httpStatusCode'] = $this->_httpStatusCode;
            
            $this->_responseData['success'] = false;
            
        }else{
            http_response_code(200);
            
            $this->_responseData['httpStatusCode'] = $this->_httpStatusCode;
            $this->_responseData['success'] = $this->_success;
            $this->_responseData['data'] = $this->_data;
            
        }
        $this->_responseData['messages'] = $this->_messages;

        echo json_encode($this->_responseData);

    }
}