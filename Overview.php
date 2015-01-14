<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<html>
<head>
	<title>Mikrotik Web Controller</title>
	<link href="css/styleOverview.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	
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

			//Dibujamos los Menus
			echo "<div class='menuTop'>
					<ul class='menuTop2'>
          					<li><p class='redText'>Usuario:</p> $user</li>
          					<li><p class='redText'>IP:</p> $IP</li>
          					<li><a href='Login.php?logOut=true' id='logOut'>Log out</a></li>
					</ul>
				</div>
				<div class='menuLeft'>
					<ul class='menuLeft2'>
          					<li class='tituloSel'>MONITOR</li>
						<li id='seleccionado'><a href='Overview.php'>Overview</a></li>
          					<li><a href='MapasyPlanos.php'>Mapas y planos</a></li>
						<li><a href='CAPsMAN.php'>CAPsMAN</a></li>
						<li><a href='AccessPoints.php'>Puntos de acceso</a></li>
						<li><a href='Clientes.php'>Clientes</a></li>
						<li><a href='LogEventos.php'>Log de eventos</a></li>
						<li><a href='MapaCobertura.php'>Mapa de cobertura</a></li>
						<li><a href='Modelo.php'>Modelo</a></li>
          					<li><a href='Resumen.php'>Resumen</a></li>

						<li class='tituloNoSel'>CONFIGURACIÓN</li>
          					<li><a href='SSID.php'>SSID</a></li>
						<li><a href='ControlAcceso.php'>Control de acceso</a></li>
						<li><a href='Firewall.php'>Firewall</a></li>
						<li><a href='Usuarios.php'>Usuarios</a></li>
						<li><a href='DisponibilidadSSID.php'>Disponibilidad SSID</a></li>
          					<li><a href='OpcionesRadio.php'>Opciones de radio</a></li>
						<li><a href='AnadirDispositivos.php'>A&ntildeadir dispositivos</a></li>
					</ul>
				</div>"	;
?>

<?php
		//Comprobamos conexion API
		if ($API->connect($IP, $user, $password)) {

			//Comprobamos cuantas interfaces tiene CAPsMAN
			$ARRAY = $API->comm("/interface/print");
			$interfaces = count($ARRAY);
			$API->disconnect();

			//Creamos un formulario que actualiza la gráfica en función de la interfaz seleccionada
			echo "<form action=Overview.php method=post>";
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
<!-- Dibujamos las graficas-->
<div class='graphics'>
<script type="text/javascript" src="highchart/js/highcharts.js"></script>
<script type="text/javascript" src="highchart/js/themes/gray2.js"></script>

<div id="container" style="max-width: 80%; height: 300px; margin: 0 auto"></div>
<input name="interface" id="interface" type="text" value="rb_inalambricos" />
<div id="trafico"></div>
</div>
</body>
</html>
