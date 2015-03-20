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

		
		//Firewall rules
		$firewall = $API->comm("/ip/firewall/filter/print");
		$numFirewall=count($firewall);

		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
		$API->disconnect();}	
				echo "
				<table class='ACLRules'>
				<tr>
					<th>#</th>
					<th>Action</th>
					<th>Src. Address</th>
					<th>Dst. Address</th>
					<th>Protocol</th>
					<th>Src. Port</th>
					<th>Dst. Port</th>
				</tr>";
				
				for ($cont = 0; $cont < $numFirewall; $cont++){
					echo "<tr>";
					echo "<td>$cont</td>";	
					echo "<td>".$firewall[$cont]['action']."</td>";
					echo "<td>".$firewall[$cont]['src-address']."</td>";		
					echo "<td>".$firewall[$cont]['dst-address']."</td>";		
					echo "<td>".$firewall[$cont]['protocol']."</td>";
					echo "<td>".$firewall[$cont]['src-port']."</td>";
					echo "<td>".$firewall[$cont]['dst-port']."</td>";
					echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableRule$cont' value='X' class='button'/>
							</form></td>";		
					echo "</tr>";	
					
				}
						
				
				echo "</table>";
		
?>
