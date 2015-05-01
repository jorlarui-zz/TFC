<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
			/*var auto_refresh = setInterval(function (){
			$('#refreshImage').load('datosPortsImage.php');
			$('#refreshPorts').load('datosPorts.php');
			}, 2000);*/

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

		//puerto Acceso, Trunk, NoSwitchport de RB
		$estadoPort = $API->comm("/interface/ethernet/switch/port/print");

		//puerto Trunk CS
		$estadoTrunkCS = $API->comm("/interface/ethernet/switch/egress-vlan-tag/print");

		//puerto Acceso CS
		$estadoAccessCS = $API->comm("/interface/ethernet/switch/ingress-vlan-translation/print");


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
		<li><a href="Vlans.php">Vlans</a></li>
                <li class="active"><a href="Ports.php">Ports</a></li>
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


					if($identidadRS == 'CR'){
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
					for ($cont = 0; $cont < count($estadoAccessCS); $cont++){
						echo '<tr><td>'.$estadoAccessCS[$cont]['ports'].'</td>';
						echo '<td>'.$estadoAccessCS[$cont]['new-customer-vid'].'</td>';
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
					for ($cont = 0; $cont < count($estadoTrunkCS); $cont++){
						echo '<tr><td>'.$estadoTrunkCS[$cont]['vlan-id'].'</td>';
						echo '<td>'.$estadoTrunkCS[$cont]['tagged-ports'].'</td>';
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

//SI ES RB se cogen puertos sin switch-cpu y si es CR se cogen con switch-cpu

			if(strcmp($identidadRS,"RB") == 0 ){
				for ($cont = 0; $cont < $numPorts; $cont++){
			
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
						if(strcmp($identidadRS,"RB") == 0 ){
							echo "Native Vlan: <input name='nativeVLAN' type='number' min='0' max='4095' placeholder='100'/>";
						}
						else{
							echo "Vlan ID: <input name='trunkVlansID' type='number' min='0' max='4095' placeholder='100'/>";
								echo "<br>";
								for ($cont = 0; $cont < $numPortsSwitch; $cont++){
								
								echo "<input type='checkbox' name='checkbox[]' value='".$portsSwitch[$cont]['name']."'/>".$portsSwitch[$cont]['name']."</br>";  								
								}
					
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
$trunkVlansID = $_POST['trunkVlansID'];
$puertosSelTrunk= "";
foreach($_POST['checkbox'] as $value)
 {
    $puertosSelTrunk.= $value.',';
 }



	if(isset($_POST['submitButton'])){
		if($modoPuerto == "NoSwitchport"){
			if(strcmp($identidadRS,"RB") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
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
				$API->disconnect();
				}}
			
		}

		/*if($modoPuerto == "NoSwitchport"){
			if(strcmp($identidadRS,"CR") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
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
				$API->disconnect();
				}}
			
		}*/
			


		if($modoPuerto == "Access"){
			if(strcmp($identidadRS,"RB") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/port/set", array(
         			 ".id"     => $interfaz,
          			"vlan-mode" => "secure",
          			"vlan-header" => "always-strip",
				"default-vlan-id" => $accessVlanID
				));
				$API->disconnect();
				}
			}
		

		
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

		if($modoPuerto == "Trunk"){
			if(strcmp($identidadRS,"RB") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/port/set", array(
         			 ".id"     => $interfaz,
          			"vlan-mode" => "secure",
          			"vlan-header" => "add-if-missing",
				"default-vlan-id" => $nativeVLAN
				));
				$API->disconnect();
				}
			}

			else if(strcmp($identidadRS,"CR") == 0 ){
				$API = new routeros_api();
				if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/egress-vlan-tag/add", array(
         			 "tagged-ports"     => $puertosSelTrunk,
          			"vlan-id" => $trunkVlansID
				));
				$API->disconnect();
				}
			}
		}
	}
		

	


?>

<!-- Eliminar Access o Trunk-->
<?php
for ($cont = 0; $cont < count($estadoAccessCS); $cont++){
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

for ($cont = 0; $cont < count($estadoTrunkCS); $cont++){
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
