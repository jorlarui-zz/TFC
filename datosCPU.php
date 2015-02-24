<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<?php
		$API = new routeros_api();
		$IP = $_SESSION[ 'ip' ];
		$user = $_SESSION[ 'user' ];
		$password = $_SESSION[ 'password' ];
		//Comprobamos conexion API
		if ($API->connect($IP, $user, $password)) {

		
		//Modelo
		$modeloCom = $API->comm("/system/routerboard/print");
		$modelo=$modeloCom[0]['model'];

		//Modelo
		$cpuInfo = $API->comm("/system/resource/print");

		}	
				echo "Model: ";
				echo $modelo."</br>";
				echo "CPU <div class='progress' style='margin-bottom: 0px;'>
  					 <div class='progress-bar' role='progressbar' aria-valuenow='$cpuInfo[0]['cpu-load']' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width:".$cpuInfo[0]['cpu-load']."%'>".
    						$cpuInfo[0]['cpu-load']."%
 					 </div>
				
				</div>";
				echo "Uptime: ".$cpuInfo[0]['uptime'];
?>
