<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	
	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];

			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				$SSID = $ArrayCaps[$cont]['configuration.ssid'];
				
				if (isset($_GET['SSID']) && $cont==0){
					echo "<table id='SSID2'><tr><th>SSID</th></tr><tr><td>$SSID &nbsp</td>";}
				else if(isset($_GET['SSID']) && $cont<$capsNum-1){
					echo "<tr><td>$SSID&nbsp</td></tr>";}
				else if(isset($_GET['SSID']) && $cont==$capsNum-1){
					echo "<tr><td>$SSID&nbsp</td></tr></table>";}


				
				
			

		}


?>
