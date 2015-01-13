<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>
<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	$ArrayState = $_SESSION['ArrayState'];
			
	
			for ($cont = 0; $cont < $capsNum; $cont++){

				$Estado= $ArrayState[$cont]['state'];
				



				if (isset($_GET['estado']) && $cont==0){
					 echo "<table id='estado2'><tr><th>Estado</th></tr><tr><td>$Estado &nbsp</td></tr>";}
				else if(isset($_GET['estado']) && $cont<$capsNum-1){
					echo "<tr><td>$Estado &nbsp</td></tr>";}
				else if(isset($_GET['estado']) && $cont==$capsNum-1){
					 echo "<tr><td>$Estado &nbsp</td></tr></table>";}


				
				
				
			

		}


?>

