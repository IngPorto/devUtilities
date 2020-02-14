
<!-- 
    #################################################################################
                                PAGINA 1: Vista principal
    #################################################################################
-->

<?php
    // Función para identificar el tipo de dispositivo
    // @fuente https://stackoverflow.com/questions/6322112/check-if-php-page-is-accessed-from-an-ios-device

	function user_agent(){
		$iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		if($iPad||$iPhone||$iPod){
			return 'ios';
		}else if($android){
			return 'android';
		}else{
			return 'pc';
		}
	}
?>

<!-- 
    Embeber con un iframe no funciona en iOS, así que se debe traer todo el contenido
    por PHP file_get_contents()
-->
<div class="iframe-container d-flex justify-content-center">
    <!-- <iframe style="border: 0;" class="embed-responsive-item h5p-iframe" src="/pagina/encuesta/?id=<?= $encuesta->video ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe> -->
    <?php 
        $agent = user_agent();
        if( $agent == 'ios'){
            $encuesta_content = file_get_contents("https://mipagina.com/pagina/encuesta/?id=".$encuesta->video); 
            echo $encuesta_content;
        }else{
            ?>
                <iframe allowfullscreen="0" style="border: 0;" class="embed-responsive-item h5p-iframe" src="/pagina/encuesta/?id=<?= $encuesta->video ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
            <?php
        }
    ?>
</div>


<!-- 
    #################################################################################
                                PAGINA 2: Encuesta
    #################################################################################
-->

<?php /* Template Name: Encuesta */
    #------------------------------
    # - PAGINA ENCUESTA -
    # Es un tamplate en Wordpress
    # Recibe un id de un video de H5P y con el método de Wordpress do_shortcode() 
    # crea un shortcode tal como lo hace wordpress para agregar contenido
    # @fuente https://h5p.org/node/181841
    # @fuente https://stackoverflow.com/questions/3007480/determine-if-user-navigated-from-mobile-safari
    #------------------------------

	$query_url = parse_url( html_entity_decode( esc_url( add_query_arg() ) ) );
	parse_str( $query_url[ 'query' ], $get_vars );
	$id = @$get_vars[ 'id' ];
    if( ! isset( $id ) ) die();

?>

<body>
	<!-- /pwa player -->
	<div class="container-h5p">
        <?php echo do_shortcode('[h5p id="'.$id.'"]'); ?>
    </div>

	<script>
        /**
         * A continución se obtiene el reproductor H5Player para controlar sus eventos, desde
         * el código o desde la consola del navegador.
		 * 
		 * Cuando el video termina se hace lo siguiente:
		 * 	- se consume un servicio
		 * 	- se sale de fullscreen
		 * 	- 500 milisegundos después se realiza una animación que scrolea la pantalla y quita el blur de la encuesta
         */
		var H5P2;
		var H5Player;
        var ua = window.navigator.userAgent;
		var isiOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
		jQuery(document).ready(function($){
			
			$('.h5p-iframe').on('load', function() {
				H5P2 = document.getElementsByClassName('h5p-iframe')[0].contentWindow.H5P;
				if(!isiOS){
					return;
				}
				H5Player = H5P2.instances[0].video;
				H5Player.on('stateChange', function (event) { 
					switch (event.data) {
						case H5P2.Video.ENDED:
							$.get( "/user/video/"+id_video, function( data ) {
								
							});
							console.log('Video ended after ' + H5Player.getCurrentTime() + ' seconds!');
							__closeFullscreen();

							setTimeout(() => {
								H5Player.pause();
								$("html, body").animate({ scrollTop: $('#questions').offset().top - 10 }, 500, function() {
									$( '.custom_blur' ).addClass( 'disable_blur' );
									//$( '#sender' ).addClass( 'disable_blur' );
									$( "#sender-submit-button" ).prop( "disabled", false );
								});
							}, 500);
							// Start over again?
							//H5Player.play();
						
							/* if (H5Player.getDuration() > 15) {
								H5Player.seek(10);
							} */
					
						break;
					
						case H5P2.Video.PLAYING:
							//console.log('Playing'); 
						break;
					
						case H5P2.Video.PAUSED:
							//console.log('Why you stop?');
					
						//H5Player.setPlaybackRate(1.5); // Go fast
						break;
					
						case H5P2.Video.BUFFERING:
							//console.log('Wait on your slow internet connection...');
						break;
					}
				});
			});
		});
		function __closeFullscreen() {
			if (document.fullscreenElement) {
				if (document.exitFullscreen) {
				document.exitFullscreen();
				} else if (document.mozCancelFullScreen) { /* Firefox */
				document.mozCancelFullScreen();
				} else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
				document.webkitExitFullscreen();
				} else if (document.msExitFullscreen) { /* IE/Edge */
				document.msExitFullscreen();
				}
			}
		}
	</script>
</body>