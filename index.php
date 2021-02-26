<?php
session_start();

if(isset($_SESSION['admin_log_now']))
{
	header('Location: admin.php');
	exit();
}
if(isset($_SESSION['user_now']))
{
	header('Location: user.php');
	exit();
}

if(isset($_SESSION['type_class']))
	unset($_SESSION['type_class']);

?>

<!DOCTYPE html>
<html>
<head>
	<title>UPSI 2.1</title>

	<meta charset="utf-8">
	<meta name="description" content="Projekt stworzony i rozwijany jako hobby w celu wspólnego dzielenia się wiedzą i nauką. Pierwotne przeznaczenie to system egzaminujący studentów.">
	<meta name="keywords" content="UPSI, upsi, UPSI2.1, upsi2.1, UPSI2, upsi2, egzekutor">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type='text/css' href='fontello/css/fontello.css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/background_index.css">

</head>
<body>

<section class='index'>
	<div class='container'>
		<div class='row'>
		<div class='col-12'>
			<div class='info'>
				<header>
					UPSI Egzekutor 2.1
				</header>
			</div>
			<div class='logon'>

			<main>
				<section>
					<form action='login.php' method='post'>
					<label>Podaj login:<br> <input type='text' name='login' class='log' <?= isset($_SESSION['login']) ? 'value="' .$_SESSION['login']. '"' : '' ?>> </label><br>
					<label>Podaj hasło:<br> <input type='password' name='password' class='log'> </label><br>
					<button name='zaloguj' class='zaloguj'>Zaloguj <i class="icon-login"></i></button>
					</form>
				</section>
			</main>

			<?php
			if(isset($_SESSION['login_error']))
			{
				echo '<hr><span style="font-size: 16px;">'.$_SESSION['login_error'];
				unset($_SESSION['login_error']);
				unset($_SESSION['login']);
				echo '</span>';
			}

			if(isset($_SESSION['end_test']))
			{
				echo '<hr>'.$_SESSION['end_test'];
				session_destroy();
			}
			?>

			</div>
		</div>

		<footer>
			<div class="footer">
				Project inspired by created Bogdan Pietrzak UPSI
			</div>
		</footer>
		</div>
		
	</div>
	
</section>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->