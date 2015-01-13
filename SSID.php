<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<html>
<head>
	<title>Mikrotik Web Controller</title>
	<link href="/css/styleSSID.css" type="text/css" rel="stylesheet" />
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
	

<?php
		
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
          					<li class='tituloNoSel'>MONITOR</li>
						<li><a href='Overview.php'>Overview</a></li>
          					<li><a href='MapasyPlanos.php'>Mapas y planos</a></li>
						<li><a href='CAPsMAN.php'>CAPsMAN</a></li>
						<li><a href='AccessPoints.php'>Puntos de acceso</a></li>
						<li><a href='Clientes.php'>Clientes</a></li>
						<li><a href='LogEventos.php'>Log de eventos</a></li>
						<li><a href='MapaCobertura.php'>Mapa de cobertura</a></li>
						<li><a href='Modelo.php'>Modelo</a></li>
          					<li><a href='Resumen.php'>Resumen</a></li>

						<li class='tituloSel'>CONFIGURACIÓN</li>
          					<li id='seleccionado'><a href='SSID.php'>SSID</a></li>
						<li><a href='ControlAcceso.php'>Control de acceso</a></li>
						<li><a href='Firewall.php'>Firewall</a></li>
						<li><a href='Usuarios.php'>Usuarios</a></li>
						<li><a href='DisponibilidadSSID.php'>Disponibilidad SSID</a></li>
          					<li><a href='OpcionesRadio.php'>Opciones de radio</a></li>
						<li><a href='AnadirDispositivos.php'>A&ntildeadir dispositivos</a></li>
					</ul>
				</div>"	;
?>


		

</body>
</html>
