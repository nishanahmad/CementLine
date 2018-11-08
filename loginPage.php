<html>
<head>
<body>
<br><br><br><br><br>
<title>User Login</title>
<link rel="stylesheet" type="text/css" href="css/loginPage.css" />
</head>

<form class="form-4" name="frmUser" method="post" action="user_login_session.php">
    <div align="center">
	<h1>USER LOGIN </h1>
	</div>
    <p>
        <label for="login">Username</label>
        <input type="text" name="user_name" placeholder="Username" required>
    </p>
    <p>
        <label for="password">Password</label>
        <input type="password" name='password' placeholder="Password" required> 
    </p>

    <p>
        <input type="submit" name="submit" value="submit">
    </p>       
</form>
</body>
</html>

