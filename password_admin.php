<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['admin_log_now']=true;

if(isset($_SESSION['select_user']))
	unset($_SESSION['select_user']);

if(isset($_SESSION['which_class']))
{
	header('Location: edit_test.php');
	exit();
}

if(isset($_SESSION['class_to_show']))
	unset($_SESSION['class_to_show']);

if(isset($_SESSION['type_class']))
	unset($_SESSION['type_class']);

unset($_SESSION['subject_type']);
unset($_SESSION['class_type']);
unset($_SESSION['student_class']);
unset($_SESSION['yearbook']);
unset($_SESSION['exam_from_question']);
?>

<!DOCTYPE html>
<html>
<head>
	<title>UPSI 2.1 <?php if(isset($_SESSION['which_admin'])) {echo $_SESSION['which_admin'];} ?></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type='text/css' href='fontello/css/fontello.css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/background_admin.css">

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="js/scroll_to_id_users.js"></script>

</head>
<body>

	<?php
	require_once 'menu_admin.php';
	echo $menu_admin;
	?>

	<section class='add_class'>
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
						throw new Exception($connect->connect_errno);
					else
					{
						$connect->query("SET CHARSET utf8");
						$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

						echo '
						<h2>Zmiana hasła</h2>';
						if($actual_password=$connect->query("SELECT haslo FROM users WHERE id='".$_SESSION['which_admin_id']."' "))
						{
							echo '<form method="post" action="password_admin_actions.php"><div class="table-responsive one_option"><table class="table">';
							function pass()
							{
								echo '<tr><td><label for="new_password">Nowe hasło:</label></td><td><input type="password" name="new_password" id="new_password"></tr>
								<tr><td><label for="repeat_password">Powtórz hasło:</label></td><td><input type="password" name="repeat_password" id="repeat_password"></tr>
								</table></div>';
							}

							if($actual_password->num_rows>0)
							{
								if($actual_password_results=$actual_password->fetch_row())
								{
									if($actual_password_results[0]!='')
									{
										echo '<tr><td><label for="actual_password">Obecne hasło:</label></td><td><input type="password" name="actual_password" id="actual_password"></tr>';
										pass();
										echo '<p><button name="change" class="editing">Zmień <i class="icon-ok"></i></button></p>';
									}
									else
									{
										pass();
										echo '<p><button name="change" class="editing">Ustaw <i class="icon-ok"></i></button></p>';
									}
								}
							}
							else
								throw new Exception($connect->error);
							echo '</form>';
						}
						else
							throw new Exception($connect->error);
						
				?>				
				
			</div>

			<?php
						if(isset($_SESSION['connect_error']))
						{
							echo '<div class="col-12" id="users">';
							if($_SESSION['connect_error']!='error')
								echo $_SESSION['connect_error'];
							unset($_SESSION['connect_error']);
							throw new Exception($connect->error);
							echo '</div>';
						}

						if(isset($_SESSION['error']))
						{
							echo '<div class="col-12" id="users">'.$_SESSION['error'].'</div>';
							unset($_SESSION['error']);
						}

						$connect->close();

						require_once 'js/scroll_top.php';

					}
				}
				catch(Exception $e)
				{
					echo '<p>Przepraszamy, serwer niedostępny.</p>';
				}
			?>

		</div>

		<?php
		if(isset($_POST['new_test']))
		{
			$_SESSION['which_class']='new_test';
			header('Location: edit_test.php');
			exit();
		}
		?>

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