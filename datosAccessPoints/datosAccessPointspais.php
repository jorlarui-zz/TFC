<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
			

	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$Pais=$ArrayCaps[$cont]['configuration.country'];
				
				if (isset($_GET['pais']) && $cont==0){
					echo "<table id='pais2'><tr><th>País</th></tr><tr><td>$Pais &nbsp</td>";}
				else if(isset($_GET['pais']) && $cont<$capsNum-1){
					echo "<tr><td>$Pais&nbsp</td></tr>";}
				else if(isset($_GET['pais']) && $cont==$capsNum-1){
					echo "<tr><td>$Pais&nbsp</td></tr></table>";}


				
				
			

		}


?>
