<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	
	
	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];

	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				
				$BandaCanal = $ArrayCaps[$cont]['channel.band'];
				
				if (isset($_GET['banda-canal']) && $cont==0){
					echo "<table id='banda-canal2'><tr><th>Banda del canal</th></tr><tr><td>$BandaCanal &nbsp</td>";}
				else if(isset($_GET['banda-canal']) && $cont<$capsNum-1){
					echo "<tr><td>$BandaCanal&nbsp</td></tr>";}
				else if(isset($_GET['banda-canal']) && $cont==$capsNum-1){
					echo "<tr><td>$BandaCanal&nbsp</td></tr></table>";}


				
			

		}


?>
