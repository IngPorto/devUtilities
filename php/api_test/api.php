<?php
/**
 * API REST
 * 
 * - versión del endpoint
 * - propuesta: tipo de ambiente
*/ 
//ini_set('display_errors', 0 );
require_once './api_controllers/user.php';

$request = (object) array("get"=>$_GET, "post"=>$_POST, "request"=>$_REQUEST); 
$response = (object) [];
$method = $_SERVER['REQUEST_METHOD'];

if(!isset($request->get['service'])){
    header('HTTP/1.1 405 Method not allowed');
    return;
}

switch($request->get['service']) {
    case 'users': 
        new User($request, $response, $method);
        break;
    case 'units': 
        break;
    case 'communities': 
        break;
    case 'videos': 
        break;
    default:
        header('HTTP/1.1 405 Method not allowed');
        header('Allow: GET, POST, PUT, DELETE');
        return;
        break;
}