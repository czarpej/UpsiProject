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
						<h2>Struktura konta</h2>
						<button type="button" class="editing hidding" data-toggle="collapse" data-target="#pass">Zrzeknij się administratora <i class="icon-crown-minus"></i></button><br>
						<div class="collapse" id="pass">
						<form method="post" action="structure_admin_actions.php">
						<fieldset><legend>Zrzeczenie się administratora</legend>
						<p style="text-align: left;">Możesz zrzec się praw administratora przenosząc się do innej klasy. Twoje obecne hasło zostanie zachowane.<br>
						Aby ponownie być administratorem musisz zostać awansowany przez głównego administratora.</p>
						<div class="table-responsive"><table class="table">
						<tr><td><label for="class">Wybierz klasę, do której chcesz się przenieść:</label></td><td>';
						if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy' "))
						{
							if($class->num_rows>0)
							{
								echo '<select id="class" name="class"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								$_SESSION['move_class']=Array();
								$i=0;
								while($class_results=$class->fetch_assoc())
								{
									echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
									$_SESSION['move_class'][$i]=$class_results['id'];
									$i++;
								}
								echo '</select>';
							}
							else
								echo 'Obecnie brak klasy. <a href="add_class.php">Dodaj nową!</a>';
						}
						else
							throw new Exception($connect->error);
						echo '</td></tr>
						</table></div>
						<p><button class="editing" name="pass">Przenieś się <i class="icon-crown-minus"></i></button></p>
						</fieldset>
						</form>
						</div>
						<button type="button" class="editing hidding" data-toggle="collapse" data-target="#block">Zablokuj konto <i class="icon-block"></i></button>
						<div class="collapse" id="block">
						<fieldset><legend>Blokowanie konta</legend>
						<p style="text-align: left;">Możesz zablokować/zamrozić swoje konto, jednak tylko administrator główny może je odblokować.</p>
						<form method="post" action="structure_admin_actions.php"><p><button name="block" class="editing">Zablokuj konto <i class="icon-block"></i></button></p></form>
						</fieldset>
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