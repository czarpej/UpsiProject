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

</head>
<body>

	<?php
	require_once 'menu_admin.php';
	echo $menu_admin;
	?>

	<section class='delete'>
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
						<h2>Usuwanie użytkowników</h2>
						<p><div class="table-responsive one_option"><table class="table">
						<tr><td><label for="class">Klasa</label></td>
						<td><form method="post" id="class_form">';
						if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy' ORDER BY section ASC"))
						{
							if($class->num_rows>0)
							{
								$_SESSION['from_class']=Array();
								$i=0;
								echo '<select name="class" id="class"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								while($class_results=$class->fetch_assoc())
								{
									echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
									$_SESSION['from_class'][$i]=$class_results['id'];
									$i++;
								}
								echo '</select>';
							}
							else
								echo 'Nie istnieją żadne klasy w systemie';
						}
						else
							throw new Exception($connect->error);
						echo '</form>';

						echo '
								<script>
								$("#class").bind("change", function () {
								    $("#class_form").submit();
								});
								</script>
								';

						echo '</td></tr><tr><td><label for="user_type">Użytkownik</label></td><form method="post" action="delete_actions.php"><td id="first_class">';

						if(isset($_POST["class"]))
						{
							$class_ok=false;
							foreach ($_SESSION['from_class'] as $value) 
							{
								if($value==$_POST['class'])
								{
									$class_ok=true;
									break;
								}
							}
							unset($_SESSION['from_class']);
							if($class_ok==false)
							{
								$_SESSION['error']='<p>Wybrana klasa nie jest rezultatem wyszukania przez system!</p>';
								header('Location: delete.php');
								exit();
							}
							else
							{
								$_SESSION['type_class']=$_POST["class"];
								if($users=$connect->query("SELECT `id`, `login` FROM `users` WHERE `id_klasy`='".$_SESSION['type_class']."' ORDER BY `login` ASC"))
								{
									if($users->num_rows>0)
									{
										$_SESSION['users_with_class']=Array();
										$i=0;
										echo '<select name="user_type" id="user_type"><option selected disabled hidden style="display: none" value=""> -- wybierz użytkownika -- </option>';
										while($users_results=$users->fetch_row())
										{
											echo '<option value="'.$users_results[0].'">'.$users_results[1].'</option>';
											$_SESSION['users_with_class'][$i]=$users_results[0];
											$i++;
										}
										echo '</select></td></tr><tr id="action_user"><td><input type=radio name=action_user value=archive checked> Przenieś do archiwum</td><td><input type=radio name=action_user value=delete> Usuń';
									}
									else
										echo 'Brak uczniów w tej klasie';
								}
								else
									throw new Exception($connect->error);
							}
						}
						else if(isset($_SESSION['type_class']))
						{
							if($users=$connect->query("SELECT `id`, `login` FROM `users` WHERE `id_klasy`='".$_SESSION['type_class']."' ORDER BY `login` ASC"))
							{
								if($users->num_rows>0)
								{
									echo '<select name="user_type" id="user_type"><option selected disabled hidden style="display: none" value=""> -- wybierz użytkownika -- </option>';
									while($users_results=$users->fetch_row())
										echo '<option value="'.$users_results[0].'">'.$users_results[1].'</option>';
									echo '</select></td></tr><tr id="action_user"><td><input type="radio" name="action_user" value="archive" id="archive_user" checked> <label for="archive_user">Przenieś do archiwum</label></td><td><input type="radio" name="action_user" value="delete" id="delete_user"> <label for="delete_user">Usuń</label>';
								}
								else
									echo 'Brak uczniów w tej klasie';
							}
							else
								throw new Exception($connect->error);
						}
						else
							echo 'Najpierw wybierz klasę.';

						echo '</td></tr></table></div>
						<p><input type="submit" class="editing" value="Usuń" name="manual_delete"></p>
						</p>
						</form>
						';
						
				?>				
				
			</div>

			<?php
						if(isset($_SESSION['connect_error']))
						{
							echo '<div class="col-12">';
							if($_SESSION['connect_error']!='error')
								echo $_SESSION['connect_error'];
							unset($_SESSION['connect_error']);
							throw new Exception($connect->error);	
							echo '</div>';					
						}

						if(isset($_SESSION['error']))
						{
							echo '<div class="col-12">'.$_SESSION['error'].'</div>';
							unset($_SESSION['error']);
						}

						if(isset($_SESSION['deleting_ok']))
						{
							echo '<div class="col-12">';
							echo $_SESSION['deleting_ok'];
							unset($_SESSION['deleting_ok']);
							echo '</div>';
						}

						if(isset($_SESSION['type_class']))
						{
							echo '
							<script>
							$("#class").val("'.$_SESSION['type_class'].'");
							$("#uzytkownik").css("display", "block");
							</script>';
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