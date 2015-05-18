<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
			var auto_refresh = setInterval(function (){
			$('#refreshImage').load('datosPortsImage.php');
			//$('#refreshPorts').load('datosPorts.php');
			//}, 3000);

			var auto_refresh = setInterval(function (){
			$('#info').load('datosCPU.php').fadeIn("fast");
			}, 1000);
		});		
			

 
	
  </script>

<script type="text/javascript">
    $(document).ready(function(){
        $("input[value=Access]:radio" ).change(function(){
                $('#areaAccess').show("fast");
		$('#areaTrunk').hide("fast");
		$('#areaNoSwitchport').hide("fast");
		$('input[name=submitButton]').show("fast");
            });

	$("input[value=Trunk]:radio" ).change(function(){
                $('#areaTrunk').show("fast");
		$('#areaAccess').hide("fast");
		$('#areaNoSwitchport').hide("fast");
		$('input[name=submitButton]').show("fast");
            });

	$("input[value=NoSwitchport]:radio" ).change(function(){
                $('#areaNoSwitchport').show("fast");
		$('#areaTrunk').hide("fast");
		$('#areaAccess').hide("fast");
		$('input[name=submitButton]').show("fast");
            });
});
</script>

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

		//puerto Trunk CR
		$estadoTrunkCR = $API->comm("/interface/ethernet/switch/egress-vlan-tag/print");

		//puerto Acceso CR
		$estadoAccessCR = $API->comm("/interface/ethernet/switch/ingress-vlan-translation/print");

		//VLANS
		$vlans = $API->comm("/interface/ethernet/switch/vlan/print");

		//IP ADDRESS
		$ipAddress = $API->comm("/ip/address/print");

		//CPU
		$cpuInfo = $API->comm("/system/resource/print");
		//RB o CR
		$routeroSwitch = $cpuInfo[0]['board-name'];
		//Saber iniciales Router o Switch
		$identidadRS = substr($routeroSwitch,0,2);

		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
		$API->disconnect();
		}
		else {
			header( 'Location:Login.php?notLogin=true' );}

?>

<html>
<head>
	<title>Mikrotik Web Controller</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="css/style.css"/>
	<?php
		echo "<link rel='stylesheet' href='css/style$modelo.css'/>";
	?>
		





</head>
<body>


	
    <nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <p class="navbar-text"><img src="images/logolittle.png"></p>
        </div>
       
        <div id="navbar" class="collapse navbar-collapse">

	    <ul class="nav navbar-nav navbar-right">
                <li><a id="logOut" href="Status.php?logOut=yes">Log Out&nbsp&nbsp&nbsp</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-center">
                <li><a href="Status.php">Status</a></li>
              <li><a href="Switch.php">Switch</a></li>
                <li class="active"><a href="Vlans.php">VLANs</a></li>
		<li><a href="Routing.php">Routing</a></li>
		<li><a href="ACLs.php">ACLs</a></li>
            </ul>
        </div> 
    	</div>
    </nav>



<div class="container" style="margin-top:50px;">

      	<div class="row">
		<div class="col-lg-12 switch-box">
			<div class="col-lg-2"></div>
			<div class="col-lg-6">
				 <div id="refreshImage">
    
				<?php

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
					if(strcmp($identidadRS,"RB") == 0 ){

					//COLOR ACCESS IMAGE
						if($portsSwitch[$cont+1]['vlan-mode']!='disabled' and $portsSwitch[$cont+1]['vlan-header']=='always-strip'){
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:$contColor; enable-background:new 0 0 15 11;' xml:space='preserve'>
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
				//COLOR TRUNK IMAGE
							else if($portsSwitch[$cont+1]['vlan-mode']!='disabled' and $portsSwitch[$cont+1]['vlan-header']=='add-if-missing'){
								echo "<svg version='1.1' id='etherMaster$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='5.2%' height='5.2%' viewBox='0 0 15 11' style='fill:#00fff9; enable-background:new 0 0 15 11;' xml:space='preserve'>
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
				//COLOR NO SWITCHPORT
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
 				 </div> 
				</div>
			<div class="col-lg-3" id="info">
			<?php
			echo "<div id='model'>
				<p class='infoBold'>Model: </p>";
				echo "<p>".$modelo."</p>
			</div></br>";
				echo "<div id='cpu'>
					<div id='cpu2'><p class='infoBold'>CPU: </p></div>
					<div class='progress' style='margin-bottom: 0px;'>
  					  <div class='progress-bar' role='progressbar' aria-valuenow='$cpuInfo[0]['cpu-load']' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:".$cpuInfo[0]['cpu-load']."%'>".
    						$cpuInfo[0]['cpu-load']."%
 					 </div>
				</div>
				</div>";

				echo "<div id='uptime'><p class='infoBold'>Uptime: </p><p>".$cpuInfo[0]['uptime']."</p></div>";
			?>
			</div>
			<div class="col-lg-1"></div>		
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 info-box">
			<div class="col-lg-2"></div>
			<div class="col-lg-4">
			<div id="refreshPorts">
<!--TABLA RB-->

<?php
				
				if(strcmp($identidadRS,"RB") == 0 ){
					echo "Access
						<table class='tablePortsCR'>
					";
					echo '<tr><td>Ports</td>';
					echo '<td>VLAN</td></tr>';
					for ($cont = 0; $cont < count($portsSwitch); $cont++){
						if($portsSwitch[$cont]['vlan-mode']!='disabled' and $portsSwitch[$cont]['vlan-header']=='always-strip'){
						echo '<tr><td>'.$portsSwitch[$cont]['name'].'</td>';
						echo '<td>'.$portsSwitch[$cont]['default-vlan-id'].'</td>';
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableAccessRB$cont' value='X' class='buttonDisable'/>
							</form></td>";
			

						echo '</tr>';}
					}
				
					echo "</table>";
							
					echo "Trunk
						<table class='tablePortsCR'>
					";
					echo '<tr><td>Ports</td>';
					echo '<td>Allowed VLANs</td>';
					echo '<td>Native VLAN</td></tr>';
					for ($cont = 0; $cont < count($portsSwitch); $cont++){
						if($portsSwitch[$cont]['vlan-mode']!='disabled' and $portsSwitch[$cont]['vlan-header']=='add-if-missing'){
						echo '<tr><td>'.$portsSwitch[$cont]['name'].'</td>';
						echo '<td>';
							for($cont2 = 0; $cont2 < count($vlans); $cont2++){
							$pos = strpos($vlans[$cont2]['ports'], $portsSwitch[$cont]['name']);
							
							if($pos !== false){
								if($cont2 < count($vlans) -1){
									echo $vlans[$cont2]['vlan-id'].",";
								}
								else{
									echo $vlans[$cont2]['vlan-id'];
								}
							}
						}	

						echo '</td>';
						echo '<td>'.$portsSwitch[$cont]['default-vlan-id'].'</td>';
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableTrunkRB$cont' value='X' class='buttonDisable'/>
							</form></td>";
			

						echo '</tr>';}
					}
					echo "</table>";



				}
			?>
<!--TABLA CR-->
			<?php
				
				if(strcmp($identidadRS,"CR") == 0 ){
					echo "Access
						<table class='tablePortsCR'>
					";
					echo '<tr><td>Ports</td>';
					echo '<td>VLAN</td></tr>';
					for ($cont = 0; $cont < count($estadoAccessCR); $cont++){
						echo '<tr><td>'.$estadoAccessCR[$cont]['ports'].'</td>';
						echo '<td>'.$estadoAccessCR[$cont]['new-customer-vid'].'</td>';
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableAccessCR$cont' value='X' class='buttonDisable'/>
							</form></td>";
			

						echo '</tr>';
					}
				
					echo "</table>";
							
					echo "Trunk
						<table class='tablePortsCR'>
					";
					echo '<tr><td>VLAN</td>';
					echo '<td>Ports</td></tr>';
					for ($cont = 0; $cont < count($estadoTrunkCR); $cont++){
						echo '<tr><td>'.$estadoTrunkCR[$cont]['vlan-id'].'</td>';
						echo '<td>'.$estadoTrunkCR[$cont]['tagged-ports'].'</td>';
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableTrunkCR$cont' value='X' class='buttonDisable'/>
							</form></td>";
			

						echo '</tr>';
					}
				
					echo "</table>";


				}
			?>
			</div>
			</div>
			<div class="col-lg-4  portsBox">
				<form method='post' action='#' name='formPorts'>
				Select interfaces:
				<div class='styled-select'>
				<select name='interfaces'>
				<option value>Interfaz</option>
			<?php



			if(strcmp($identidadRS,"RB") == 0 ){
				for ($cont = 0; $cont < count($Ports); $cont++){
			
					$interfazSel = $Ports[$cont]['name'];
					echo "<option value=$interfazSel>$interfazSel</option>";
			
				}		
			}

			else if(strcmp($identidadRS,"CR") == 0 ){
				for ($cont = 0; $cont < $numPortsSwitch; $cont++){
			
					$interfazSel = $portsSwitch[$cont]['name'];
					echo "<option value=$interfazSel>$interfazSel</option>";
			
				}		
			}

			
			echo "</select></div>";
			?>

			</br>
			Access <input type='radio' name='form' value='Access'/>
			Trunk <input type='radio' name='form' value='Trunk'/>
			No Switchport<input type='radio' name='form' value='NoSwitchport'/>

			<div style='display: none' id='areaAccess'>
					Access Vlan: <input name='accessVlanID' type='number' min='0' max='4095' placeholder='100'/></br>
					
				
					</br>
				
			</div>	

			<div style='display: none' id='areaTrunk'>
					<?php
						echo "Allowed VLANS: <input name='allowedVlans' type='text' placeholder='80, 90, 100'/></br>";

						if(strcmp($identidadRS,"RB") == 0 ){
							echo "Native Vlan: <input name='nativeVLAN' type='number' min='0' max='4095' placeholder='100'/></br>";
							//echo "Switch CPU Fallback: <input name='cpuFallback' type='checkbox'/>";
						}
						
							
								
					
						?>
					</br>
					
				
			</div>	
			<div style='display: none' id='areaNoSwitchport'>
					Ip Address: <input name='noSwitchportIP' type='text' placeholder='192.168.100.10/24'/>
					</br>
					
			</div>
			<input type='submit' name='submitButton' value='Submit' style='display: none'/>
			</form>
			
			<div class="col-lg-2"></div>
			
	</div>

<?php
$interfaz = $_POST['interfaces'];
$modoPuerto = $_POST['form'];
$accessVlanID = $_POST['accessVlanID'];
$noSwitchportIP = $_POST['noSwitchportIP'];
$nativeVLAN = $_POST['nativeVLAN'];
//Separar allowedVlans en un array
$allowedVlans = $_POST['allowedVlans'];
$allowedVlans = explode(',', $allowedVlans);


$cpuFallback = $_POST['cpuFallback'];
$puertosSelTrunk= "";


$contadorVlans = 0;
foreach($_POST['checkbox'] as $value)
 {
    $puertosSelTrunk.= $value.',';
 }

//////// NO SWITCHPORT ////////
///// RB /////

	if(isset($_POST['submitButton'])){
	
		if($modoPuerto == "NoSwitchport"){
			if(strcmp($identidadRS,"RB") == 0 ){
				for($cont = 0;$cont < count($vlans); $cont++){
					//Check if PORT exist in another VLAN, if exist DELETE PORT and create in NEW
					$pos= strpos($vlans[$cont]['ports'], $interfaz);
					
					
					if($pos !== false){
					
						//Get Actual Port to delete
						$actualPort = $interfaz;
						//Get all ports of VLAN
						$previousPort = $vlans[$cont]['ports'];
						//Delete port from previousPort
						$finalPort = str_replace($actualPort,"",$previousPort);
						//Replace ,, to , if the port is deleted in the middle of string
						$finalPort = str_replace(',,',",",$finalPort);
						//Delete the last comma
						if ($finalPort[strlen($finalPort)-1] == ","){
					
							$finalPort = rtrim($finalPort,',');
						}

						//DELETE VLAN if last PORT
						if($previousPort == $actualPort){
							$API = new routeros_api();
							if ($API->connect($IP, $user, $password)) {
							$API->comm("/interface/ethernet/switch/vlan/remove", array(
		 					".id"     => $cont
							));
							$API->disconnect();
							}
						}
						//Delete Port from VLAN
						$API = new routeros_api();
						if ($API->connect($IP, $user, $password)) {
							$API->comm("/interface/ethernet/switch/vlan/set", array(
		 					".id"     => $cont,
							"ports" => $finalPort,
							));
							$API->disconnect();
						
						}
					}

					

					//Check if VLAN exist, if exist $contadorVlans is increased
					if($vlans[$cont]['vlan-id'] == $accessVlanID){
						$contadorVlans++;
					}

				}
				
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				//If VLAN dont exist, a new VLAN is created
					//GET Switch per Port to create VLAN
					$switchPerPort;
					for($cont2=0; $cont2<$numPortsSwitch; $cont2++){
						if($portsSwitch[$cont2]['name'] == $interfaz) {
							$switchPerPort = $portsSwitch[$cont2]['switch'];
							
						}
					}
					if($contadorVlans==0){
						$API->comm("/interface/ethernet/switch/vlan/add", array(
         				 	"ports"     => $interfaz,
          					"switch" => $switchPerPort,
						"vlan-id" => $accessVlanID
					));
					}
				
				//If VLAN exist, edit VLAN and add ports
					if($contadorVlans!=0){
						for($cont = 0; $cont < count($vlans); $cont++){
							if($vlans[$cont]['vlan-id'] == $accessVlanID){
								$previousPort = $vlans[$cont]['ports'];
								$actualPort = $previousPort.",".$interfaz;
								$API->comm("/interface/ethernet/switch/vlan/set", array(
         				 				".id"     => $cont,
          								"ports" => $actualPort,
									));
							}

						}
										
					
						
					}

				//Set PORT mode NO SWITCHPORT
				

					//IF NO IP REMOVE IP ADDRESS				
					if($noSwitchportIP == null){
						for($cont = 0; $cont < count($ipAddress); $cont++){
							if($ipAddress[$cont]['interface'] == $interfaz)
								$API->comm("/ip/address/remove", array(
					 			".id"     => $cont,
								));
							}
						}
							else{
							$API->comm("/interface/ethernet/switch/port/set", array(
         						 ".id"     => $interfaz,
          						"vlan-mode" => "disabled",
          						"vlan-header" => "leave-as-is",
							"default-vlan-id" => "0",
							));

							$API->comm("/ip/address/add", array(
							"address"=> $noSwitchportIP, 
							"interface"=> $interfaz,
							));
							}
				$API->disconnect();
				
			}
		}
//////// NO SWITCHPORT ////////		
///// CR /////
		
			else if(strcmp($identidadRS,"CR") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {

				$API->comm("/ip/address/add", array(
					"address"=> $noSwitchportIP, 
					"interface"=> $interfaz,
				));
				$API->disconnect();
				}}
			
		}
			

//////// ACCESS ////////
///// RB /////
		if($modoPuerto == "Access"){
			if(strcmp($identidadRS,"RB") == 0 ){

			
				for($cont = 0;$cont < count($vlans); $cont++){
					//Check if PORT exist in another VLAN, if exist DELETE PORT and create in NEW
					$pos= strpos($vlans[$cont]['ports'], $interfaz);
					
					if($pos !== false){
					
						//Get Actual Port to delelte
						$actualPort = $interfaz;
						//Get all ports of VLAN
						$previousPort = $vlans[$cont]['ports'];
						//Delete port from previousPort
						$finalPort = str_replace($actualPort,"",$previousPort);
						//Replace ,, to , if the port is deleted in the middle of string
						$finalPort = str_replace(',,',",",$finalPort);
						//Delete the last comma
						if ($finalPort[strlen($finalPort)-1] == ","){
					
							$finalPort = rtrim($finalPort,',');
						}

						//DELETE VLAN if last PORT
						if($previousPort == $actualPort){
							$API = new routeros_api();
							if ($API->connect($IP, $user, $password)) {
							$API->comm("/interface/ethernet/switch/vlan/remove", array(
		 					".id"     => $cont
							));
							$API->disconnect();
							}
						}
						//Delete Port from VLAN
						$API = new routeros_api();
						if ($API->connect($IP, $user, $password)) {
							$API->comm("/interface/ethernet/switch/vlan/set", array(
		 					".id"     => $cont,
							"ports" => $finalPort,
							));
							$API->disconnect();
						
						}
						}

					

					//Check if VLAN exist, if exist $contadorVlans is increased
					if($vlans[$cont]['vlan-id'] == $accessVlanID){
						$contadorVlans++;
					}

				}
				
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				//If VLAN dont exist, a new VLAN is created
					//GET Switch per Port to create VLAN
					$switchPerPort;
					for($cont2=0; $cont2<$numPortsSwitch; $cont2++){
						if($portsSwitch[$cont2]['name'] == $interfaz) {
							$switchPerPort = $portsSwitch[$cont2]['switch'];
							
						}
					}
					if($contadorVlans==0){
						$API->comm("/interface/ethernet/switch/vlan/add", array(
         				 	"ports"     => $interfaz,
          					"switch" => $switchPerPort,
						"vlan-id" => $accessVlanID
					));
					}
				
				//If VLAN exist, edit VLAN and add ports
					if($contadorVlans!=0){
						for($cont = 0; $cont < count($vlans); $cont++){
							if($vlans[$cont]['vlan-id'] == $accessVlanID){
								$previousPort = $vlans[$cont]['ports'];
								$actualPort = $previousPort.",".$interfaz;
								$API->comm("/interface/ethernet/switch/vlan/set", array(
         				 				".id"     => $cont,
          								"ports" => $actualPort,
									));
							}

						}
										
					
						
					}

				//Set PORT mode ACCESS
				$API->comm("/interface/ethernet/switch/port/set", array(
         			 ".id"     => $interfaz,
          			"vlan-mode" => "secure",
          			"vlan-header" => "always-strip",
				"default-vlan-id" => $accessVlanID
				));
				$API->disconnect();
				}
			}
		

//////// ACCESS ////////
///// CR /////		
			else if(strcmp($identidadRS,"CR") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/ingress-vlan-translation/add", array(
         			 "ports"     => $interfaz,
          			"sa-learning" => "yes",
          			"customer-vid" => "0",
				"new-customer-vid" => $accessVlanID
				));
				$API->disconnect();
				}
			}
		}

//////// TRUNK ////////
///// RB /////

		if($modoPuerto == "Trunk"){
			if(strcmp($identidadRS,"RB") == 0 ){

		//Create Vlan per Allowed Vlan in Trunk
			for($contAllowed = 0; $contAllowed < count($allowedVlans);$contAllowed++){
				
				for($cont = 0;$cont < count($vlans); $cont++){

			//Check if VLAN exist, if exist $contadorVlans is increased
			
					if($vlans[$cont]['vlan-id'] == $allowedVlans[$contAllowed]){
						$contadorVlans++;
					}
					


				}
				
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				//If VLAN dont exist, a new VLAN is created
					//GET Switch per Port to create VLAN
					$switchPerPort;
					for($cont2=0; $cont2<$numPortsSwitch; $cont2++){
						if($portsSwitch[$cont2]['name'] == $interfaz) {
							$switchPerPort = $portsSwitch[$cont2]['switch'];
							
						}
					}
					if($contadorVlans==0){
						$API->comm("/interface/ethernet/switch/vlan/add", array(
         				 	"ports"     => $interfaz,
          					"switch" => $switchPerPort,
						"vlan-id" => $allowedVlans[$contAllowed]
					));
					
					}
					
				//If VLAN exist, edit VLAN and add ports
					else if($contadorVlans!=0){
						for($cont = 0; $cont < count($vlans); $cont++){
							if($vlans[$cont]['vlan-id'] == $allowedVlans[$contAllowed]){
								$previousPort = $vlans[$cont]['ports'];
								$actualPort = $previousPort.",".$interfaz;
								$API->comm("/interface/ethernet/switch/vlan/set", array(
         				 				".id"     => $cont,
          								"ports" => $actualPort,
									));
							}

						}
										
					
						
					}
				//Reseteamos contadorVlans para todas las allowed Vlans
					$contadorVlans = 0;

				}

				//Set PORT mode TRUNK
				$API->comm("/interface/ethernet/switch/port/set", array(
         			 ".id"     => $interfaz,
          			"vlan-mode" => "secure",
          			"vlan-header" => "add-if-missing",
				"default-vlan-id" => $nativeVLAN
				));
				$API->disconnect();
				}
			}

		
//////// TRUNK ////////
///// CR /////
			

			else if(strcmp($identidadRS,"CR") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/egress-vlan-tag/add", array(
         			 "tagged-ports"     => $puertosSelTrunk,
          			"vlan-id" => $allowedVlans
				));
				$API->disconnect();
				}
			}
		}
	}
		

	


?>


<!--------------------------- Eliminar Access o Trunk----------------------------->
<?php

//////// DELETE ACCESS ////////
///// RB /////


for ($cont = 0; $cont < count($portsSwitch); $cont++){
		if(isset($_POST['disableAccessRB'.$cont])){
		
			//Delete determinated port
			for($cont2 = 0; $cont2 < count($vlans); $cont2++){
			if($portsSwitch[$cont]['default-vlan-id'] == $vlans[$cont2]['vlan-id']){
				//Get Actual Port to delelte
				$actualPort = $portsSwitch[$cont]['name'];
				//Get all ports of VLAN
				$previousPort = $vlans[$cont2]['ports'];
				//Delete port from previousPort
				$finalPort = str_replace($actualPort,"",$previousPort);
				//Replace ,, to , if the port is deleted in the middle of string
				$finalPort = str_replace(',,',",",$finalPort);
				//Delete the last comma
				if ($finalPort[strlen($finalPort)-1] == ","){
					
					$finalPort = rtrim($finalPort,',');
				}

			//DELETE VLAN if last PORT
				if($previousPort == $actualPort){
					$API = new routeros_api();
					if ($API->connect($IP, $user, $password)) {
					$API->comm("/interface/ethernet/switch/vlan/remove", array(
		 				".id"     => $cont2
						));
					$API->disconnect();
					}
				}
			//Delete Port from VLAN
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/vlan/set", array(
		 				".id"     => $cont2,
						"ports" => $finalPort,
						));
				$API->disconnect();
				}
			}
			}


				//Set port to No Switchport
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/port/set", array(

         			 ".id"     => $cont,
          			"vlan-mode" => "disabled",
          			"vlan-header" => "leave-as-is",
				"default-vlan-id" => "0",
				));
				$API->disconnect();
				}	
		}
}




//////// DELETE TRUNK////////
///// RB /////



for ($cont = 0; $cont < count($portsSwitch); $cont++){


	if(isset($_POST['disableTrunkRB'.$cont])){
		
			
			//Delete determinated port
			//INVERSE FOR TO MAKE BETTER DELETIONG
			for($cont2 = count($vlans) - 1; $cont2 >= 0; $cont2--){
				echo $cont2;
				
				//Get Actual Port to delete
				$actualPort = $portsSwitch[$cont]['name'];
				//Get all ports of VLAN
				$previousPort = $vlans[$cont2]['ports'];
				//Delete port from previousPort
				$finalPort = str_replace($actualPort,"",$previousPort);
				//Replace ,, to , if the port is deleted in the middle of string
				$finalPort = str_replace(',,',",",$finalPort);
				
				//Delete the last comma
				if ($finalPort[strlen($finalPort)-1] == ","){
					
					$finalPort = rtrim($finalPort,',');
				}
			
				//Delete Port from VLAN
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/vlan/set", array(
		 				".id"     => $cont2,
						"ports" => $finalPort,
						));
				}
				
				

			//DELETE VLAN if last PORT
				
				
					if(strcmp($previousPort, $actualPort) === 0){
					$API = new routeros_api();
						if ($API->connect($IP, $user, $password)) {
						$API->comm("/interface/ethernet/switch/vlan/remove", array(
		 					".id"     => $cont2
							));
					
						
					}
				}
			}


				//Set port to No Switchport
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/port/set", array(

         			 ".id"     => $cont,
          			"vlan-mode" => "disabled",
          			"vlan-header" => "leave-as-is",
				"default-vlan-id" => "0",
				));
				$API->disconnect();
				}	
		}
	
}

//////// DELETE ACCESS ////////
///// CR /////
for ($cont = 0; $cont < count($estadoAccessCR); $cont++){
		if(isset($_POST['disableAccessCR'.$cont])){
			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->write("/interface/ethernet/switch/ingress-vlan-translation/remove",false);
				$API->write("=.id=".$cont);
				$Ports = $API->read();
				$API->disconnect();
		}}
		}


//////// DELETE TRUNK ////////
///// CR /////
for ($cont = 0; $cont < count($estadoTrunkCR); $cont++){
		if(isset($_POST['disableTrunkCR'.$cont])){
			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->write("/interface/ethernet/switch/egress-vlan-tag/remove",false);
				$API->write("=.id=".$cont);
				$Ports = $API->read();
				$API->disconnect();
		}}
		}



?>

<!--Boton cerrar sesión-->

<?php
	if($_GET['logOut'] == 'yes'){
		session_destroy();
		header( 'Location:Login.php'); 
}

?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>	


</body>
</html>
