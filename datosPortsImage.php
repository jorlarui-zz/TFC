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
		//Estado Link

		$valoresPar= json_encode(range(0, $numPorts-1));
		$valores = substr($valoresPar, 1, -1);

		//Switch
		$switches = $API->comm("/interface/ethernet/switch/print");
		$numSwitches = count($switches);

		//Ports Switch
		$portsSwitch = $API->comm("/interface/ethernet/switch/port/print");
		$numPortsSwitch = count($portsSwitch);

		//puerto Acceso, Trunk, NoSwitchport de RB
		$estadoPort = $API->comm("/interface/ethernet/switch/port/print");

		//puerto Trunk CR
		$estadoTrunkCR = $API->comm("/interface/ethernet/switch/egress-vlan-tag/print");

		//puerto Acceso CR
		$estadoAccessCR = $API->comm("/interface/ethernet/switch/ingress-vlan-translation/print");

		//CPU
		$cpuInfo = $API->comm("/system/resource/print");
		//RB o CS
		$routeroSwitch = $cpuInfo[0]['board-name'];
		//Saber iniciales Router o Switch
		$identidadRS = substr($routeroSwitch,0,2);

		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
		$API->disconnect();}	


//Sacar valores String TRUNK CR
			for ($cont = 0; $cont < count($estadoTrunkCR); $cont++){
			//Concatenamos todos los puertos de trunk
				$puertosTrunk = ($estadoTrunkCR[$cont]['tagged-ports'].(",").$puertosTrunk);}
			//Eliminamos la ultima coma
				$rest = substr($puertosTrunk, 0, -1);
			//Separamos cada puerto en una string delimitando la coma
				$tags = explode(',', $rest);
			//Elegimos el primer puerto y eliminamos a los repetidos
				$resultTrunk = array_unique($tags);

				for ($cont = 0; $cont < $numPorts; $cont++){
				
				if($statusPorts[$cont]['status']=='link-ok'){
					if($identidadRS == 'RB'){
						if($estadoPort[$cont+1]['vlan-mode']!='disabled' and $estadoPort[$cont+1]['vlan-header']=='always-strip'){
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#00fff9; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:9px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 4.375 10.2891)' class='st3 st2 st0'>A</text>
								</svg>";
							}
							else if($estadoPort[$cont+1]['vlan-mode']!='disabled' and $estadoPort[$cont+1]['vlan-header']=='add-if-missing'){
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#ff00e7; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:9px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 3.375 10.2891)' class='st3 st2 st0'>T</text>
								</svg>";
							}
							else{
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#a3ff00; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:9px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 2.375 10.2891)' class='st3 st2 st0'>NS</text>
								</svg>";
							}
					
					}

				else if(strcmp($identidadRS,"CR") == 0 ){
//DIBUJAMOS NS EN TODOS LOS SWITCHPORTS						
						
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#a3ff00; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:9px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 2.375 10.2891)' class='st3 st2 st0'>NS</text>
								</svg>";
							

//DIBUJAR TRUNK CR		
						for($cont1 = 0; $cont1 < count($resultTrunk); $cont1++){
							if($resultTrunk[$cont1]==$Ports[$cont]['name']){
								
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#ff00e7; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:9px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 3.375 10.2891)' class='st3 st2 st0'>T</text>
								</svg>";
							}
						}

//DIBUJAR ACCESS CR
						for($cont1 = 0; $cont1 < count($estadoAccessCR); $cont1++){
							if($estadoAccessCR[$cont1]['ports']==$Ports[$cont]['name']){
								
								if($estadoAccessCR[$cont1]['new-customer-vid']!= 4095){	
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#00fff9; enable-background:new 0 0 15 11;' xml:space='preserve'>
								<style type='text/css'>
								<![CDATA[
								.st0{font-size:9px;}
								.st2{font-family:'Open Sans';}
								.st3{fill:#000;}
								]]>
								</style>

								<polygon class='st1' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
								<text transform='matrix(1.0151 0 0 1 4.375 10.2891)' class='st3 st2 st0'>A</text>
								</svg>";
							}}
						}
						
					}
				}
			}

				echo "<img src='images/$modelo.png'>";			
?>
