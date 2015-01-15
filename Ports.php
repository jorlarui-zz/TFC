<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<html>
<head>
	<title>Mikrotik Web Controller</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="css/styleStatus.css"/>
		

	
<!--Script para dibujar las graficas-->
<script> 
	var chart;
	function requestDatta(interface) {
		$.ajax({
			url: 'datosGraficas.php?interface='+interface,
			datatype: "json",
			success: function(data) {
				var midata = JSON.parse(data);
				if( midata.length > 0 ) {
					var TX=parseInt(midata[0].data);
					var RX=parseInt(midata[1].data);
					var x = (new Date()).getTime(); 
					shift=chart.series[0].data.length > 19;
					chart.series[0].addPoint([x, TX], true, shift);
					chart.series[1].addPoint([x, RX], true, shift);
					document.getElementById("trafico").innerHTML=TX + " / " + RX;
				}else{
					document.getElementById("trafico").innerHTML="- / -";
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				console.error("Status: " + textStatus + " request: " + XMLHttpRequest); console.error("Error: " + errorThrown); 
			}       
		});
	}	

	$(document).ready(function() {
			Highcharts.setOptions({
				global: {
					useUTC: false
				}
			});
	

           chart = new Highcharts.Chart({
			   chart: {
				renderTo: 'container',
				animation: Highcharts.svg,
				type: 'spline',
				events: {
					load: function () {
						setInterval(function () {
							requestDatta(document.getElementById("interface").value);
						}, 1000);
					}				
			}
		 },
		 title: {
			text: 'Monitoring'
		 },
		 xAxis: {
			type: 'datetime',
				tickPixelInterval: 150,
				maxZoom: 20 * 1000
		 },
		 yAxis: {
			minPadding: 0.2,
				maxPadding: 0.2,
				title: {
					text: 'Trafico Kbps',
					margin: 80
				}
		 },
            series: [{
                name: 'TX',
                data: []
            }, {
                name: 'RX',
                data: []
            }]
	  });
  });
</script>


</head>
<body>

<?php
		$API = new routeros_api();
		$IP = $_SESSION[ 'ip' ];
		$user = $_SESSION[ 'user' ];
		$password = $_SESSION[ 'password' ];
?>

	
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
                <li><a href="Vlans.php">Vlans</a></li>
		<li><a href="ACLs.php">ACLs</a></li>
            </ul>
        </div> 
    	</div>
    </nav>

     <!--<div class="container top-bar">

      <div class="masthead">
        <img src="images/logolittle.png" width="100" height="30" style="margin-top:20px;"></p>
        <nav>
          <ul class="nav nav-justified">
            <li class="active"><a href="#">STATUS</a></li>
            <li><a href="#">PORTS</a></li>
            <li><a href="#">VLANs</a></li>
            <li><a href="#">ACLs</a></li>
          </ul>
        </nav>
      </div>-->


<div class="container" style="margin-top:50px;">

      	<div class="row">
		<div class="col-lg-12 switch-box">
			<div class="col-lg-2"></div>
			<div class="col-lg-8">
				<img src="images/CRS109-8G-1S-2HnD-IN.png">
			</div>
			<div class="col-lg-2"></div>		
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 info-box">
			<div class="col-lg-2"></div>
			<div class="col-lg-8">
				<?php
					
					//Comprobamos conexion API
					if ($API->connect($IP, $user, $password)) {

			//Comprobamos cuantas interfaces tiene CAPsMAN
			$ARRAY = $API->comm("/interface/print");
			$interfaces = count($ARRAY);
			$API->disconnect();

			//Creamos un formulario que actualiza la gráfica en función de la interfaz seleccionada
			echo "<form action=Status.php method=post>";
			echo "Selecciona interfaz:   ";
			echo "<select name='interfaces' size='1' onchange='this.form.submit()'>";
			echo "<option value>Interfaz</option>";
			for ($cont = 0; $cont < $interfaces; $cont++){
			
				$interfazSel = $ARRAY[$cont]['name'];
				echo "<option value=$interfazSel>$interfazSel</option>";
			
			}		
			echo "</select></form>";
		
			echo "Interfaz:";
		 
			echo $_POST['interfaces'];

			$interfaz =  $_POST['interfaces'];
			$_SESSION[ 'interfaz' ]=$interfaz;}
	
			else {
					header( 'Location:Login.php?notLogin=true' );}
?>
				<div class='graphics'>
				<div id="container" style="max-width: 80%; height: 200px; margin: 0 auto"></div>
				<input name="interface" id="interface" type="text" value="rb_inalambricos" />
				</div>
			</div>
			<div class="col-lg-2"></div>		
		</div>
	</div>

</div>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>			
			
			
<!-- Dibujamos las graficas-->


<script type="text/javascript" src="highchart/js/highcharts.js"></script>
<script type="text/javascript" src="highchart/js/themes/gray2.js"></script>

</body>
</html>
