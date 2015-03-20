<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
			var auto_refresh = setInterval(function (){
			$('#refreshImage').load('datosVlansImage.php').fadeIn("fast");
			$('#refreshVlans').load('datosVlans.php').fadeIn("fast");
			}, 3000);
			
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

		//Array Colores,
		$colores=["#00fff9","#ff00e7","#a3ff00","#ffdc0b","#ff4400","#7c00ff","#3377ff","#ff7468","#20e523","#fcc512"
			 ,"#9f0d0d","#bc9ad4","#79addd","#e7d1e5","#7bcf5a","#cc8324","#b80f12","#0da9b0","#eea7b9","#1e7352"
			,"#eee117","#b80000","#00137a","#AA0078","#3333FF","#99FF00","#FFCC00","#CC0000","#587498","#E86850"
			,"#FFD800","##00FF00","#FF0000","#0000FF","#FF6600","#A16B23","#C9341C","#ECC5A8","#A3CBF1","#79BFA1"
			,"#FB7374","#FF9900","#4FD5D6","#D6E3B5","#FFD197","#FFFF66","#FFC3CE","#21B6A8","#CDFFFF",""];
		
		$API->disconnect();
		}
		else {
			header( 'Location:Login.php?notLogin=true' );

		}

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
               <li ><a href="Switch.php">Switch</a></li>
		<li class="active"><a href="Vlans.php">Vlans</a></li>
                <li><a href="Ports.php">Ports</a></li>
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
			<div id="refreshVlans">
				<table class="vlanTable">
				<?php
				$contVlan=-1;
				for ($cont = 0; $cont < $numVlans; $cont++){
						
						$contVlan=$contVlan+1;
						echo "<tr>";
						echo "<th style='border-bottom: 3px solid ".$colores[$contVlan].";'>Vlan ".$vlans[$cont]['vlan-id']." ".$vlans[$cont]['name']."</th>";
						echo "<td><form name='button$cont' method='post'>
							<input type='submit' name='disableVlan$cont' value='X' class='buttonDisable'/>
							</form></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>".$vlans[$cont]['ports']."</td>";
						
						echo "</tr>";
								

							
						

						
					
				}
						
				?>
				</table>
			</div>
			</div>
			<div class="col-lg-6">
				<table>
				<td>
				<table class="vlanTable">
				<?php
					
						echo "<tr>";
						echo "<form action=Vlans.php method=post>";
						echo "<td><tr><font size='2'>VLAN-ID:</font> <input name='vlan-id' style='font-size:13px' type='number' min='0' max='4095' value='0'/></tr>
							
						
						</td>";
						echo "<td>";
						
						
  							for ($cont = 0; $cont < $numPorts; $cont++){
								echo "<input type='checkbox' name='checkbox[]' value='".$Ports[$cont]['name']."'/>".$Ports[$cont]['name']."</br>";  								}
						echo "</br>";
							for ($cont = 0; $cont < $numSwitches; $cont++){
								echo "<input type='radio' name='radioSwitch' value='".$switches[$cont]['name']."'/>".$switches[$cont]['name']."</br>";  						}
						echo "</br><input type='submit' name='formSubmit' value='Submit' /></form>";	
						echo "</td>";
									
						echo "</tr>";
					
				?>
					</table>
				</td>
				<td>
				
			
			</div>		
		</div>
	</div>

</div>

<?php


for ($cont = 0; $cont < $numVlans; $cont++){
		if(isset($_POST['disableVlan'.$cont])){
			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->write("/interface/ethernet/switch/vlan/remove",false);
				$API->write("=.id=".$cont);
				$Ports = $API->read();
				$API->disconnect();
		}}
		}
$vlanId= $_POST['vlan-id'];
$name= $_POST['name'];

$puertosSel= "";
foreach($_POST['checkbox'] as $value)
 {
    $puertosSel.= $value.',';
 }

$switchSel= $_POST['radioSwitch'];

if(isset($_POST['formSubmit'])){
			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->comm("/interface/ethernet/switch/vlan/add", array(
         			 "vlan-id"     => $vlanId,
          			"switch" => $switchSel,
          			"ports" => $puertosSel,
				
				));
		}
		
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
