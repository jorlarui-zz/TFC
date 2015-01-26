<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
			var auto_refresh = setInterval(function (){
			$('#refreshImage').load('datosSwitchImage.php').fadeIn("slow");
			$('#refreshPorts').load('datosSwitch.php').fadeIn("slow");
			}, 2000);
		});		
			

 
	
  </script>
<?php



function random_color(){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}



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
	<?php
		echo "<link rel='stylesheet' href='css/styleSwitch$modelo.css'/>";
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
               <li ><a href="Switch.php">Switch</a></li>
		<li class="active"><a href="Vlans.php">Vlans</a></li>
                <li><a href="Ports.php">Ports</a></li>
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
			</div>
			</div>
			<div class="col-lg-2">INFO HERE</div>
			<div class="col-lg-2"></div>		
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 info-box">
			<div class="col-lg-2"></div>
			<div class="col-lg-4">
			<div id="refreshPorts">
				<table>
				<?php
				$contSwitch=-1;
				for ($cont = 0; $cont < $numPorts; $cont++){
						
						if($Ports[$cont]['master-port']=='none'){
							$contSwitch=$contSwitch+1;
							echo "<tr>";
							echo "<th style='border-bottom: 3px solid ".$colores[$contSwitch].";'>Switch ".$contSwitch."</th></tr>";
							echo "<tr>";
							echo "<td id='master-port'>".$Ports[$cont]['name']." (Master-port)</td>";
							for($cont2 = 0; $cont2 < $numPorts; $cont2++){
								if($Ports[$cont]['name']==$Ports[$cont2]['master-port']){
									echo "<tr><td>".$Ports[$cont2]['name']."</td></tr>";
								}

							}
						

						}
					
				}
						
				?>
				</table>
			</div>
			</div>
			<div class="col-lg-6">
				<table>
				<td>
				<table>
				<?php
					for ($cont = 0; $cont < $numPorts; $cont++){
						
						echo "<tr>";
						echo "<td>".$Ports[$cont]['name']."</td>";
						echo "<td>";
						echo "<form action=Switch.php method=post>";
						echo "<select name='formMaster$cont' onchange='this.form.submit()'>
  							<option value=''>Master Port</option>";
								echo "<option value='none'>none</option>";
							for ($cont2 = 0; $cont2 < $numPorts; $cont2++){
								echo "<option value='".$Ports[$cont2]['name']."'>".$Ports[$cont2]['name']."</option>";  								
								
							}

						echo "</select></form>";	
						echo "</td>";
									
						echo "</tr>";
					}
				?>
					</table>
				</td>
				<td>
				
			
			</div>		
		</div>
	</div>

</div>

<?php
		for ($cont = 0; $cont < $numPorts; $cont++){
			if(isset($_POST['formMaster'.$cont])){
			$seleccion= $_POST['formMaster'.$cont];

			$API = new routeros_api();
			$IP = $_SESSION[ 'ip' ];
			$user = $_SESSION[ 'user' ];
			$password = $_SESSION[ 'password' ];
			if ($API->connect($IP, $user, $password)) {
				$API->write("/interface/ethernet/set",false);
				$API->write("=master-port=".$seleccion,false);
				$API->write("=.id=".$Ports[$cont]['name']);
				$ARRAY = $API->read();
				$API->disconnect();
			}
			}

}
?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>			
			

</body>
</html>
