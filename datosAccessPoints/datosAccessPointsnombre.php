<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>
<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$Nombre = $ArrayCaps[$cont]['name'];
				
				

				if(isset($_GET['nombre']) && $cont==0){
					echo "<table id='nombre2'><tr><th>Nombre</th></tr><tr><td>$Nombre &nbsp</td></tr>";}
				else if(isset($_GET['nombre']) && $cont<$capsNum-1){
					echo "<tr><td>$Nombre &nbsp</td></tr>";}
				else if(isset($_GET['nombre']) && $cont==$capsNum-1){
					 echo "<tr><td>$Nombre &nbsp</td></tr></table>";}

				
			

		}


?>

