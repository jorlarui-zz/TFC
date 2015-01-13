<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>
<?php	

	
	$ArrayCaps = $_SESSION[ 'ArrayCaps' ];
	$capsNum = $_SESSION[ 'capsNum' ];
	$ArrayAddresses = $_SESSION[ 'ArrayAddresses' ];
	$ArrayBridge = $_SESSION[ 'ArrayBridge' ];
	echo $ArrayCaps[1]['mac-address']."<br>";
	echo $ArrayBridge[4]['mac-address'];
	function ip_publica($ArrayAddresses,$ArrayCaps,$cont,$ArrayBridge){
				for ($cont2 = 0;$cont2<count($ArrayBridge);$cont2++){
					
					if($ArrayCaps[$cont]['mac-address']===$ArrayBridge[$cont2]['mac-address']){
						return "2";}
						
				}}

			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				$IPPublica = ip_publica($ArrayAddresses,$ArrayCaps,$cont,$ArrayBridge);
				


				if (isset($_GET['ip-publica']) && $cont==0){
					echo "<table id='ip-publica2'><tr><th>IP P&uacuteblica</th></tr><tr><td>$IPPublica &nbsp</td>";}
				else if(isset($_GET['ip-publica']) && $cont<$capsNum-1){
					echo "<tr><td>$IPPublica&nbsp</td></tr>";}
				else if(isset($_GET['ip-publica']) && $cont==$capsNum-1){
					echo "<tr><td>$IPPublica&nbsp</td></tr></table>";}


				
			}

		


?>

