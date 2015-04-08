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

		//puerto Acceso, Trunk, NoSwitchport
		$estadoPort = $API->comm("/interface/ethernet/switch/port/print");

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
				echo "<table class='tablePorts'>";
				for ($cont = 0; $cont < $numPorts; $cont++){
						echo '<tr><td><b>'.$estadoPort[$cont]['name'].'</b></td>';
						echo "<td>";
//Detectar Si es Vlan, Trunk, No Switchport En RB
						if($identidadRS == 'RB'){
							if($estadoPort[$cont]['vlan-mode']!='disabled' and $estadoPort[$cont]['vlan-header']=='always-strip'){
								echo "Access Vlan: ".$estadoPort[$cont]['default-vlan-id'];
							}
							else if($estadoPort[$cont]['vlan-mode']!='disabled' and $estadoPort[$cont]['vlan-header']=='add-if-missing'){
								echo "Trunk";
							}
							else{
								echo "No Switchport";
							}
						}

						echo '</td></tr>';
				}
				echo '</table>';
?>
