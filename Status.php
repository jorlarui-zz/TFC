<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<html>
<head>
	<title>Mikrotik Web Controller</title>
	<link href="css/styleOverview.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>

	
<!--Script para dibujar las graficas-->


</head>
<body>
<script src="bootstrap/js/bootstrap.min.js"></script>

<div class='navbar navbar-default navbar-static-top'>
						<div class='container'>
							<button class='navbar-toggle' data-toggle='collapse' data-target='.navHeaderCollapse'>
							 &#9776;
							</button>
							<div class='collapse navbar-collapse navHeaderCollapse'>
							<ul class='nav navbar-nav navbar-right>
								<li><a href='#'>Status</a></li>
							</ul>
							</div>
						</div>
				</div>
			
			
			
			<
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
