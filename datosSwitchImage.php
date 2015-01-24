<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>

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


		$API->write("/interface/ethernet/monitor",false);
		$API->write("=numbers=".$valores,false);  
		$API->write("=once=",true);
		$READ = $API->read(false);
		$statusPorts = $API->parse_response($READ);
	
		$colores=["#00fff9","#ff00e7","#a3ff00","#ffdc0b","#ff4400","#7c00ff","#3377ff","#ff7468","#20e523","#fcc512"
			 ,"#9f0d0d","#bc9ad4","#79addd","#e7d1e5","#7bcf5a","#cc8324","#b80f12","#0da9b0","#eea7b9","#1e7352"
			,"#eee117","#b80000","#00137a","#AA0078","#3333FF","#99FF00","#FFCC00","#CC0000","#587498","#E86850"
			,"#FFD800","##00FF00","#FF0000","#0000FF","#FF6600","#A16B23","#C9341C","#ECC5A8","#A3CBF1","#79BFA1"
			,"#FB7374","#FF9900","#4FD5D6","#D6E3B5","#FFD197","#FFFF66","#FFC3CE","#21B6A8","#CDFFFF",""];
		$API->disconnect();}	
				$contSwitchImg=-1;
				for ($cont = 0; $cont < $numPorts; $cont++){
					if($Ports[$cont]['master-port']=='none'){

						$contSwitchImg=$contSwitchImg+1;
				
						if($statusPorts[$cont]['status']=='link-ok'){
						echo "<svg version='1.1' id='etherGreen$cont' style='fill:$colores[$contSwitchImg]' xmlns='http://www.w3.org/2000/svg' 
						xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'
	 					width='5.2%' height='5.2%' viewBox='0 0 15 11' style='enable-background:new 0 0 15 11;' xml:space='preserve''>
						<polygon class='st0' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
						</svg>";
						
							
						}
					}	

					else{
						if($statusPorts[$cont]['status']=='link-ok'){
						echo "<svg version='1.1' id='etherGreen$cont' style='fill:$colores[$contSwitchImg]' xmlns='http://www.w3.org/2000/svg' 
									xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'
	 								width='5.2%' height='5.2%' viewBox='0 0 15 11' style='enable-background:new 0 0 15 11;' xml:space='preserve''>
									<polygon class='st0' points='10.7,2.7 10.7,0.5 4.5,0.5 4.5,2.7 0.3,2.7 0.3,11 15,11 15,2.7 '/>
									</svg>";
						
							
						}	
					
					
					}
				
				}
				echo "<img src='images/$modelo.png'>";				
?>
