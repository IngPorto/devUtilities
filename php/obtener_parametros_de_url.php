<?php
    /**
     * OBTENER PARAMETROS POR GET DE LA URL
     * 
     * Recuperar en php los datos que vienen viajando en la url de forma cómoda.
     * Ejemplo de url: https://miweb.co/?nombre_usuario=Johnny
     */

    $query_url = parse_url( html_entity_decode( esc_url( add_query_arg() ) ) );

    parse_str( $query_url[ 'query' ], $get_vars );

    $nombre_usuario = @$get_vars[ 'nombre_usuario' ];
    
    if ( $nombre_usuario )
    {
        // Do stuff
    }