<?php ob_start(); session_start(); require('../routeros_api.class.php'); ?>

<?php	

	$API = new routeros_api();
	$IP = $_SESSION[ 'ip' ];
	$user = $_SESSION[ 'user' ];
	$password = $_SESSION[ 'password' ];
	if ($API->connect($IP, $user, $password)) {

			$ArrayCaps = $API->comm("/caps-man/actual-interface-configuration/print");
			$capsNum = count($ArrayCaps);
			$ArrayState = $API->comm("/caps-man/remote-cap/print");
			$ArrayAddresses = $API->comm("/ip/address/print");
			$ArrayDHCP = $API->comm("/ip/dhcp-server/print");
			$ArrayPool = $API->comm("/ip/pool/print");
			$ArrayRegistration = $API->comm("/caps-man/registration-table/print");

			$API->disconnect();

	
			
			for ($cont = 0; $cont < $capsNum; $cont++){

				
				$InterfazMaestra = $ArrayCaps[$cont]['master-interface'];
				$ModoConfiguracion = $ArrayCaps[$cont]['configuration.mode'];
				$FrecuenciaCanal = $ArrayCaps[$cont]['channel.frequency'];
				$BandaCanal = $ArrayCaps[$cont]['channel.band'];
				$PoderTransmision = $ArrayCaps[$cont]['channel.tx-power'];
				$AutentificacionSeguridad = $ArrayCaps[$cont]['security.authentication-types'];
				$EncriptacionSeguridad = $ArrayCaps[$cont]['security.encryption'];
				//OBTENER IP PUBLICA
				


				


				if (isset($_GET['interfaz-maestra']) && $cont==0){
					echo "<table id='interfaz-maestra2'><tr><th>Interfaz Maestra</th></tr><tr><td>$InterfazMaestra &nbsp</td>";}
				else if(isset($_GET['interfaz-maestra']) && $cont<$capsNum-1){
					echo "<tr><td>$InterfazMaestra&nbsp</td></tr>";}
				else if(isset($_GET['interfaz-maestra']) && $cont==$capsNum-1){
					echo "<tr><td>$InterfazMaestra&nbsp</td></tr></table>";}



				if (isset($_GET['modo-configuracion']) && $cont==0){
					echo "<table id='modo-configuracion2'><tr><th>Modo configuración</th></tr><tr><td>$ModoConfiguracion &nbsp</td>";}
				else if(isset($_GET['modo-configuracion']) && $cont<$capsNum-1){
					echo "<tr><td>$ModoConfiguracion&nbsp</td></tr>";}
				else if(isset($_GET['modo-configuracion']) && $cont==$capsNum-1){
					echo "<tr><td>$ModoConfiguracion&nbsp</td></tr></table>";}



				if (isset($_GET['pais']) && $cont==0){
					echo "<table id='pais2'><tr><th>País</th></tr><tr><td>$Pais &nbsp</td>";}
				else if(isset($_GET['pais']) && $cont<$capsNum-1){
					echo "<tr><td>$Pais&nbsp</td></tr>";}
				else if(isset($_GET['pais']) && $cont==$capsNum-1){
					echo "<tr><td>$Pais&nbsp</td></tr></table>";}




				if (isset($_GET['frecuencia-canal']) && $cont==0){
					echo "<table id='frecuencia-canal2'><tr><th>Frencuencia del canal</th></tr><tr><td>$FrecuenciaCanal &nbsp</td>";}
				else if(isset($_GET['frecuencia-canal']) && $cont<$capsNum-1){
					echo "<tr><td>$FrecuenciaCanal&nbsp</td></tr>";}
				else if(isset($_GET['frecuencia-canal']) && $cont==$capsNum-1){
					echo "<tr><td>$FrecuenciaCanal&nbsp</td></tr></table>";}



				if (isset($_GET['banda-canal']) && $cont==0){
					echo "<table id='banda-canal2'><tr><th>Banda del canal</th></tr><tr><td>$BandaCanal &nbsp</td>";}
				else if(isset($_GET['banda-canal']) && $cont<$capsNum-1){
					echo "<tr><td>$BandaCanal&nbsp</td></tr>";}
				else if(isset($_GET['banda-canal']) && $cont==$capsNum-1){
					echo "<tr><td>$BandaCanal&nbsp</td></tr></table>";}



				if (isset($_GET['poder-transmision']) && $cont==0){
					echo "<table id='poder-transmision2'><tr><th>Poder de transmisión</th></tr><tr><td>$PoderTransmision &nbsp</td>";}
				else if(isset($_GET['poder-transmision']) && $cont<$capsNum-1){
					echo "<tr><td>$PoderTransmision&nbsp</td></tr>";}
				else if(isset($_GET['poder-transmision']) && $cont==$capsNum-1){
					echo "<tr><td>$PoderTransmision&nbsp</td></tr></table>";}



				if (isset($_GET['autentificacion-seguridad']) && $cont==0){
					echo "<table id='autentificacion-seguridad2'><tr><th>Autentificacion de seguridad</th></tr><tr><td>$AutentificacionSeguridad &nbsp</td>";}
				else if(isset($_GET['autentificacion-seguridad']) && $cont<$capsNum-1){
					echo "<tr><td>$AutentificacionSeguridad&nbsp</td></tr>";}
				else if(isset($_GET['autentificacion-seguridad']) && $cont==$capsNum-1){
					echo "<tr><td>$AutentificacionSeguridad&nbsp</td></tr></table>";}



				if (isset($_GET['encriptacion-seguridad']) && $cont==0){
					echo "<table id='encriptacion-seguridad2'><tr><th>Encriptación de seguridad</th></tr><tr><td>$EncriptacionSeguridad &nbsp</td>";}
				else if(isset($_GET['encriptacion-seguridad']) && $cont<$capsNum-1){
					echo "<tr><td>$EncriptacionSeguridad&nbsp</td></tr>";}
				else if(isset($_GET['encriptacion-seguridad']) && $cont==$capsNum-1){
					echo "<tr><td>$EncriptacionSeguridad&nbsp</td></tr></table>";}
				
				
			}

		}


?>
