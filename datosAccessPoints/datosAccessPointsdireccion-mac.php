<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>
<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	$ArrayState = $_SESSION['ArrayState'];
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				$DireccionMac = $ArrayState[$cont]['address'];
				
				

				if (isset($_GET['direccion-mac']) && $cont==0){
					echo "<table id='direccion-mac2'><tr><th>Direcci&oacuten MAC</th></tr><tr><td>$DireccionMac &nbsp</td>";}
				else if(isset($_GET['direccion-mac']) && $cont<$capsNum-1){
					echo "<tr><td>$DireccionMac&nbsp</td></tr>";}
				else if(isset($_GET['direccion-mac']) && $cont==$capsNum-1){
					echo "<tr><td>$DireccionMac&nbsp</td></tr></table>";}


				
				
				
			

		}


?>

