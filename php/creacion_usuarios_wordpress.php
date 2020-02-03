<?php 
	/**
	 * CREACIÓN DE USUARIOS EN WORDPRESS
	 * 
	 * Se crean los usuario en la tabla por defecto de usuarios que tiene Wordpress llamada wp_users
	 * los datos adicionales del usuario que no vienen por defecto en la tabla son agregado en la
	 * tabla wp_usermeta, ahí de pueden poder todo tipo de información que no se pueda en la tabla
	 * wp_users
	 * 
	 * Los datos del usaurio se obtienen de la sesión, de los cuales se estrae la siguiente información:
	 * @param google_id
	 * @param email
	 * @param name
	 * @param picture
	 * @param family_name
	 * @param given_name
	 */
    session_start();
	$current_user_id = null; // Esta variable será accedida de forma globa en la aplicación

	if ( !$_SESSION['google_id'] )
	{
		wp_safe_redirect("https://miweb.co/sign");
	}
	else
	{
		// Valido si el usuario ya existe en el sistema con ls función de wordpress email_exists
		if ( !email_exists( $_SESSION['email'] ) )
		{
			// Si no existe el usuario, lo creo
			$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
			$user_id = wp_create_user( $_SESSION['name'] , $random_password, $_SESSION['email'] );
			update_user_meta(
				$user_id,
				'picture',
				$_SESSION['picture']
			);
			update_user_meta(
				$user_id,
				'family_name',
				$_SESSION['family_name']
			);
			update_user_meta(
				$user_id,
				'given_name',
				$_SESSION['given_name']
			);

			$current_user_id = $user_id;
		}
		else
		{
			// Si existe, lo establesco el current_user_id como su ID de wordpress
			$user = get_user_by( 'email',  $_SESSION['email'] );
			$current_user_id = $user->ID;

			// si el usuario ya existe pero no tiene la nueva meta data (información 
			// adicional propia de mi app), se le crea. Compruebo por el campo "picture"
			if ( !get_user_meta ( $current_user_id, 'picture', true ) )
			{
				update_user_meta(
					$current_user_id,
					'picture',
					$_SESSION['picture']
				);
				update_user_meta(
					$current_user_id,
					'family_name',
					$_SESSION['family_name']
				);
				update_user_meta(
					$current_user_id,
					'given_name',
					$_SESSION['given_name']
				);
			}
		}
	}