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

if(isset($_POST['new_test']))
{
	$_SESSION['which_class']='new_test';
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

	<section class='report'>
	<div class='container'>
		<div class='row'>

				<div class='col-12'>
					<h3>Pliki użytkowników</h3>

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

							echo '<div class="table-responsive one_option"><form method="post"><table class="table">
							<tr><td><label for="user">Wybierz ucznia:</label></td><td>';
							$users=scandir('new_files');
							if(empty($users))
								echo 'Brak plików użytkowników.';
							else
							{
								echo '<select name="user" id="user"><option selected disabled hidden style="display: none" value=""> -- wybierz ucznia -- </option>';
								$_SESSION['users_files']=Array();
								for($i=2; $i<count($users); $i++)
								{
									echo '<option value="'.$users[$i].'">'.$users[$i].'</option>';
									$_SESSION['users_files'][$i]=$users[$i];
								}
								echo '</select>';
							}
							echo '</td></tr></table>
							<p><button name="search" class="editing">Wyszukaj <i class="icon-search"></i></button></p>
							</form></div>';

					?>

				</div>

					<?php

					if(isset($_POST['search']))
					{
						echo '<div class="col-12" id="users">';
						if(!isset($_POST['user']) || $_POST['user']=='')
							echo '<p>Nie wybrano użytkownika!</p>';
						else
						{
							$user_ok=false;
							foreach($_SESSION['users_files'] as $value)
							{
								if($value==$_POST['user'])
								{
									$user_ok=true;
									break;
								}
							}
							if($user_ok==false)
								echo '<p>Wybrany użytkownik jest niezgodny z rezultatami wyszukań!</p>';
							else
							{
								$files=glob('new_files/'.$_POST['user'].'/*.*');
								if(empty($files))
									echo '<p>Ten użytkownik nie przesłał jeszcze żadnych plików.</p>';
								else
								{
									echo '<h3>Pliki użytkownika "'.$_POST['user'].'":</h3>
									<div class="table-responsive"><table class="table table-bordered">
									<tr><th>Lp.</th><th>Plik</th></tr>';
									$i=1;
									foreach($files as $value)
									{
										echo '<tr><td>'.$i.'</td><td><a href="'.$value.'" download>'.substr($value, (strlen($_POST['user'])+11)).'</a></td></tr>';
										$i++;
									}
									echo '</table></div>';
								}
							}
						}
						echo '</div>';
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