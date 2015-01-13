<?php ob_start(); session_start(); require('routeros_api.class.php'); ?>
<html>
<head>
	<title>Login</title>
	<link href="/css/login.css" type="text/css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
</head>
<body>
	<img id="logo" src='images/logo.png' />
	<form name="login" method="post" action="Login.php">
	<table cellspacing="0" cellpadding="0" id="loginTable">
	<tr id="loginBorder">
			<th colspan="2">LOGIN</td>
	</tr>
	<tr>
		<td>
		<label for='ip' >IP:</label>
		</td>
		<td>
		<input name="ip" type="text" id="ip" placeholder="192.168.88.1"></br>
		</td>
	</tr>
	<tr>
		<td>
		<label for='user' >User:</label>
		</td>
		<td>
		<input name="user" type="text" id="user" placeholder="admin"></br>
		</td>
	</tr>
	<tr>
		<td>
		<label for='password' >Password:</label>
		</td>
		<td>
		<input name="password" type="password" id="password" placeholder="******"></br>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<div id="submitButton">
		<input type="submit" name="send" value="SUBMIT" class="button" /></br>	</div>
		</td>
	</tr>
	</table>
	</form>
	

<?php
	$API = new routeros_api();
	$IP = $_POST['ip'];
	$user = $_POST['user'];
	$password = $_POST['password'];
	$notLogin = $_GET["logOut"];

	$_SESSION[ 'ip' ]=$IP;
	$_SESSION[ 'user' ]=$user;
	$_SESSION[ 'password' ]=$password;
	
	$buttonClick = isset($_POST['send']);
	
	if( $buttonClick){
		if ($API->connect($IP, $user, $password)) {

   			header( 'Location:Overview.php') ;}

	
		else {
		echo "<div class='error'>Error: Login incorrecto.</div>";}}
       if ($notLogin){
	session_unset();	
}

?>
</body>
</html>
