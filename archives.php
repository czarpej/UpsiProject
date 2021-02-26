<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

if(isset($_SESSION['which_class']))
{
	header('Location: edit_test.php');
	exit();
}

$_SESSION['admin_log_now']=true;

if(isset($_SESSION['class_to_show']))
	unset($_SESSION['class_to_show']);

if(isset($_SESSION['type_class']))
	unset($_SESSION['type_class']);

unset($_SESSION['subject_type']);
unset($_SESSION['class_type']);
unset($_SESSION['student_class']);
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
	<style>
	@media(min-width: 768px) and (max-width: 991px)
	{
		.editing_button
		{
			max-width: 240px;
		}
	}
	</style>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="js/scroll_to_id_users.js"></script>

</head>
<body>

	<?php
	require_once 'menu_admin.php';
	echo $menu_admin;
	?>

	<section class="admin">
		<div class="container">
			<div class="row">
				
				<div class="col-12">
					<h3>Przeglądanie archiwum</h3>

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

							function forwarding()
							{
								header('Location: archives.php');
								exit();
							}

							function class_connect_error()
							{
								$_SESSION['connect_error']='error';
								forwarding();
							}

							echo '<input type="button" class="editing hidding" data-toggle="collapse" data-target="#search_all" value="Uczniowie według klas"><br>
							<div class="collapse" id="search_all">
							<fieldset><legend>Uczniowie według klas</legend><div class="table-responsive"><table class="table">
							<form method="post" id="year_form">
							<tr><td><label for="year">Wybierz rocznik:</label></td><td>';
							if($year=$connect->query("SELECT DISTINCT year FROM archive_users"))
							{
								if($year->num_rows>0)
								{
									$_SESSION['searched_year']=Array();
									$i=0;
									echo '<select name="year" id="year"><option selected disabled hidden style="display: none" value=""> -- wybierz rocznik -- </option>';
									while($year_results=$year->fetch_row())
									{
										echo '<option value="'.$year_results[0].'">'.$year_results[0].'</option>';
										$_SESSION['searched_year'][$i]=$year_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
									echo 'Archiwum jest puste.';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr></form>
							<form method="post"><tr><td><label for="class">Wybierz klasę:</label></td><td id="for_class">';

							function echo_class($class)
							{
								if($class->num_rows>0)
								{
									echo '<select name="class" id="class"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
									$_SESSION['searched_class']=Array();
									$i=0;
									while($class_results=$class->fetch_row())
									{
										echo '<option value="'.$class_results[0].'">'.$class_results[0].'</option>';
										$_SESSION['searched_class'][$i]=$class_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
									class_connect_error();
							}

							if(isset($_POST['year']))
							{
								$year_ok=false;
								foreach($_SESSION['searched_year'] as $value)
								{
									if($value==$_POST['year'])
									{
										$year_ok=true;
										break;
									}
								}
								if($year_ok==false)
								{
									$_SESSION['error']='<p>Wybrany rocznik jest niezgodny z rezultatami wyszukań!</p>';
									forwarding();
								}
								else
								{
									$_SESSION['yearbook']=$_POST['year'];
									if($class=$connect->query("SELECT DISTINCT class FROM archive_users WHERE year='".$_POST['year']."' "))
										echo_class($class);
									else
										class_connect_error();
								}
							}
							else if(isset($_SESSION['yearbook']))
							{
								if($class=$connect->query("SELECT DISTINCT class FROM archive_users WHERE year='".$_SESSION['yearbook']."' "))
									echo_class($class);
								else
									class_connect_error();
							}
							else
								echo 'Najpierw wybierz rocznik.';
							echo '</td><tr>
							</table>
							<p><button name="search" class="editing">Wyszukaj <i class="icon-search"></i></button></p>
							</form></div></fieldset></div>

							<input type="button" class="editing hidding" data-toggle="collapse" data-target="#search_one" value="Ręczne wyszukiwanie ucznia"><br>
							<div class="collapse" id="search_one">
							<form method="post"><fieldset><legend>Ręczne wyszukiwanie ucznia</legend><div class="table-responsive"><table class="table">
							<tr><td><label for="manual_user">Wprowadź szukaną frazę:</label></td><td><input type="text" name="manual_user" id="manual_user"></td></tr>
							</table>
							<p><button name="manual_search" class="editing">Wyszukaj <i class="icon-search"></i></button></p>
							</div></fieldset></form>
							</div>';

							echo '
							<script>
							$("#year").bind("change", function () {
							    $("#year_form").submit();
							});
							</script>
							';

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

							if(isset($_POST['search']))
							{
								echo '<div class="col-12" id="users">';
								if(!isset($_POST['class']) || $_POST['class']=='')
									echo '<p>Nie wybrano klasy!</p>';
								else
								{
									$class_ok=false;
									foreach($_SESSION['searched_class'] as $value)
									{
										if($value==$_POST['class'])
										{
											$class_ok=true;
											break;
										}
									}
									if($class_ok==false)
									{
										$_SESSION['error']='<p>Wybrana klasa jest niezgodna z rezultatami wyszukań!</p>';
										forwarding();
									}
									else
									{
										if($students=$connect->query("SELECT id, login FROM archive_users WHERE year='".$_SESSION['yearbook']."' AND class='".$_POST['class']."' "))
										{
											if($students->num_rows>0)
											{
												echo '<div class="table-responsive"><form method="post" action="archives_actions.php"><table class="table table-bordered">
												<tr><th>Lp.</th><th>Użytkownik</th><th>Testy</th><th>Usuwanie</th></tr>';
												$_SESSION['how_users']=0;
												while($students_results=$students->fetch_assoc())
												{
													echo '<tr><td>'.($_SESSION['how_users']+1).'</td><td>'.$students_results['login'].'</td><td><form method="post" action="archives_actions.php"><input type="hidden" name="archived_student" value="'.$students_results['id'].'"><input type="hidden" name="archived_login" value="'.$students_results['login'].'"><button name="results" class="editing editing_button">Wyszukaj <i class="icon-search"></i></button></form></td><td><input type="checkbox" name="student'.$_SESSION['how_users'].'" value="'.$students_results['id'].'"></td></tr>';
													$_SESSION['how_users']++;
												}
												echo '</table>
												<p><input type="submit" value="Usuń z archiwum" name="delete_archived" class="editing"></p>
												</form></div>';
											}
											else
												throw new Exception($connect->error);
										}
										else
											throw new Exception($connect->error);
									}
								}
								unset($_SESSION['yearbook']);
								echo '<script>$("#for_class").html("Najpierw wybierz rocznik.");</script>';
								echo '</div>';
							}

							if(isset($_POST['manual_search']))
							{
								unset($_SESSION['yearbook']);
								echo '<div class="col-12" id="users">';
								if(!isset($_POST['manual_user']) || $_POST['manual_user']=='')
									echo '<p>Nie wprowadzono frazy do wyszukania!</p>';
								else
								{
									if($user=$connect->query("SELECT * FROM archive_users WHERE login LIKE '%".$_POST['manual_user']."%' "))
									{
										if($user->num_rows>0)
										{
											$_SESSION['how_users']=0;
											echo '<form method="post" action="archives_actions.php"><div class="table-responsive">
											<div class="searched_question"><h3>Rezultaty wyszukań dla frazy "'.$_POST['manual_user'].'":</h3>';
											while($user_results=$user->fetch_assoc())
											{
												echo '
												<div class="this_question">
													<div class="content_question">
														Użytkownik: '.$user_results['login'].'<br>
														Klasa: '.$user_results['class'].'<br>
														Rocznik: '.$user_results['year'].'<br>
													</div>
													<div class="info_question">
														<div class="info_question_answer">
															<form method="post" action="archives_actions.php">Testy: <br><input type="hidden" name="archived_student" value="'.$user_results['id'].'"><input type="hidden" name="archived_login" value="'.$user_results['login'].'"><button name="results" class="editing editing_button">Wyszukaj <i class="icon-search"></i></button></form>
														</div>
														<div class="change_question">
															Zaznacz do usunięcia: <input type="checkbox" name="student'.$_SESSION['how_users'].'" value="'.$user_results['id'].'">
														</div>
													</div>
												</div>';
												$_SESSION['how_users']++;
											}
											echo '<p><button name="delete_archived" class="editing">Usuń z archiwum <i class="icon-trash-empty"></i></button></p>
											</div></div></div></form>';
										}
										else
											echo '<p>Brak wyników wyszukań dla szukanej frazy.</p>';
									}
									else
										throw new Exception($connect->error);
								}
								echo '</div>';
							}

							if(isset($_SESSION['yearbook']) && !isset($_POST['search']))
							{
								echo '<script>
								$("#year").val("'.$_SESSION['yearbook'].'");
								$("#search_all").css("display", "block");
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