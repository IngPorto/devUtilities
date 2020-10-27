<?php
require_once './lib/iService.php';

abstract class aService implements iService {

    public function init($request, $response, $method){

        switch($method) {
            case 'GET': 
                $this->get($request, $response);
                break;
            case 'POST': 
                $this->post($request, $response);
                break;
            case 'PUT': 
                $this->put($request, $response);
                break;
            case 'DELETE': 
                $this->delete($request, $response);
                break;
            default:
                header('HTTP/1.1 405 Method not allowed');
                header('Allow: GET, POST, PUT, DELETE');
                return;
                break;
        }
    }

    abstract public function get($request, $response);
    abstract public function post($request, $response);
    abstract public function put($request, $response);
    abstract public function delete($request, $response);
}