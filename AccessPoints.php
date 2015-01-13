<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<html>
<head>
	<title>Mikrotik Web Controller</title>
	<link href="css/styleAccessPoints.css" type="text/css" rel="stylesheet" />
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
	
<!-- script para comprobar los selectboxes y actualizar parcialmente el contenido de la web en funcion de estos-->

	<script>
		$(document).ready(function(){

			if($('#nombre').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsnombre.php?nombre', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});
			}

			if($('#estado').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsestado.php?estado', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});
			}
			
			if($('#direccion-mac').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsdireccion-mac.php?direccion-mac', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});
			}
			
			if($('#clientes').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsclientes.php?clientes', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});

			if($('#ip-lan').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsip-lan.php?ip-lan', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});
			}

			/*if($('#ip-publica').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsip-publica.php?ip-publica', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});
			}*/

			if($('#SSID').is(':checked')){
				$.get('datosAccessPoints/datosAccessPointsSSID.php?SSID', function(data){
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
				});
			}
			}
			$(':checkbox').change(function(){
				
				if($('table[id='+$(this).attr('id')+'2]').length>=1){
					$('table[id='+$(this).attr('id')+'2]').remove();
					
				}else{
					
					//Coge de datosAccessPoints.php el valor del contenido del id que le pasamos.
					$.get("datosAccessPoints/datosAccessPoints"+$(this).attr('id')+".php?"+$(this).attr('id'), function(data){
						
						var old = $('#rowDatos2').html();
						$('#rowDatos2').html(old+data);
						
						
					});
				}
			});	

				

			

		
		});	
	</script>

<?php
	
	$API = new routeros_api();
	
	

		$IP = $_SESSION[ 'ip' ];
		$user = $_SESSION[ 'user' ];
		$password = $_SESSION[ 'password' ];
	if ($API->connect($IP, $user, $password)) {
	
		$ArrayCaps = $API->comm("/caps-man/actual-interface-configuration/print");
		$capsNum = count($ArrayCaps);
		$ArrayRegistration = $API->comm("/caps-man/registration-table/print");
		$ArrayState = $API->comm("/caps-man/remote-cap/print");
		$ArrayAddresses = $API->comm("/ip/address/print");
		$ArrayDHCP = $API->comm("/ip/dhcp-server/print");
		$ArrayPool = $API->comm("/ip/pool/print");
		$ArrayBridge = $API->comm("/interface/bridge/host/print");


		$_SESSION[ 'ArrayState' ] = $ArrayState;
		$_SESSION[ 'ArrayRegistration' ]=$ArrayRegistration;
		$_SESSION[ 'ArrayCaps' ]=$ArrayCaps;
		$_SESSION[ 'ArrayAddresses' ] = $ArrayAddresses;
		$_SESSION[ 'ArrayDHCP' ]=$ArrayDHCP;
		$_SESSION[ 'ArrayPool' ]=$ArrayPool;
		$_SESSION[ 'capsNum' ]=$capsNum;
		$_SESSION[ 'ArrayBridge' ]=$ArrayBridge;
			
			$API->disconnect();
		}
		else {
		header( 'Location:Login.php?notLogin=true' );}

?>
</head>
<body>


<?php
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
						<li><a href='Overview.php'>Overview</a></li>
          					<li><a href='MapasyPlanos.php'>Mapas y planos</a></li>
						<li><a href='CAPsMAN.php'>CAPsMAN</a></li>
						<li id='seleccionado'><a href='AccessPoints.php'>Puntos de acceso</a></li>
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


		<!--Conectamos al CAPsMAN-->
		

			
			
<p id='tituloMenu'>Selecciona opciones visualizaci&oacuten:   <p></br>
				<div id='basico'>
					Nombre: <input type='checkbox' name='nombre' value='nombre' id='nombre' checked='checked' />
					Estado: <input type='checkbox' name='estado' value='estado' id='estado' checked='checked' />
					Modelo: <input type='checkbox' name='modelo' value='modelo' id='modelo'/>
				</div>
				<div id='configuracion'>
					Direcci&oacuten Mac: <input type='checkbox' name='direccion-mac' value='direccion-mac' id='direccion-mac' checked='checked'/>
					<!--IP p&uacuteblica: <input type='checkbox' name='ip-publica' value='ip-publica'id='ip-publica' checked='checked'/>-->
					IP LAN: <input type='checkbox' name='ip-lan' value='ip-lan' id='ip-lan' checked='checked'/>
					SSID: <input type='checkbox' name='SSID' value='SSID' id='SSID' checked='checked'/>
					Clientes: <input type='checkbox' name='clientes' value='clientes'id='clientes' checked='checked'/>
					Pa&iacutes: <input type='checkbox' name='pais' value='pais'id='pais'/>
					Frecuencia del canal: <input type='checkbox' name='frecuencia-canal' value='frecuencia-canal' id='frecuencia-canal'/>
				</div>
				<div id='otros'>
					Interfaz Maestra: <input type='checkbox' name='interfaz-maestra' value='interfaz-maestra' id='interfaz-maestra'/>
					Modo de configuraci&oacuten: <input type='checkbox' name='modo-configuracion' value='modo-configuracion' id='modo-configuracion'/>
				
					Banda del canal: <input type='checkbox' name='banda-canal' value='banda-canal' id='banda-canal'/>
					Poder de transmisi&oacuten: <input type='checkbox' name='poder-transmision' value='poder-transmision' id='poder-transmision'/>
					Tipo de autentificaci&oacuten de seguridad: <input type='checkbox' name='autentificacion-seguridad' value='autentificacion-seguridad' id='autentificacion-seguridad'/>
					Encriptaci&oacuten de seguridad: <input type='checkbox' name='encritacion-seguridad' value='encriptacion-seguridad' id='encriptacion-seguridad'/>
				</div>
				</br>
			
			
			
			<div id='rowDatos2'>
				
			
			</div>

	


</body>
</html>
