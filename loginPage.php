
<html>
<title>User Login</title>
<head>
<link rel="stylesheet" type="text/css" href="css/login.css">
<script src="js/jquery3.min.js"></script>
<script type="text/javascript">
$("#login-button").click(function(event){
	event.preventDefault();	 
	$('form').fadeOut(500);
	$('.wrapper').addClass('form-success');
});
</script>
</head>
<body>
	<div class="wrapper">
		<div class="container">
			<h1>Welcome</h1>
			
			<form class="form" name="frmUser" method="post" action="user_login_session.php">
				<input type="text" name="user_name" placeholder="Username" required>
				<input type="password" name='password' placeholder="Password" required> 			
				<button type="submit" id="login-button">Login</button>
			</form>
		</div>
		
		<ul class="bg-bubbles">
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
</body>
</html>

