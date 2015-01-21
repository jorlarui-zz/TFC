<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>


<script type="text/javascript">
  $(document).ready(function(){
			var auto_refresh = setInterval(function (){
			$('#refreshImage').load('datosPortImage.php').fadeIn("slow");
			$('#refreshPorts').load('datosPort.php').fadeIn("slow");
			}, 2000);
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
		$ARRAY = $API->comm("/interface/print");
		$interfaces = count($ARRAY);
		$Ports = $API->comm("/interface/ethernet/print");
		$numPorts = count($Ports);

		//Modelo
		$modeloCom = $API->comm("/system/routerboard/print");
		$modelo=$modeloCom[0]['model'];
		//Estado Link

		$valoresPar= json_encode(range(0, $numPorts-1));
		$valores = substr($valoresPar, 1, -1);
		echo $valores;


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
	<?php
		echo "<link rel='stylesheet' href='css/stylePorts$modelo.css'/>";
	?>
		

	
<!--Script para dibujar las graficas-->



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
                <li class="active"><a href="Ports.php">Ports</a></li>
		<li><a href="Switch.php">Switch</a></li>
                <li><a href="Vlans.php">Vlans</a></li>
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
	 				width='5.2%' height='5.2%' viewBox='0 0 15 11' style='enable-background:new 0 0 15 11;' xml:space='preserve'>
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
			<div class="col-lg-2">INFO HERE</div>
			<div class="col-lg-2"></div>		
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 info-box">
				<table>
				<td>
					 <table id="refreshPorts">
          
           

				<?php
					for ($cont = 0; $cont < $numPorts; $cont++){
						
						echo "<tr>";
						echo "<td>".$Ports[$cont]['name']."</td>";
						if($statusPorts[$cont]['status']=='link-ok'){
							echo "<td class='link-ok'>";			
						}
						else if($statusPorts[$cont]['status']=='no-link'){
							echo "<td class='no-link'>";				
						}
						echo $statusPorts[$cont]['status']."</td>";
						}			
						echo "</tr>";
				?>
					</table>
				</td>
				<td>
				<table id="PortsButtons">
				 

				<?php
					for ($cont = 0; $cont < $numPorts; $cont++){
						echo "<tr>";
						echo "<td><form name='button$cont' method='post' action='Ports.php'>
							<input type='submit' name='enablePort$cont' value='&#10004' class='button'/>
							<input type='submit' name='disablePort$cont' value='X' class='button'/>
							</form></td>";
						echo "</tr>";
					}	
				?>
				</table>
				</td>
			</table>
			</div>
			
	</div>

<?php
		for ($cont = 0; $cont < $numPorts; $cont++){
		if(isset($_POST['enablePort'.$cont])){
			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->write("/interface/ethernet/set",false);
				$API->write("=disabled=no",false);
				$API->write("=.id=".$Ports[$cont]['name']);
				$ARRAY = $API->read();
				$API->disconnect();
		}}
		}

		for ($cont = 0; $cont < $numPorts; $cont++){
		if(isset($_POST['disablePort'.$cont])){
			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->write("/interface/ethernet/set",false);
				$API->write("=disabled=yes",false);
				$API->write("=.id=".$Ports[$cont]['name']);
				$ARRAY = $API->read();
				$API->disconnect();
		}}
		}


?>

<script src="bootstrap/js/bootstrap.min.js"></script>	


			
<!-- Dibujamos las graficas-->


<script type="text/javascript" src="highchart/js/highcharts.js"></script>
<script type="text/javascript" src="highchart/js/themes/gray2.js"></script>

</body>
</html>
