<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

if(isset($_SESSION['test_now']))
{
	header('Location: test.php');
	exit();
}


$_SESSION['user_now']=true;
?>

<!DOCTYPE html>
<html>
<head>
	<title>UPSI 2.1 <?php if(isset($_SESSION['which_user'])) {echo $_SESSION['which_user'];} ?> </title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type='text/css' href='fontello/css/fontello.css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/background_user.css">
	
	<script src="js/jquery-3.3.1.min.js"></script>

</head>
<body>

	<nav class="navbar navbar-dark navbar-expand">
		<ol class="navbar-nav">
			<li class="nav-item"><a class="nav-link"><form method="post"><button name="canel"><i class="icon-cancel-circled"></i> Anuluj</button></form></a></li>
			<li class="nav-item"><a class="nav-link"><form action='logout.php' method='post'><button name='logout'><i class="icon-logout"></i> Wyloguj</button></form></a></li>
		</ol>
	</nav>

	<section class="edit_user">
	<div class='container'>

		<div class='row'>
			
			<div class='col-12'>

				<?php

				require_once 'dbconnect.php';
				mysqli_report(MYSQLI_REPORT_STRICT);
				try
				{
					$connect=new mysqli($address, $db_login, $db_password, $db_name);
					if($connect->connect_errno!=0)
						throw new Exception($connect->connect_errno());
					else
					{
						$connect->query("SET CHARSET utf8");
						$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

						$password_from_db=$connect->query("SELECT haslo FROM users WHERE id='".$_SESSION['which_user_id']."' ");
						$what_password=$password_from_db->fetch_row();

						echo '
						<h3>Zmiana hasła</h3>
						<form method="post">
						<div class="table-responsive"><table class="table">';
						if($what_password[0]!='')
						{
						echo '<tr><td><label for="actual_password">Obecne hasło:</label></td><td><input type="password" name="actual_password" id="actual_password"';
						if(isset($_SESSION['actual_password']))
							echo 'value="'.$_SESSION['actual_password'].'"';
						echo '></td></tr>';
						}
						echo '<tr><td><label for="new_password">Nowe hasło:</label></td><td><input type="password" name="new_password" id="new_password"';
						if(isset($_SESSION['new_password']))
							echo 'value="'.$_SESSION['new_password'].'"';
						echo '></td></tr>
						<tr><td><label for="repeat_password">Powtórz hasło:</label></td><td><input type="password" name="repeat_password" id="repeat_password"';
						if(isset($_SESSION['repeat_password']))
							echo 'value="'.$_SESSION['repeat_password'].'"';
						echo '></td></tr>
						</table></div>
						<p><input type="submit" name="change" value="Zmień" class="editing"></p>
						</form>
						';

						if(isset($_SESSION['info']))
						{
							echo '<hr>';
							echo $_SESSION['info'];
							unset($_SESSION['info']);
						}

						if(isset($_POST['change']))
						{
							echo '<hr>';
							if(isset($_POST['actual_password']))
								$_SESSION['actual_password']=$_POST['actual_password'];
							$_SESSION['new_password']=$_POST['new_password'];
							$_SESSION['repeat_password']=$_POST['repeat_password'];

							if(!$password_from_db)
								throw new Exception($connect->error);	
							else if($_SESSION['new_password']=='' || $_SESSION['repeat_password']=='')
							{
								$_SESSION['info']='<p>Nie wprowadzono wszystkich danych!</p>';
								header('Location: edit_user.php');
								exit();
							}
							else if(!password_verify($_SESSION['actual_password'], $what_password[0]))
							{
								$_SESSION['info']='<p>Obecne hasło jest nieprawdziwe!</p>';
								header('Location: edit_user.php');
								exit();
							}
							else if($_SESSION['new_password']!==$_SESSION['repeat_password'])
							{
								$_SESSION['info']='<p>Podane hasła są niezgodne!</p>';
								header('Location: edit_user.php');
								exit();
							}
							else
							{
								if($connect->query("UPDATE users SET haslo='".password_hash($_SESSION['repeat_password'], PASSWORD_DEFAULT)."' WHERE id='".$_SESSION['which_user_id']."' "))
								{
									unset($_SESSION['actual_password']);
									unset($_SESSION['new_password']);
									unset($_SESSION['repeat_password']);
									$_SESSION['info']='<p>Pomyślnie zmieniono hasło.</p>';
									header('Location: edit_user.php');
									exit();
								}
								else
									throw new Exception($connect->error);
							}
						}

						$connect->close();

						require_once 'js/scroll_top.php';

					}
				}
				catch(Exception $e)
				{
					echo '<p>Przepraszamy, serwer niedostępny.</p>';
				}

				if(isset($_POST['canel']))
				{
					unset($_SESSION['actual_password']);
					unset($_SESSION['new_password']);
					unset($_SESSION['repeat_password']);
					header('Location: user.php');
					exit();		
				}

				?>

			</div>

		</div>

	</div>
	</section>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/width_fieldset.js"></script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->