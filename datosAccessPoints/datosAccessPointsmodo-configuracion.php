<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];

			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$ModoConfiguracion = $ArrayCaps[$cont]['configuration.mode'];
				
				if (isset($_GET['modo-configuracion']) && $cont==0){
					echo "<table id='modo-configuracion2'><tr><th>Modo configuración</th></tr><tr><td>$ModoConfiguracion &nbsp</td>";}
				else if(isset($_GET['modo-configuracion']) && $cont<$capsNum-1){
					echo "<tr><td>$ModoConfiguracion&nbsp</td></tr>";}
				else if(isset($_GET['modo-configuracion']) && $cont==$capsNum-1){
					echo "<tr><td>$ModoConfiguracion&nbsp</td></tr></table>";}


				
			

		}


?>
