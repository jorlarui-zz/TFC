<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	
	
	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];

	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$AutentificacionSeguridad = $ArrayCaps[$cont]['security.authentication-types'];
				
				if (isset($_GET['autentificacion-seguridad']) && $cont==0){
					echo "<table id='autentificacion-seguridad2'><tr><th>Autentificacion de seguridad</th></tr><tr><td>$AutentificacionSeguridad &nbsp</td>";}
				else if(isset($_GET['autentificacion-seguridad']) && $cont<$capsNum-1){
					echo "<tr><td>$AutentificacionSeguridad&nbsp</td></tr>";}
				else if(isset($_GET['autentificacion-seguridad']) && $cont==$capsNum-1){
					echo "<tr><td>$AutentificacionSeguridad&nbsp</td></tr></table>";}

				
				
			}

		


?>
