<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['admin_log_now']=true;

if(isset($_SESSION['type_class']))
	unset($_SESSION['type_class']);

if(isset($_SESSION['select_user']))
	unset($_SESSION['select_user']);

if(isset($_SESSION['which_class']))
{
	header('Location: edit_test.php');
	exit();
}

if(isset($_SESSION['class_to_show']))
	unset($_SESSION['class_to_show']);

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

<section class='show'>
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
						<h3>Dodawanie administratorów</h3>
						<input type="button" class="editing hidding" data-toggle="collapse" data-target="#add_one" value="Pojedyncze dodawanie"><br>
						<div class="collapse" id="add_one">
						<form method="post" action="add_admin_actions.php"><fieldset><legend>Pojedyncze dodawanie</legend>
						<div class="table-responsive">
						<table class="table">
						<tr><td><label for="login">Login:</label></td><td><input type="text" id="login" name="login"></td></tr>
						</table>
						</div>
						<p><button class="editing" name="add">Dodaj <i class="icon-crown-plus"></i></button></p>
						</fieldset></form>
						</div>
						<input type="button" class="editing hidding" data-toggle="collapse" data-target="#add_many" value="Seryjne dodawanie"><br>
						<div class="collapse" id="add_many">
						<form method="post"><fieldset><legend>Seryjne dodawanie</legend>
						<div class="table-responsive">
						<table class="table">
						<tr><td><label for="many">Ilość seryjna</label></td><td><input type="number" name="many" id="many"></td></tr>
						</table>
						</div>
						<p><button name="set" class="editing">Ustaw <i class="icon-ok"></i></button></p>
						</fieldset></form>
						</div>';

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

						if(isset($_POST['set']))
						{
							echo '<div class="col-12" id="users">';
							if(!isset($_POST['many']) || $_POST['many']=='')
								echo '<p>Nie wprowadzono wartości w wymaganym polu!</p>';
							else if(!is_numeric($_POST['many']) || filter_var($_POST['many'], FILTER_VALIDATE_INT) === false)
								echo '<p>Wprowadzona wartość nie jest liczbą całkowitą!</p>';
							else
							{
								echo '<form method="post" action="add_admin_actions.php">
								<div class="table-responsive">
								<table class="table table-bordered">
								<tr><th>Lp.</th><th>Login</th></tr>';
								for($_SESSION['add_serially']=0; $_SESSION['add_serially']<$_POST['many']; $_SESSION['add_serially']++)
								{
									echo '<tr><td><label for="login'.$_SESSION['add_serially'].'">'.($_SESSION['add_serially']+1).'</td><td><input type="text" name="login'.$_SESSION['add_serially'].'" id="login'.$_SESSION['add_serially'].'"></td></tr>';
								}
								echo '</table>
								</div>
								<p><button name="add_serially" class="editing">Dodaj seryjnie <i class="icon-crown-plus"></i></button></p>
								</form>';
							}
							echo '</div>';
						}

						$connect->close();

						require_once 'js/scroll_top.php';

					}
				}
				catch(Exception $e)
				{
					echo 'Przepraszamy, serwer niedostępny.';
				}
			?>

		</div>
	</div>
</section>

		<?php
		if(isset($_POST['new_test']))
		{
			$_SESSION['which_class']='new_test';
			header('Location: edit_test.php');
			exit();
		}
		?>

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