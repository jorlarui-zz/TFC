<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>

<?php
		$API = new routeros_api();
		$IP = $_SESSION[ 'ip' ];
		$user = $_SESSION[ 'user' ];
		$password = $_SESSION[ 'password' ];
		//Comprobamos interfaces
		if ($API->connect($IP, $user, $password)) {
		$Ports = $API->comm("/interface/ethernet/print");
		$numPorts = count($Ports);

		//Estado Link
		$valoresPar= json_encode(range(0, $numPorts-1));
		$valores = substr($valoresPar, 1, -1);

		//Ports VLAN (incluye switchcpu)

		$PortsVlans = $API->comm("/interface/ethernet/switch/port/print");
		$numPortsVlans = count($PortsVlans);

		//Switch
		$switches = $API->comm("/interface/ethernet/switch/print");
		$numSwitches = count($switches);

		//Modelo
		$modeloCom = $API->comm("/system/routerboard/print");
		$modelo=$modeloCom[0]['model'];
		
		//Vlans
		$vlans = $API->comm("/interface/ethernet/switch/vlan/print");
		$numVlans = count($vlans);

		//CPU
		$cpuInfo = $API->comm("/system/resource/print");
		
		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
		$API->disconnect();}	
				
			
				for ($cont = 0; $cont < $numVlans; $cont++){
			//Concatenamos todos los puertos de vlan
				$puertosVlan = ($vlans[$cont]['ports'].(",").$puertosVlan);}
			//Eliminamos la ultima coma
				$rest = substr($puertosVlan, 0, -1);
			//Separamos cada puerto en una string delimitando la coma
				$tags = explode(',', $rest);
			//Elegimos el primer puerto y eliminamos a los repetidos
				$result = array_unique($tags);
				for ($cont = 0; $cont < $numPorts; $cont++){
					for($cont1=0;$cont1<count($tags);$cont1++){
						if($Ports[$cont]['name']==$result[$cont1]){
							
					echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#ffffff; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:5px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 1.375 9.2891)' class='st3 st2 st0'>VLAN</text>
								</svg>";}
					}				

				}
				
				
				echo "<img src='images/$modelo.png'>";			
				?>
