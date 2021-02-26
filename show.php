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

if(isset($_POST['edit']))
{
	if(isset($_POST['manage']))
	{
		$_SESSION['select_user']=$_POST['manage'];
		header('Location: edit.php');
		exit();
	}
	else
		$_SESSION['action_ok']='<p>Nie wybrano użytkownika!</p>';
}

if(isset($_POST['archive']))
{
	$_SESSION['archive']=true;
	header('Location: show_actions.php');
	exit();
}
else if(isset($_POST['delete']))
{
	$_SESSION['delete']=true;
	header('Location: show_actions.php');
	exit();
}
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
						<h3>Użytkownicy systemu</h3>
						<input type="button" class="editing hidding" data-toggle="collapse" data-target="#klasa" value="Według klas"><br>
						<div class="collapse" id="klasa"><form method="post" id="class_form">
						<fieldset><legend>Według klas</legend><div class="table-responsive"><table class="table"><tr><td>
						<label for="class_type">Wybierz klasę: </label></td><td>';
						if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy'"))
						{
							if($class->num_rows>0)
							{
								$_SESSION['class_to_add']=Array();
								$i=0;
								echo '<select name="class_type" id="class_type"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								while($class_results=$class->fetch_assoc())
								{
									echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
									$_SESSION['class_to_add'][$i]=$class_results['id'];
									$i++;
								}
								if($class->num_rows>=2)
								{
									echo '<option value="all">Wszyscy</option></select>';
									$_SESSION['class_to_add'][$i]='all';
								}
							}
							else
								echo 'Nie istnieją żadne klasy w systemie';
						}
						else
							throw new Exception($connect->error);
						echo '</td></tr></table></div></fieldset></form></div>';

						echo '
								<script>
								$("#class_type").bind("change", function () {
								    $("#class_form").submit();
								});
								</script>
								';
						echo '<input type="button" class="editing hidding" data-toggle="collapse" data-target="#reczne_wyszukiwanie" value="Ręczne wyszukiwanie"><br><div class="collapse" id="reczne_wyszukiwanie"><form method="post"><fieldset><legend>Ręczne wyszukiwanie</legend><div class="table-responsive"><table class="table"><tr><td><label for="name_user">Wprowadź szukaną frazę: </label></td><td><input type="text" name="name_user" id="name_user"> </td></tr></table></div><p><button name="manual_find" class="editing">Szukaj <i class="icon-search"></i></button></p></fieldset></form></div>

						<input type="button" class="editing hidding" data-toggle="collapse" data-target="#freezing_users" value="Zablokowani użytkownicy"><br>
						<div class="collapse" id="freezing_users"><form method="post" action="show_actions.php">
						<fieldset><legend>Zablokowani użytkownicy</legend>';
						if($freezing_users=$connect->query("SELECT `users`.`login`, `users`.`id`, `classes`.`class`, `classes`.`section` FROM users, classes WHERE `classes`.`section`!='admin' AND `users`.`freezing`!=0 AND `classes`.`id`=`users`.`id_klasy`"))
						{
							if($freezing_users->num_rows>0)
							{
								echo '<div class="table-responsive"><table class="table table-bordered">
								<tr><th>Użytkownik</th><th>Klasa</th><th>Odblokowanie</th><th>Masowe odblokowanie</th></tr>';
								$i=0;
								$_SESSION['block_users']=Array();
								while($freezing_users_results=$freezing_users->fetch_assoc())
								{
									echo '<tr><td>'.$freezing_users_results['login'].'</td><td>'.$freezing_users_results['class'].''.$freezing_users_results['section'].'</td><td><form method="post" action="show_actions.php"><input type="hidden" name="which_block" value="'.$freezing_users_results['id'].'"><button name="unblock_one" class="editing editing_button">Odblokuj <i class="icon-lock-open"></i></button></form></td><td><input type="checkbox" name="unblock'.$i.'" value="'.$freezing_users_results['id'].'"></tr>';
									$_SESSION['block_users'][$i]=$freezing_users_results['id'];
									$i++;
								}
								echo '</table></div>
								<p><input type="submit" class="editing" value="Odblokuj wybranych" name="mass_unblock"></p>';
							}
							else
								echo '<p>Obecnie żaden użytkownik nie jest zablokowany.</p>';
						}
						else
							throw new Exception($connect->error);
						echo '</fieldset></form></div>';

						
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

						if(isset($_SESSION['action_ok']))
						{
							echo '<div class="col-12">'.$_SESSION['action_ok'].'</div>';
							unset($_SESSION['action_ok']);
						}

						if(isset($_POST['class_type']))
						{
							echo '<div class="col-12"><form method="post"><p>';
							$class_ok=false;
							foreach($_SESSION['class_to_add'] as $value)
							{
								if($value==$_POST['class_type'])
								{
									$class_ok=true;
									break;
								}
							}
							unset($_SESSION['class_to_add']);
							if($class_ok==false)
								echo '<p>Wybrana klasa nie jest rezultatem wyszukania przez system!</p>';
							else
							{
								function echo_users($users)
								{
									$lp=1;
									echo '
									<div class="table-responsive">
										<div class="searched_question">
											<h3>Użytkownicy wybranej klasy:</h3>';
											while($users_results=$users->fetch_assoc())
											{
												echo '
												<div class="this_question">
													<div class="content_question">
														Użytkownik: '.$users_results['login'].'<br>
														Klasa: '.$users_results['class'].''.$users_results['section'].'
													</div>
													<div class="info_question">
														<div class="info_question_answer">
															Akcje:<br>
															<form method="post"><input type="hidden" name="manage" value="'.$users_results['id'].'"><button name="edit" class="editing editing_button">Zarządzaj <i class="icon-vcard"></i></button></form>
															<form method="post" action="show_actions.php"><input type="hidden" name="which_block" value="'.$users_results['id'].'">';
															if($users_results['freezing']==0)
																echo '<button name="block_one" class="editing editing_button">Zablokuj <i class="icon-block"></i></button>';
															else
																echo '<button name="unblock_one" class="editing editing_button">Odblokuj <i class="icon-lock-open"></i></button>';
															echo '</form>
														</div>
														<div class="change_question">
															Wykonanie operacji <input type="checkbox" name="to_delete'.$users_results['id'].'" value="'.$users_results['id'].'"><br>
														</div>
													</div>
												</div>';
												$lp++;
											}
										echo '
										</div>';
										$_SESSION['how_users']=$lp;
									echo '

									<p><button name="archive" class="editing">Archiwizuj <i class="icon-archive"></i></button> <button name="delete" class="editing">Usuń <i class="icon-trash-empty"></i></button></p>';
								}

								$class_type=$_POST['class_type'];
								if($class_type=='all')
								{
									if($users=$connect->query("SELECT `users`.`id`, `users`.`login`, `users`.`freezing`, `classes`.`class`, `classes`.`section` FROM users, classes WHERE `users`.`id_klasy`=`classes`.`id` ORDER BY login ASC"))
									{
										if($users->num_rows>0)
											echo_users($users);
										else
											echo 'Brak jakiegokolwiek ucznia w systemie.';
									}
									else
										throw new Exception($connect->error);
								}
								else
								{
									if($users=$connect->query("SELECT `users`.`id`, `users`.`login`, `users`.`freezing`, `classes`.`class`, `classes`.`section` FROM users, classes WHERE id_klasy='".$class_type."' AND `users`.`id_klasy`=`classes`.`id` ORDER BY login ASC"))
									{
										if($users->num_rows>0)
											echo_users($users);
										else
											echo 'Brak uczniów w tej klasie.';
									}
									else
										throw new Exception($connect->error);
								}
							}
							echo '</p></form></div>';
						}

						if(isset($_POST['name_user']))
						{
							echo '<div class="col-12"><p>';
							$login=$_POST['name_user'];
							if($login=='')
								echo 'Nie podano nazwy użytkownika!';
							else
							{
								if($manual_find=$connect->query("SELECT `users`.`id`, `users`.`login`, `classes`.`class`, `classes`.`section` FROM users, classes WHERE login LIKE '%".$login."%' AND `classes`.`section`!='admin' AND `classes`.`section`!='Administratorzy' AND `users`.`id_klasy`=`classes`.`id` ORDER BY login ASC"))
								{
									if($manual_find->num_rows>0)
									{
										$lp=1;
										echo '<form method="post"><h3>Rezultaty wyszukań dla frazy "'.$login.'":</h3><div class="table-responsive"><table class="table table-bordered" id="users"><tr><th>Lp.</th><th>Uczeń</th><th>Klasa</th><th>Zarządzanie</th></tr>';
										while($manual_find_results=$manual_find->fetch_assoc())
										{
											echo '<tr><td>'.$lp.'</td><td>'.$manual_find_results['login'].'</td><td>'.$manual_find_results['class'].''.$manual_find_results['section'].'</td><td><input type="radio" name="manage" value="'.$manual_find_results['id'].'"></td></tr>';
											$lp++;
										}
										$_SESSION['how_users']=$lp;
										echo '</table></div>
										<p><input type="submit" name="edit" value="Zarządzaj" class="editing"></p>
										</form>';
									}
									else
										echo '<p>Brak wyników wyszukań dla szukanej frazy.</p>';
								}
								else
									throw new Exception($connect->error);
							}
							echo '</p></div>';
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