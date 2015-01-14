<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<html>
<head>
	<title>Mikrotik Web Controller</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
		

	
<!--Script para dibujar las graficas-->


</head>
<body>	
    <!--<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <p class="navbar-text"><img src="images/logolittle.png" width="75" height="20" style="margin-left:70px;"></p>
        </div>
       
        <div id="navbar" class="collapse navbar-collapse">

	    <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Log Out&nbsp&nbsp&nbsp</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right"  style="margin-right:70px;">
                <li class="active"><a href="#">Status</a></li>
                <li><a href="#">Ports</a></li>
                <li><a href="#">Vlan</a></li>
		<li><a href="#">ACL</a></li>
            </ul>
        </div> 
    	</div>
    </nav>-->
<style media="screen" type="text/css">

.top-bar a{
	color:#333;
	background-color:#DDD;
	
	border-radius:6px;}

.top-bar li{
}

</style>
 <div class="container top-bar">

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
      </div>


<div class="container">

      	<div class="row">
		<div class="col-lg-12" style="background-color:#DDD; margin-top:10px;">
				<h1>PROBANDO</H1>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12" style="background-color:#DDD;margin-top:10px;">
				<h1>PROBANDO</H1>
		</div>
	</div>

</div>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>			
			
			
<!-- Dibujamos las graficas-->
<!--<div class='graphics'>

<script type="text/javascript" src="highchart/js/highcharts.js"></script>
<script type="text/javascript" src="highchart/js/themes/gray2.js"></script>

<div id="container" style="max-width: 80%; height: 300px; margin: 0 auto"></div>
<input name="interface" id="interface" type="text" value="rb_inalambricos" />
<div id="trafico"></div>
</div>-->
</body>
</html>
