<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
			var auto_refresh = setInterval(function (){
			$('#refreshImage').load('datosPortImage.php').fadeIn("fast");
			$('#refreshPorts').load('datosPort.php').fadeIn("fast");
			}, 2000);

			var auto_refresh = setInterval(function (){
			$('#info').load('datosCPU.php').fadeIn("fast");
			}, 1000);
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

		//Todas Interfaces
		$Interfaces= $API->comm("/interface/print");
		$numInterfaces = count($Interfaces);

		//Modelo
		$modeloCom = $API->comm("/system/routerboard/print");
		$modelo=$modeloCom[0]['model'];
		//Estado Link

		$valoresPar= json_encode(range(0, $numPorts-1));
		$valores = substr($valoresPar, 1, -1);

		//Switch
		$switches = $API->comm("/interface/ethernet/switch/print");
		$numSwitches = count($switches);

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
	<link rel='stylesheet' href='css/stylePorts.css'/>
	<?php
		echo "<link rel='stylesheet' href='css/stylePorts$modelo.css'/>";
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
                <li><a href="#">Log Out&nbsp&nbsp&nbsp</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right"  style="margin-right:70px;">
                <li><a href="Status.php">Status</a></li>
              <li><a href="Switch.php">Switch</a></li>
		<li><a href="Vlans.php">Vlans</a></li>
                <li><a href="Ports.php">Ports</a></li>
		<li class="active"><a href="Routing.php">Routing</a></li>
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
					
					echo "<svg version='1.1' id='etherGreen$cont' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'
	 				width='5.2%' height='5.2%' viewBox='0 0 15 11' style='enable-background:new 0 0 15 11;' xml:space='preserve''>
					<polygon class='st0' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
					</svg>";
					}
				else if($statusPorts[$cont]['status']=='no-link'){			
					}
				
				}
				echo "<img src='images/$modelo.png'>";			
				?>
 				 </div> 
				</div>
			<div class="col-lg-3" id="info">
			<?php
			echo "Model: ";
				echo $modelo."</br>";
				echo "CPU:  <div class='progress' style='margin-bottom: 0px;'>
  					  <div class='progress-bar' role='progressbar' aria-valuenow='$cpuInfo[0]['cpu-load']' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:".$cpuInfo[0]['cpu-load']."%'>".
    						$cpuInfo[0]['cpu-load']."%
 					 </div>
				
				</div>";

				echo "Uptime: ".$cpuInfo[0]['uptime'];
			?>
			</div>
			<div class="col-lg-1"></div>		
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 info-box">
			<div class="col-lg-2"></div>
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
					
			<div>
				<form method='post' action='#' name='formRouting'>
				Interface: 
				<select name='interfaces'>
				<option value>Interface</option>
				<?php
			
				for ($cont = 0; $cont < $numInterfaces; $cont++){
			
				$interfazSel = $Interfaces[$cont]['name'];
				echo "<option value=$interfazSel>$interfazSel</option>";
			
				}		
				echo "</select>";
				?>
				</br>VLAN ID: <input name='VlanID' type='number' min='0' max='4095' placeholder='100'/>
				</br>VLAN Name: <input name='VlanName' type='text' placeholder='Management VLAN'/>
				</br>IP Address: <input name='VlanAddress' type='text' placeholder='192.168.100.1/24'/>

				</br><input type='submit' name='submitButton' value='Submit'/>
				</form>

			</div>	

			
			<div class="col-lg-2"></div>
			
	</div>

<?php

$interfaz = $_POST['interfaces'];
$VlanID = $_POST['VlanID'];
$VlanName = $_POST['VlanName'];
$VlanAddress = $_POST['VlanAddress'];

	if(isset($_POST['submitButton'])){
		if(strcmp($identidadRS,"RB") == 0 ){
			$API = new routeros_api();
			if ($API->connect($IP, $user, $password)) {
			$API->comm("/interface/vlan/add", array(
         		 "interface"     => $interfaz,
          		"vlan-id" => $VlanID,
          		"name" => $VlanName,
			));

			$API->comm("/ip/address/add", array(
			"address"=> $VlanAddress, 
			"interface"=> $VlanName,
			));
			$API->disconnect();
			}
		}
	}


?>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>	


</body>
</html>
