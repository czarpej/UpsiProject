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

if(isset($_SESSION['which_class']))
{
	header('Location: edit_test.php');
	exit();
}

if(!isset($_SESSION['select_user']))
{
	header('Location: show.php');
	exit();
}

if(isset($_SESSION['class_to_show']))
	unset($_SESSION['class_to_show']);

unset($_SESSION['subject_type']);
unset($_SESSION['class_type']);
unset($_SESSION['student_class']);
unset($_SESSION['yearbook']);
unset($_SESSION['exam_from_question']);

function unsetting()
{
	unset($_SESSION['select_user']);
	unset($_SESSION['change_class_good']);
	unset($_SESSION['change_login_ok']);
	header('Location: show.php');
	exit();
}

if(isset($_POST['canel']))
	unsetting();
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

	<nav class="navbar navbar-dark navbar-expand-sm">
		<ol class="navbar-nav">
			<li class="nav-item"><a class="nav-link"><form method="post"><button name="canel"><i class="icon-cancel-circled"></i> Anuluj</button></form></a></li>
			<li class="nav-item"><a class="nav-link"><form action='logout.php' method='post'><button name='logout'><i class="icon-logout"></i> Wyloguj</button></form></a></li>
		</ol>
	</nav>

<section class='edit'>
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

						if($which_user=$connect->query("SELECT id, login, id_klasy, freezing FROM users WHERE id='".$_SESSION['select_user']."' "))
						{
							if($which_user->num_rows>0)
							{
								$which_user_results=$which_user->fetch_assoc();
								if($users_class=$connect->query("SELECT * FROM classes WHERE id='".$which_user_results['id_klasy']."' "))
								{
									if($users_class->num_rows>0)
									{
										$users_class_results=$users_class->fetch_assoc();
										echo '
										<h3>Zarządzenie użytkownikiem</h3>
										';
										echo '<p>
										<div class="table-responsive"><table class="table table-bordered">
										<tr><th>Użytkownik</th><th>Klasa</th></tr>
										<tr><td>';
									
										echo $which_user_results['login'].'</td><td>'.$users_class_results['class'].''.$users_class_results['section'].'</td></tr>';
										echo '</table></div>
										
										<p>
										<form method="post"><button name="reset_password" class="editing editing_button">Resetuj hasło <i class="icon-arrows-cw"></i></button><br></form>						
										<button type="button" class="editing hidding" data-toggle="collapse" data-target="#klasa">Zmień klasę użytkownika <i class="icon-school"></i></button><br>	
											<div class="collapse" id="klasa"><div class="table-responsive"><fieldset><legend>Zmiana klasy</legend><form method="post"><label for="class">Wybierz klasę:</label> ';
											$class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy'");
											if($class->num_rows>0)
											{
												$_SESSION['class_for_user']=Array();
												$i=0;
												echo '<p><select name="class" id="class"><option selected hidden value="'.$which_user_results['id_klasy'].'">'.$users_class_results['class'].''.$users_class_results['section'].'</option>';
												while($class_results=$class->fetch_assoc())
												{
													echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
													$_SESSION['class_for_user'][$i]=$class_results['id'];
													$i++;
												}
												echo '</select></p>';
											}
											else
												echo 'Nie istnieją żadne klasy w systemie';
											echo '<p><input type="submit" name="change_clas" value="Zmień" class="editing"></p></form></fieldset></div></div>					
										<button type="button" class="editing hidding" data-toggle="collapse" data-target="#nazwa">Zmień nazwę użytkownika <i class="icon-user"></i></button><br>
											<div class="collapse" id="nazwa">
											<div class="table-responsive"><fieldset><legend>Zmiana nazwy użytkownika</legend><form method="post"><p>Wprowadź nową nazwę użytkownika: <input type="text" name="new_login">
											<p><input type="submit" name="change_log" value="Zmień" class="editing"></p>
											</p></form></fieldset></div></div>
										<button type="button" class="editing hidding" data-toggle="collapse" data-target="#usun">Usuń użytkownika <i class="icon-trash-empty"></i></button>
											<div class="collapse" id="usun"><div class="table-responsive">
											<fieldset><legend>Usuwanie użytkownika</legend><form method="post">
											<table class="table">
											<tr><td><input type="radio" name="together" value="archive" id="archive" checked> <label for="archive">Przeniesienie do archiwum</label></td><td><input type="radio" name="together" value="delete" id="delete"> <label for="delete">Usunięcie ucznia</label></td></tr>
											</table> 
											<p><input type="submit" name="delete" value="Usuń" class="editing"></p>
											</form></fieldset>
											</div></div>
										<form method="post">';
										if($which_user_results['freezing']!=0)
											echo '<button class="editing editing_button" name="freezing">Odblokuj użytkownika <i class="icon-lock-open"></i></button>';
										else
											echo '<button class="editing editing_button" name="freezing">Zablokuj użytkownika <i class="icon-block"></i></button>';
										echo '</form>
										<form method="post"><button class="editing editing_button" name="add_admin">Dodaj do administracji <i class="icon-crown-plus"></i></button></form>
										</p>
										</p>';
									}
									else
										throw new Exception($connect->error);
								}
							}
							else
								echo '<p>Błąd! Nie znaleziono w systemie wybranego użytkownika!</p>';
						}
						else
							throw new Exception($connect->error);
				?>
				
			</div>

			<?php
						//reset password
						if(isset($_POST['reset_password']))
						{
							echo '<div class="col-12" id="users">';
							if($reset_password=$connect->query("UPDATE `users` SET `haslo`='' WHERE `users`.`id`='".$which_user_results['id']."' "))
								echo '<p>Pomyślnie zresetowano hasło użytkownika.</p>';
							else
								throw new Exception($connect->error);
							echo '</div>';
						}

						//write communicat about good change
						if(isset($_SESSION['change_class_good']))
						{
							echo '<div class="col-12" id="users">';
							echo $_SESSION['change_class_good'];
							unset($_SESSION['change_class_good']);
							echo '</div>';
						}

						//change class
						if(isset($_POST['change_clas']))
						{
							echo '<div class="col-12" id="users">';
							$class_ok=false;
							foreach($_SESSION['class_for_user'] as $value)
							{
								if($value==$_POST['class'])
								{
									$class_ok=true;
									break;
								}
							}
							unset($_SESSION['class_for_user']);
							if($class_ok==false)
								echo '<p>Wybrana klasa jest niezgodna z rezulatatami wyszukań!</p>';
							else
							{
								if($connect->query("UPDATE users SET id_klasy='".$_POST['class']."' WHERE `users`.`id`='".$which_user_results['id']."' "))
								{
									unset($_POST['change_clas']);
									$_SESSION['change_class_good']='<p>Pomyślnie zmieniono klasę użytkownika.</p>';
									header('Location: edit.php');
								}
								else
									throw new Exception($connect->error);
							}
							echo '</div>';
						}

						//write communicat about good change
						if(isset($_SESSION['change_login_ok']))
						{
							echo '<div class="col-12" id="users">';
							echo $_SESSION['change_login_ok'];
							unset($_SESSION['change_login_ok']);
							echo '</div>';
						}

						if(isset($_POST['change_log']))
						{
							echo '<div class="col-12" id="users">';
							if($_POST['new_login']=='')
								echo '<p>Nie podano nowej nazwy użytkownika!</p>';
							else
							{
								if($connect->query("UPDATE `users` SET `login`='".$_POST['new_login']."' WHERE `users`.`id`='".$which_user_results['id']."' "))
								{
									unset($_POST['change_log']);
									$_SESSION['change_login_ok']='<p>Pomyślnie zmieniono nazwę użytkownika.</p>';
									header('Location: edit.php');
								}
								else
									throw new Exception($connect->error);
							}
							echo '</div>';
						}
						
						if(isset($_POST['delete']))
						{
							echo '<div class="col-12" id="users">';
							if(!isset($_POST['together']))
							{
								echo '<p>Nie wybrano jaką akcję podjąć dla wybranego użytkownika!</p>';
							}
							else
							{
								if($_POST['together']=='archive')
								{
									if($choosen_user=$connect->query("SELECT login, id_klasy FROM users WHERE id='".$_SESSION['select_user']."' "))
									{
										if($choosen_user->num_rows>0)
										{
											$choosen_user_results=$choosen_user->fetch_assoc();
											if($choosen_user_class=$connect->query("SELECT * FROM classes WHERE id='".$choosen_user_results['id_klasy']."' "))
											{
												if($choosen_user_class->num_rows>0)
												{
													$choosen_user_class_results=$choosen_user_class->fetch_assoc();
													if($connect->query("INSERT INTO archive_users VALUES('', '".$choosen_user_results['login']."', '".$choosen_user_class_results['class']."".$choosen_user_class_results['section']."', '".$choosen_user_class_results['year_started']."-".($choosen_user_class_results['year_started']+$choosen_user_class_results['class'])."')"))
													{
														if($choosen_archive_user=$connect->query("SELECT id FROM archive_users"))
														{
															if($choosen_archive_user->num_rows>0)
															{
																$choosen_id_archive_user='';
																while($choosen_archive_user_results=$choosen_archive_user->fetch_row())
																	$choosen_id_archive_user=$choosen_archive_user_results[0];

																if($actual_score=$connect->query("SELECT * FROM results WHERE id_users='".$_SESSION['select_user']."' "))
																{
																	if($actual_score->num_rows>0)
																	{
																		while($actual_score_results=$actual_score->fetch_assoc())
																		{
																			if($connect->query("INSERT INTO archive_results VALUES('', '".$choosen_id_archive_user."', '".$actual_score_results['exam_category']."', '".$actual_score_results['comment']."', '".$actual_score_results['score']."', '".$actual_score_results['mark']."', '".$actual_score_results['count_question']."', '".$actual_score_results['extra_points']."', '".$actual_score_results['multipler_points']."', '".$actual_score_results['date']."', '".$actual_score_results['grade_2']."', '".$actual_score_results['grade_3']."', '".$actual_score_results['grade_4']."', '".$actual_score_results['grade_5']."', '".$actual_score_results['grade_6']."')"))
																				;
																			else
																			{
																				echo '<p>Pomyślnie dodano użytkownika do archiwum.</p>';
																				throw new Exception($connect->error);
																			}
																		}
																	}
																	else
																		;
																}
																else
																{
																	echo '<p>Pomyślnie dodano użytkownika do archiwum.</p>';
																	throw new Exception($connect->error);
																}
															}
															else
																throw new Exception($connect->error);
														}
														else
														{
															echo '<p>Pomyślnie dodano użytkownika do archiwum.</p>';
															throw new Exception($connect->error);
														}
													}
													else
														throw new Exception($connect->error);

													if(($connect->query("DELETE FROM results WHERE id_users='".$_SESSION['select_user']."' ")) && ($connect->query("DELETE FROM users WHERE id='".$_SESSION['select_user']."' ")))
													{
														unset($_SESSION['select_user']);
														$_SESSION['action_ok']='<p>Pomyślnie przeniesiono wybranego użytkownika do archiwum.</p>';
														header('Location: show.php');
														exit();
													}
													else
													{
														echo '<p>Dodano użytkownika do archiwum wraz z wynikami.</p>';
														throw new Exception($connect->error);
													}
												}
												else
													throw new Exception($connect->error);
											}
											else
												throw new Exception($connect->error);
										}
										else
											echo '<p>Nie znaleziono wybranego użytkownika w systemie!</p>';
									}
									else
										throw new Exception($connect->error);
								}
								else if($_POST['together']=='delete')
								{
									if(($connect->query("DELETE FROM results WHERE id_users='".$_SESSION['select_user']."' ")) && ($connect->query("DELETE FROM users WHERE id='".$_SESSION['select_user']."' ")))
									{
										unset($_SESSION['select_user']);
										$_SESSION['action_ok']='<p>Pomyślnie usunięto wybranego użytkownika z systemu.</p>';
										header('Location: show.php');
										exit();
									}
									else
										throw new Exception($connect->error);
								}
								else
									echo '<p>Błąd! Wybrano złe działanie!</p>';
							}
							echo '</div>';
						}

						if(isset($_POST['freezing']))
						{
							echo '<div class="col-12" id="users">';
							if($user_block=$connect->query("SELECT freezing FROM users WHERE id='".$_SESSION['select_user']."' "))
							{
								if($user_block->num_rows>0)
								{
									$freezing='';
									$user_block_results=$user_block->fetch_row();
									if($user_block_results[0]!=0)
										$freezing=0;
									else
										$freezing=1;
									if($connect->query("UPDATE users SET freezing='".$freezing."' WHERE id='".$_SESSION['select_user']."' "))
									{
										if($freezing==0)
											$_SESSION['change_class_good']='<p>Pomyślnie odblokowano użytkownika.</p>';
										else
											$_SESSION['change_class_good']='<p>Pomyślnie zablokowano użytkownika.</p>';
										header('Location: edit.php');
										exit();
									}
									else
										throw new Exception($connect->error);
								}
								else
									throw new Exception($connect->error);
							}
							else
								throw new Exception($connect->error);
							echo '</div>';
						}

						if(isset($_POST['add_admin']))
						{
							echo '<div class="col-12" id="users">';
							if($connect->query("UPDATE users SET id_klasy=5 WHERE id='".$_SESSION['select_user']."' "))
							{
								$_SESSION['action_ok']='<p>Pomyślnie przeniesiono użytkowanika do administracji.</p>';
								unsetting();
							}
							throw new Exception($connect->error);
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

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/navbar.js"></script>
<script src="js/width_fieldset.js"></script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->