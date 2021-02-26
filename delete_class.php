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

	<section class='delete_class'>
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
						<h2>Usuwanie klasy</h2>
						<form method="post">
						<p><div class="table-responsive one_option"><table class="table">
						<tr><td><label for="class_type">Wybierz klasę:<label></td><td>';
						if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy' ORDER BY section ASC"))
						{
							if($class->num_rows>0)
							{
								$_SESSION['class_to_delete']=Array();
								$i=0;
								echo '<select name="class_type" id="class_type"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								while($class_results=$class->fetch_assoc())
								{
									echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
									$_SESSION['class_to_delete'][$i]=$class_results['id'];
									$i++;
								}
								echo '</select>';
							}
							else
								echo 'Nie istnieją żadne klasy w systemie';

						}
						else
							throw new Exception($connect->error);
						echo '</td></tr>
						<tr><td><input type="radio" name="delete_or_archive" value="archive" id="archive" checked> <label for="archive">Przeniesienie uczniów wraz z wynikami do archiwum</label></td><td><input type="radio" name="delete_or_archive" id="delete" value="delete"> <label for="delete">Usunięcie uczniów wraz z wynikami</label></td></tr>
						</table></div>
						<p><input type="submit" class="editing" name="as_class" value="Usuń"></p>
						</p>
						</form>';
						
				?>				
				
			</div>

			<?php
						if(isset($_SESSION['delete_class']))
						{
							echo '<div class="col-12" id="users">';
							echo $_SESSION['delete_class'];
							unset($_SESSION['delete_class']);
							echo '</div>';
						}

						//delete class
						if(isset($_SESSION['archive_results_ok']))
						{
							if(($connect->query("DELETE FROM actual_test WHERE id_class='".$_SESSION['archive_results_ok']."' ")) && ($connect->query("DELETE FROM grade_norm WHERE id_class='".$_SESSION['archive_results_ok']."' ")) && ($connect->query("DELETE FROM `users` WHERE `users`.`id_klasy`='".$_SESSION['archive_results_ok']."' ")) && ($connect->query("DELETE FROM `classes` WHERE `classes`.`id`='".$_SESSION['archive_results_ok']."' ")) )
							{
								unset($_SESSION['archive_results_ok']);
								$_SESSION['delete_class']='<p>Pomyślnie przeniesiono wybraną klasę do archiwum oraz usunięto ją z aktualnych danych.</p>';
								header('Location: delete_class.php');
								exit();
							}
							else
								throw new Exception($connect->error);
						}

						if(isset($_SESSION['delete_ok']))
						{
							if(($connect->query("DELETE FROM actual_test WHERE id_class='".$_SESSION['delete_ok']."' ")) && ($connect->query("DELETE FROM grade_norm WHERE id_class='".$_SESSION['delete_ok']."' ")) && ($connect->query("DELETE FROM users WHERE id_klasy='".$_SESSION['delete_ok']."' ")) && ($connect->query("DELETE FROM classes WHERE id='".$_SESSION['delete_ok']."' ")))
							{
								unset($_SESSION['delete_ok']);
								$_SESSION['delete_class']='<p>Pomyślnie usunięto wybraną klasę z systemu.</p>';
								header('Location: delete_class.php');
								exit();
							}
							else
								throw new Exception($connect->error);
						}

						if(isset($_POST['as_class']))
						{
							echo '<div class="col-12" id="users">';
							if(isset($_POST['class_type']))
							{
								$class_ok=false;
								foreach($_SESSION['class_to_delete'] as $value)
								{
									if($value==$_POST['class_type'])
									{
										$class_ok=true;
										break;
									}
								}
								if($class_ok==false)
									echo '<p>Wybrana klasa jest niezgodna z rezultatami wyszukań!</p>';
								else
								{
									if(isset($_POST['delete_or_archive']))
									{
										if($_POST['delete_or_archive']=='archive')
										{
											if($choosen_class=$connect->query("SELECT `users`.`id`, `users`.`login`, `users`.`id_klasy`, `classes`.`class`, `classes`.`section`, `classes`.`year_started` FROM users, classes WHERE `users`.`id_klasy`='".$_POST['class_type']."' AND `classes`.`id`='".$_POST['class_type']."' "))
											{
												if($choosen_class->num_rows>0)
												{
													$help_variable=0;
													while($choosen_class_results=$choosen_class->fetch_assoc())
													{
														if($connect->query("INSERT INTO archive_users VALUES('', '".$choosen_class_results['login']."', '".$choosen_class_results['class']."".$choosen_class_results['section']."', '".$choosen_class_results['year_started']."-".($choosen_class_results['year_started']+$choosen_class_results['class'])."') ")) //add deleting users to archive
														{
															echo '<p>Dodano użytkowników do archiwum. Skończono dodawanie na użytkowniku '.$choosen_class_results['login'].'.</p>';
															if($which_id=$connect->query("SELECT id FROM archive_users")) //select user's id
															{
																if($which_id->num_rows>0) 
																{
																	$id_users='';
																	while($which_id_results=$which_id->fetch_row())
																		$id_users=$which_id_results[0];
																	if($choosen_score=$connect->query("SELECT * FROM results WHERE id_users='".$choosen_class_results['id']."' ")) //select all results from results for choosen user
																	{
																		if($choosen_score->num_rows>0) //test's results from table results is exist
																		{
																			while($choosen_score_results=$choosen_score->fetch_assoc())
																			{
																				if(($connect->query("INSERT INTO archive_results VALUES('', '".$id_users."', '".$choosen_score_results['exam_category']."', '".$choosen_score_results['comment']."', '".$choosen_score_results['score']."', '".$choosen_score_results['mark']."', '".$choosen_score_results['count_question']."', '".$choosen_score_results['extra_points']."', '".$choosen_score_results['multipler_points']."', '".$choosen_score_results['date']."', '".$choosen_score_results['grade_2']."', '".$choosen_score_results['grade_3']."', '".$choosen_score_results['grade_4']."', '".$choosen_score_results['grade_5']."', '".$choosen_score_results['grade_6']."')")) && ($connect->query("DELETE FROM results WHERE id_results='".$choosen_score_results['id_results']."' "))) 
																				{
																					; //add results tests to archive for choosen user
																				}
																				else
																					throw new Exception($connect->error);
																			}
																		}
																		else
																		{
																			echo '<p>Nie znaleziono testów do przeniesienia dla tej klasy!</p>';
																			if(($connect->query("DELETE FROM actual_test WHERE id_class='".$_POST['class_type']."' ")) && ($connect->query("DELETE FROM `users` WHERE `users`.`id_klasy`='".$_POST['class_type']."' ")) && ($connect->query("DELETE FROM `classes` WHERE `classes`.`id`='".$_POST['class_type']."' ")) )
																			{
																				$_SESSION['delete_class']='<p>Nie znaleziono testów do przeniesienia dla tej klasy!<br>Pomyślnie przeniesiono wybraną klasę do archiwum oraz usunięto ich z aktualnych danych.</p>';
																				header('Location: delete_class.php');
																				exit();
																			}
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
																throw new Exception($connect->error);
														}
														else
															throw new Exception($connect->error);
														$help_variable++;
														if($help_variable==$choosen_class->num_rows)
														{
															$_SESSION['archive_results_ok']=$_POST['class_type'];
															header('Location: delete_class.php');
															exit();
														}
													}
												}
												else
												{
													$_SESSION['archive_results_ok']=$_POST['class_type'];
													header('Location: delete_class.php');
													exit();
												}
											}
											else
												throw new Exception($connect->error);
										}	
										else if($_POST['delete_or_archive']=='delete')
										{
											if($id_users=$connect->query("SELECT id FROM users WHERE id_klasy='".$_POST['class_type']."' "))
											{
												if($id_users->num_rows>0)
												{
													$help_variable=0;
													while($id_users_results=$id_users->fetch_row())
													{
														if($connect->query("DELETE FROM results WHERE id_users='".$id_users_results[0]."' "))
															;
														else
															throw new Exception($connect->error);
														$help_variable++;
														if($help_variable==$id_users->num_rows)
														{
															$_SESSION['delete_ok']=$_POST['class_type'];
															header('Location: delete_class.php');
															exit();
														}
													}
													
												}
												else
												{
													$_SESSION['delete_ok']=$_POST['class_type'];
													header('Location: delete_class.php');
													exit();
												}
											}
											else
												throw new Exception($connect->error);
										}
										else
											echo '<p>Błąd! Wybrano złe działanie!</p>';
									}
									else
										echo '<p>Nie wybrano jaką akcję podjąć dla uczniów!</p>';
								}
							}
							else
								echo '<p>Nie wybrano klasy!</p>';
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