<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
			
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				$PoderTransmision = $ArrayCaps[$cont]['channel.tx-power'];
				


				if (isset($_GET['poder-transmision']) && $cont==0){
					echo "<table id='poder-transmision2'><tr><th>Poder de transmisión</th></tr><tr><td>$PoderTransmision dBm&nbsp</td>";}
				else if(isset($_GET['poder-transmision']) && $cont<$capsNum-1){
					echo "<tr><td>$PoderTransmision dBm&nbsp</td></tr>";}
				else if(isset($_GET['poder-transmision']) && $cont==$capsNum-1){
					echo "<tr><td>$PoderTransmision dBm&nbsp</td></tr></table>";}


				
			

		}


?>
