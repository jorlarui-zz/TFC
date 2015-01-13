<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];

	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				$EncriptacionSeguridad = $ArrayCaps[$cont]['security.encryption'];
				

				if (isset($_GET['encriptacion-seguridad']) && $cont==0){
					echo "<table id='encriptacion-seguridad2'><tr><th>Encriptación de seguridad</th></tr><tr><td>$EncriptacionSeguridad &nbsp</td>";}
				else if(isset($_GET['encriptacion-seguridad']) && $cont<$capsNum-1){
					echo "<tr><td>$EncriptacionSeguridad&nbsp</td></tr>";}
				else if(isset($_GET['encriptacion-seguridad']) && $cont==$capsNum-1){
					echo "<tr><td>$EncriptacionSeguridad&nbsp</td></tr></table>";}
				
				
			

		}


?>
