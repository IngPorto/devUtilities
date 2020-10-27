<?php 
interface iService {
    public function get($request, $response);
    public function post($request, $response);
    public function put($request, $response);
    public function delete($request, $response);
}