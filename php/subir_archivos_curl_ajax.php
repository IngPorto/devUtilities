<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <!-- Ejemplo de campos usados -->
    <div>
        <input type="text" class="form-control" id="username" placeholder="Ingresa tu nombre" onkeyup="validateFields(this)">
        <div>
			<input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" onchange="validateFields(this)"> Coönsumidor
            <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" onchange="validateFields(this)"> Emprendedor
        </div>
        <select id="selectPqrs" onchange="validateFields(this)">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
        </select>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="Deja un mensaje" onkeyup="validateFields(this)"></textarea>
        <button id="sendPqrs" type="button" onclick="sendPQRS()" disabled class="btn btn-primary btn-rose btn-round btn-lg mr-auto ml-auto mt-3">Enviar</button>
    </div>
    <!-- /Ejemplo de campos usados -->
    
    <script>
        function validateFields(){
            // ... cosas de validación
        }

        function sendPQRS(){
            let tipoAcceso = "Not Selected";
            tipoAcceso = $("#inlineRadio1").prop("checked") ? 'Consumidor' : tipoAcceso;
            tipoAcceso = $("#inlineRadio2").prop("checked") ? 'Emprendedor' : tipoAcceso;

            const fields = [
                {
                    "title": "Nombre del usuario",
                    "data": $('#username').val()
                },
                {
                    "title": "Tipo de Acceso",
                    "data": tipoAcceso
                },
                {
                    "title": "¿En qué te podemos asesorar?",
                    "data": $("#selectPqrs option:selected" ).text()
                },
                {
                    "title": "Cuéntanos sobre el tema o la situación de tu consulta",
                    "data": $('#exampleFormControlTextarea1').val()
                }
            ]
            const arrayData = [{ 
                    title: 'Solicitud de PQRS[ '+ $("#selectPqrs option:selected" ).text() + ' ]: ' + $('#username').val(),
                    description: fields
                }]
            
            var formData = new FormData();
            formData.append('section', 'general');
            formData.append('action', 'previewImg');
            // Attach file
            formData.append('file', $('input[type=file]')[0].files[0]); 
            formData.append('title','Solicitud de PQRS[ '+ $("#selectPqrs option:selected" ).text() + ' ]: ' + $('#username').val() ); 
            formData.append("description", JSON.stringify(fields));

            $.ajax({
                url: "/pqrs",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false
            }).done(function(res) {
                //alert( `Tu solicitud fue recibida satisfactoriamente bajo el identificador: ${res.data[0].permalink.split("id=")[1]}.` )
                console.log( res );
                location.reload();
            }).fail(function(res){
                alert('Ha ocurrido un error al enviar tus datos. Intenta de nuevo en un momento.')
                console.log( res );
            })
            return false;
        }
    </script>
</body>
</html>

<?php
## En el micro servicio en PHP va el siguiente código
# Se consumen por 


function post ()
{
    $user_folder = "IEAC6FG3I4M4TGUD";
    $access_token = 'eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjMwODU1MzEsXCJpXCI6Njg5MTIzMixcImNcIjo0NjE2NTAwLFwidVwiOjY2NTYwMzMsXCJyXCI6XCJVU1wiLFwic1wiOltcIldcIixcIkZcIixcIklcIixcIlVcIixcIktcIixcIkNcIixcIkRcIixcIkFcIixcIkxcIl0sXCJ6XCI6W10sXCJ0XCI6MH0iLCJpYXQiOjE1ODExMjgxNjZ9.4Eb0o_Qc_I8ZNNBD82_TYAWymf78FWAV1hSG07gFR9c';
    
    $query = "folders/{$user_folder}/tasks";
    $URL = "https://www.wrike.com/api/v4/{$query}";
    // abrimos la sesión cURL
    $ch = curl_init();
    // definimos la URL a la que hacemos la petición
    curl_setopt($ch, CURLOPT_URL, $URL);
    // indicamos el tipo de petición: POST
    curl_setopt($ch, CURLOPT_POST, TRUE);
    // definición de cabeceras
    $headr = array();
    $headr[] = 'Authorization: bearer ' . $access_token;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    // definimos cada uno de los parámetros
    $title = R::sanitize( $this->post->title );
    $description = "";
    $arr_form_content = json_decode(html_entity_decode($this->post->description));
    foreach ( $arr_form_content as $field )
    {
        $description .= "<p><strong>".$field->title."</strong></p><p>".$field->data."</p><br>";
    }
    // contenido del mensaje
    curl_setopt($ch, CURLOPT_POSTFIELDS, "title={$title}&description={$description}&responsibles=[\"KUAGLEBB\"]");
    // recibimos la respuesta y la guardamos en una variable
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // se ejecuta el curl
    $remote_server_output = curl_exec ($ch);

    $ch_error_ = curl_error($ch);
    if ($ch_error_) {
        header('HTTP/1.1 500 Internal Server Error');
        echo "cURL Error Creating Task: $ch_error_";
        die();
    } 
    // cerramos la sesión cURL
    curl_close ($ch);
    
    $ch_output = json_decode( $remote_server_output );
    
    //-------------
    // FILE :::: Si existe un archivo adjunto
    //-------------
    if ( $_FILES['file'] ){
        $task_create_id = $ch_output->data[0]->id ;
        $URL_TASK= "https://www.wrike.com/api/v4/tasks/{$task_create_id}/attachments";
        
        $ch_image = curl_init();
        curl_setopt($ch_image, CURLOPT_POSTFIELDS, file_get_contents( $_FILES['file']['tmp_name'] ) );

        // definimos la URL a la que hacemos la petición
        curl_setopt($ch_image, CURLOPT_URL, $URL_TASK);
        // indicamos el tipo de petición: POST
        curl_setopt($ch_image, CURLOPT_POST, TRUE);
        // definición de cabeceras
        $headr_image = array();
        $headr_image[] = 'Authorization: bearer ' . $access_token;
        $headr_image[] = 'content-type: application/octet-stream';
        $headr_image[] = 'X-Requested-With: XMLHttpRequest';
        $headr_image[] = 'X-File-Name: '. $_FILES['file']['name'];
        curl_setopt($ch_image, CURLOPT_HTTPHEADER, $headr_image);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, "attachment content");
        // recibimos la respuesta y la guardamos en una variable
        curl_setopt($ch_image, CURLOPT_RETURNTRANSFER, true);
        $ch_image_output = curl_exec ($ch_image);

        $ch_error = curl_error($ch_image);
        if ($ch_error) {
            header('HTTP/1.1 500 Internal Server Error');
            echo "cURL Error Attaching image: $ch_error";
        } else {
            //var_dump($ch_image_output);
        }
        curl_close ($ch_image);
        
    }
    header( 'Content-type: application/json' );
    echo( $remote_server_output );
}