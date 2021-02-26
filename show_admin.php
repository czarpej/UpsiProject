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
						<h3>Administratorzy systemu</h3>';
						if($admins=$connect->query("SELECT `classes`.`section`, `classes`.`class`, `users`.`login`, `users`.`id`, `users`.`id_klasy`, `users`.`freezing` FROM classes, users WHERE `classes`.`section`='Administratorzy' AND `classes`.`id`=`users`.`id_klasy` "))
						{
							if($admins->num_rows>0)
							{
								$_SESSION['how_admins']=0;
								echo '<form method="post" action="show_admin_actions.php"><div class="table-responsive"><table class="table table-bordered"><tr><th>Administrator</th><th>Zarządzanie</th><th>Usuwanie</th></tr>';
								while($admins_results=$admins->fetch_assoc())
								{
									echo '<tr><td>'.$admins_results['login'].'</td><td><input type="button" class="editing hidding" data-toggle="collapse" data-target="#edit_admin'.$_SESSION['how_admins'].'" value="Zmień dane"><form method="post" action="show_admin_actions.php"><input type="hidden" name="id_admin" value="'.$admins_results['id'].'">';
									if($admins_results['freezing']==0)
										echo '<button name="block" class="editing editing_button">Zablokuj <i class="icon-block"></i></button>';
									else
										echo '<button name="unblock" class="editing editing_button">Odblokuj <i class="icon-lock-open"></i></button>';
									echo '</form>
									<form method="post" action="show_admin_actions.php"><input type="hidden" name="id_admin" value="'.$admins_results['id'].'"><button name="reset" class="editing editing_button">Resetuj hasło <i class="icon-arrows-cw"></i></button></form>
									</td><td><input type="checkbox" name="delete'.$_SESSION['how_admins'].'" value="'.$admins_results['id'].'"></td></tr>';
									echo '<tr class="collapse" id="edit_admin'.$_SESSION['how_admins'].'"><td colspan="3">
									<form method="post" action="show_admin_actions.php"><div class="table-responsive"><table class="table">
									<tr><th colspan="2">Zarządzanie aministratorem</th></tr>
									<tr><td><label for="login">Login:</td><td><input type="text" name="login" id="login" value="'.$admins_results['login'].'"><input type="hidden" name="admin_id" value="'.$admins_results['id'].'"></td></tr>
									<tr><td><label for="class">Klasa:</td><td>';
									if($class=$connect->query("SELECT id, section, class FROM classes WHERE section!='admin'"))
									{
										if($class->num_rows>0)
										{
											$_SESSION['admins_class']=Array();
											$i=0;
											echo '<select id="class" name="class"><option selected hidden value="'.$admins_results['id_klasy'].'">'.$admins_results['section'].'</option>';
											while($class_results=$class->fetch_assoc())
											{
												if($class_results['section']=='Administratorzy')
													$class_results['class']='';
												echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
												$_SESSION['admins_class'][$i]=$class_results['id'];
												$i++;
											}
											echo '</select>';
										}
										else
											throw new Exception($connect->error);
									}
									else
										throw new Exception($connect->error);
									echo '</td></tr>
									</table></div>
									<p><input type="submit" name="change" class="editing" value="Zmień"></p>
									</form>
									</td></tr>';
									$_SESSION['how_admins']++;
								}
								echo '</table></div>
								<button name="delete" class="editing">Usuń z systemu <i class="icon-trash-empty"></i></button>
								</form>';
							}
							else
								throw new Exception($connect->error);
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