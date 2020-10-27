<?php
require_once './lib/aService.php';
// importar BL's ... Obtener el define() para DIR de la app

class User extends aService {
    private $david = array("name"=>"David", "lastname"=>"Portocarrero", "id"=>1, "email"=>"david@correo.co");
    private $jota = array("name"=>"Jhonatan", "lastname"=>"Arias", "id"=>2, "email"=>"j@correo.co");
    
    public function __construct($request, $response, $method){
        $this->USERS = array($this->david, $this->jota);
        parent::init($request, $response, $method);
    }

    function get($request, $response){
        if(!isset($request->get['id'])){ 
            $this->readMany($request, $response);
        } else {
            $this->readOne($request, $response);
        }
    }

    function post($request, $response){
        $this->createOne($request, $response);
    }

    function put($request, $response){
        $this->updateOne($request, $response);
    }

    function delete($request, $response){
        $this->deleteOne($request, $response);
    }

    // Funciones propias de la clase

    function readOne($request, $response){
        foreach($this->USERS as $user) {
            if($user['id'] && $user['id'] == $request->get['id']){
                $response->data = $user;
            }
        }
        echo(json_encode($response));
    }

    function readMany($request, $response){
        $response->data = $this->USERS;
        echo(json_encode($response));
    }

    function createOne($request, $response){
        $response->message = 'Usuario creado';
        echo(json_encode($response));
    }

    function updateOne($request, $response){
        $response->message = 'Usuario actualizado';
        echo(json_encode($response));
    }

    function deleteOne($request, $response){
        $response->message = 'Usuario eliminado';
        echo(json_encode($response));
    }

}