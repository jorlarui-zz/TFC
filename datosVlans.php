<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>

<?php
		$API = new routeros_api();
		$IP = $_SESSION[ 'ip' ];
		$user = $_SESSION[ 'user' ];
		$password = $_SESSION[ 'password' ];
		//Comprobamos conexion API
		if ($API->connect($IP, $user, $password)) {

		//Comprobamos interfaces
		
		$Ports = $API->comm("/interface/ethernet/print");
		$numPorts = count($Ports);

		//Modelo
		$modeloCom = $API->comm("/system/routerboard/print");
		$modelo=$modeloCom[0]['model'];
		
		//Vlans
		$vlans = $API->comm("/interface/ethernet/switch/vlan/print");
		$numVlans = count($vlans);

		$colores=["#00fff9","#ff00e7","#a3ff00","#ffdc0b","#ff4400","#7c00ff","#3377ff","#ff7468","#20e523","#fcc512"
			 ,"#9f0d0d","#bc9ad4","#79addd","#e7d1e5","#7bcf5a","#cc8324","#b80f12","#0da9b0","#eea7b9","#1e7352"
			,"#eee117","#b80000","#00137a","#AA0078","#3333FF","#99FF00","#FFCC00","#CC0000","#587498","#E86850"
			,"#FFD800","##00FF00","#FF0000","#0000FF","#FF6600","#A16B23","#C9341C","#ECC5A8","#A3CBF1","#79BFA1"
			,"#FB7374","#FF9900","#4FD5D6","#D6E3B5","#FFD197","#FFFF66","#FFC3CE","#21B6A8","#CDFFFF",""];


		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
		$API->disconnect();}	
				echo "<table class='vlanTable'>";
					$contVlan=-1;
				for ($cont = 0; $cont < $numVlans; $cont++){
						
						$contVlan=$contVlan+1;
						echo "<tr>";
						echo "<th style='border-bottom: 3px solid ".$colores[$contVlan].";'>Vlan ".$vlans[$cont]['vlan-id']."</th>";
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableVlan$cont' value='X' class='buttonDisable'/>
							</form></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>".$vlans[$cont]['ports']."</td>";
						
						echo "</tr>";
						
					
				}		
				echo "</table>";
		
?>
