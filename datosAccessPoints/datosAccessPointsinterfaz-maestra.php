<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$API = new routeros_api();
	$IP = $_SESSION[ 'ip' ];
	$user = $_SESSION[ 'user' ];
	$password = $_SESSION[ 'password' ];
	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	
	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$InterfazMaestra = $ArrayCaps[$cont]['master-interface'];
				

				if (isset($_GET['interfaz-maestra']) && $cont==0){
					echo "<table id='interfaz-maestra2'><tr><th>Interfaz Maestra</th></tr><tr><td>$InterfazMaestra &nbsp</td>";}
				else if(isset($_GET['interfaz-maestra']) && $cont<$capsNum-1){
					echo "<tr><td>$InterfazMaestra&nbsp</td></tr>";}
				else if(isset($_GET['interfaz-maestra']) && $cont==$capsNum-1){
					echo "<tr><td>$InterfazMaestra&nbsp</td></tr></table>";}


				
			

		}


?>
