<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	
	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	$ArrayRegistration = $_SESSION[ 'ArrayRegistration' ];
	
			
			for ($cont = 0; $cont < $capsNum; $cont++){
				for($cont1 = 0; $cont1 < count($ArrayRegistration)+1;$cont1++){
					if($ArrayRegistration[$cont1]['interface']===$ArrayCaps[$cont]['name']){
						$Clientes = 2;}
					
				}


				if (isset($_GET['clientes']) && $cont==0){
					echo "<table id='clientes2'><tr><th>Clientes</th></tr><tr><td>$Clientes &nbsp</td>";}
				else if(isset($_GET['clientes']) && $cont<$capsNum-1){
					echo "<tr><td>$Clientes&nbsp</td></tr>";}
				else if(isset($_GET['clientes']) && $cont==$capsNum-1){
					echo "<tr><td>$Clientes&nbsp</td></tr></table>";}

				
				
				
			

		}


?>
