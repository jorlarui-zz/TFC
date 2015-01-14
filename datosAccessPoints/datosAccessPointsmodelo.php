<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>
<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	$ArrayState = $_SESSION['ArrayState'];
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$Modelo = $ArrayState[$cont]['model'];
				
				



				if (isset($_GET['modelo']) && $cont==0){
					echo "<table id='modelo2'><tr><th>Modelo</th></tr><tr><td>$Modelo &nbsp</td>";}
				else if(isset($_GET['modelo']) && $cont<$capsNum-1){
					echo "<tr><td>$Modelo&nbsp</td></tr>";}
				else if(isset($_GET['modelo']) && $cont==$capsNum-1){
					echo "<tr><td>$Modelo&nbsp</td></tr></table>";}



				
				
			

		}


?>

