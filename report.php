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
					<h3>Raport ocen</h3>

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
								header('Location: report.php');
								exit();
							}

							function class_connect_error()
							{
								unset($_SESSION['subject_type']);
								$_SESSION['connect_error']='error';
								forwarding();
							}

							if(isset($_POST['student_class']))
							{
								unset($_SESSION['subject_type']);
								unset($_SESSION['class_type']);
							}

							echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#dla_klas">Dla klas <i class="icon-users"></i></button><br>
							<div class="collapse" id="dla_klas">
							<form method="post" id="class_form">
							<fieldset><legend>Dla klas</legend><p><div class="table-responsive"><table class="table">
							<tr><td><label for="subject_type">Wybierz przedmiot: </label></td>
							<td>';
							if($subject=$connect->query("SELECT DISTINCT exam_category FROM results"))
							{
								if($subject->num_rows>0)
								{
									$_SESSION['exam_results']=Array();
									$i=0;
									echo '<select name="subject_type" id="subject_type"><option selected disabled hidden style="display: none" value=""> -- wybierz przedmiot -- </option>';
									while($subject_results=$subject->fetch_row())
									{
										echo '<option value="'.$subject_results[0].'">'.$subject_results[0].'</option>';
										$_SESSION['exam_results'][$i]=$subject_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
									echo 'Nie znaleziono przedmiotów w systemie';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr>
							<tr><td>
							<label for="class_type">Wybierz klasę: </label></td><td id="for_subject">';

							function echo_class_for_class($class, $connect)
							{
								if($class->num_rows>0)
								{
									$_SESSION['class_for_class']=Array();
									$i=0;
									echo '<select name="class_type" id="class_type"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
									while($class_results=$class->fetch_row())
									{
										if($which_class=$connect->query("SELECT * FROM classes WHERE id='".$class_results[0]."' "))
										{
											if($which_class->num_rows>0)
											{
												$which_class_results=$which_class->fetch_assoc();
												echo '<option value="'.$which_class_results['id'].'">'.$which_class_results['class'].''.$which_class_results['section'].'</option>';
												$_SESSION['class_for_class'][$i]=$which_class_results['id'];
												$i++;
											}
											else
												class_connect_error();
										}
										else
											class_connect_error();
									}
									if($class->num_rows>=2)
									{
										echo '<option value="all">Wszyscy</option>';
										$_SESSION['class_for_class'][$i]='all';
									}
									echo '</select>';
								}
								else
								{
									$_SESSION['error']='<p>Nie znaleziono żadnych klas dla tego przedmiotu!</p>';
									unset($_SESSION['subject_type']);
									forwarding();
								}
							}

							if(isset($_POST['subject_type']))
							{
								unset($_SESSION['student_class']);
								$exam_ok=false;
								foreach($_SESSION['exam_results'] as $value)
								{
									if($value==$_POST['subject_type'])
									{
										$exam_ok=true;
										break;
									}
								}
								unset($_SESSION['exam_results']);
								if($exam_ok==false)
								{
									$_SESSION['error']='<p>Wybrany przedmiot jest niezgodny z rezultatami wyszukań!</p>';
									forwarding();
								}
								else
								{
									$_SESSION['subject_type']=$_POST['subject_type'];
									if($class=$connect->query("SELECT DISTINCT `users`.`id_klasy` FROM `results`, `users` WHERE `users`.`id`=`results`.`id_users` AND `results`.`exam_category`='".$_SESSION['subject_type']."' "))
										echo_class_for_class($class, $connect);
									else
										class_connect_error();
								}
							}
							else if(isset($_SESSION['subject_type']))
							{
								if($class=$connect->query("SELECT DISTINCT `users`.`id_klasy` FROM `results`, `users` WHERE `users`.`id`=`results`.`id_users` AND `results`.`exam_category`='".$_SESSION['subject_type']."' "))
									echo_class_for_class($class, $connect);
								else
									class_connect_error();
							}
							else
								echo 'Najpierw wybierz przedmiot';
							echo '</td></tr><tr><td><label for="date_test">Wybierz datę testu:</label></td><td id="for_class">';

							function echo_date_for_class($date_test)
							{
								if($date_test->num_rows>0)
								{
									$_SESSION['date_test']=Array();
									$i=0;
									echo '<select name="date_test" id="date_test"><option selected disabled hidden style="display: none" value=""> -- wybierz datę -- </option>';
									while($date_test_results=$date_test->fetch_row())
									{
										echo '<option value="'.$date_test_results[0].'">'.$date_test_results[0].'</option>';
										$_SESSION['date_test'][$i]=$date_test_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
								{
									$_SESSION['error']='<p>Brak testów!</p>';
									unset($_SESSION['subject_type']);
									forwarding();
								}
							}

							if(isset($_POST['class_type']))
							{
								$class_ok=false;
								foreach($_SESSION['class_for_class'] as $value)
								{
									if($value==$_POST['class_type'])
									{
										$class_ok=true;
										break;
									}
								}
								unset($_SESSION['class_for_class']);
								if($class_ok==false)
								{
									unset($_SESSION['subject_type']);
									$_SESSION['error']='<p>Wybrany przedmiot jest niezgodny z rezultatami wyszukań!</p>';
									forwarding();
								}
								else
								{
									$_SESSION['class_type']=$_POST['class_type'];
									if($_POST['class_type']=='all')
									{
										if($date_test=$connect->query("SELECT DISTINCT `date` FROM `results` WHERE `exam_category`='".$_SESSION['subject_type']."' "))
											echo_date_for_class($date_test);
										else
											class_connect_error();
									}
									else
									{
										if($date_test=$connect->query("SELECT DISTINCT `date` FROM `results`, `users` WHERE `users`.`id_klasy`='".$_SESSION['class_type']."' AND `results`.`exam_category`='".$_SESSION['subject_type']."' AND `users`.`id`=`results`.`id_users` "))
											echo_date_for_class($date_test);
										else
											class_connect_error();
									}
								}
							}
							else if(isset($_SESSION['class_type']))
							{
								if($_SESSION['class_type']=='all')
								{
									if($date_test=$connect->query("SELECT DISTINCT `date` FROM `results` WHERE `exam_category`='".$_SESSION['subject_type']."' "))
										echo_date_for_class($date_test);
									else
										class_connect_error();
								}
								else
								{
									if($date_test=$connect->query("SELECT DISTINCT `date` FROM `results`, `users` WHERE `users`.`klasa`='".$_SESSION['id_klasy']."' AND `results`.`exam_category`='".$_SESSION['subject_type']."' AND `users`.`id`=`results`.`id_users` "))
										echo_date_for_class($date_test);
									else
										class_connect_error();
								}
							}
							else
								echo 'Najpierw wybierz klasę';
							echo '</td></tr></table></div>
							<p><button class="editing" name="search_date">Wyszukaj <i class="icon-search"></i></button></p>
							</p></fieldset></form></div>';

							echo '
							<script>
							$("#class_type").bind("change", function () {
							    $("#class_form").submit();
							});
							$("#subject_type").bind("change", function () {
							    $("#class_form").submit();
							});
							</script>
							';

							function student_connect_error()
							{
								unset($_SESSION['student_class']);
								$_SESSION['connect_error']='error';
								forwarding();
							}

							echo  '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#dla_ucznia">Dla ucznia <i class="icon-user"></i></button><br>
							<div class="collapse" id="dla_ucznia"><form method="post" id="student_form"><fieldset><legend>Dla ucznia</legend><p>
							<div class="table-responsive"><table class="table"><tr><td><label for="student_class">Klasa:</label></td><td>';
							if($class=$connect->query("SELECT DISTINCT * FROM classes WHERE section!='admin' AND section!='Administratorzy'"))
							{
								if($class->num_rows>0)
								{
									$_SESSION['class_student']=Array();
									$i=0;
									echo '<select name="student_class" id="student_class"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
									while($class_results=$class->fetch_assoc())
									{
										echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
										$_SESSION['class_student'][$i]=$class_results['id'];
										$i++;
									}
									echo '</select>';
								}
								else
									echo 'Nie istnieją żadne klasy w systemie';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr><tr><td><label for="student">Uczeń:</label></td><td id="for_mark">';

							function echo_user_for_user($student)
							{
								if($student->num_rows>0)
								{
									$_SESSION['student']=Array();
									$_SESSION['data']=Array();
									$i=0;
									echo '<select name="student" id="student"><option selected disabled hidden style="display: none" value=""> -- wybierz ucznia -- </option>';
									while($student_results=$student->fetch_assoc())
									{
										echo '<option value="'.$student_results['id'].'">'.$student_results['login'].'</option>';
										$_SESSION['student'][$i]=$student_results['id'];
										$_SESSION['data'][$i]=$student_results['login'];
										$i++;
									}
									echo '</select>';
								}
								else
								{
									$_SESSION['error']='<p>Brak uczniów w tej klasie.</p>';
									unset($_SESSION['student_class']);
									forwarding();
								}
							}

							if(isset($_POST['student_class']))
							{
								unset($_SESSION['subject_type']);
								unset($_SESSION['class_type']);
								$class_ok=false;
								foreach($_SESSION['class_student'] as $value)
								{
									if($value==$_POST['student_class'])
									{
										$class_ok=true;
										break;
									}
								}
								unset($_SESSION['class_student']);
								if($class_ok==false)
								{
									$_SESSION['error']='<p>Wybrana klasa nie jest zgodna z rezultatami wyszukań!</p>';
									forwarding();
								}
								else
								{
									$_SESSION['student_class']=$_POST['student_class'];
									if($student=$connect->query("SELECT id, login FROM users WHERE id_klasy='".$_POST['student_class']."' "))
										echo_user_for_user($student);
									else
										student_connect_error();
								}
							}
							else if(isset($_SESSION['student_class']))
							{
								if($student=$connect->query("SELECT id, login FROM users WHERE id_klasy='".$_SESSION['student_class']."' "))
									echo_user_for_user($student);
								else
									student_connect_error();
							}
							else
								echo 'Najpierw wybierz klasę';
							echo '</td></tr></table></div>
							<p><button class="editing" name="mark_student">Wyszukaj <i class="icon-search"></i></button></p>
							</p></fieldset></form></div>';

							echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#dla_daty">Dla konkretnej daty <i class="icon-calendar"></i></button><br>
							<div class="collapse" id="dla_daty"><form method="post"><p><fieldset><legend>Dla konkretnej daty</legend>
							<div class="table-responsive"><table class="table">
							<tr><td><label for="date">Wybierz datę:</label></td><td>';
							if($date=$connect->query("SELECT DISTINCT `date` FROM `results`"))
							{
								if($date->num_rows>0)
								{
									$_SESSION['date']=Array();
									$i=0;
									echo '<select name="date" id="date"><option selected disabled hidden style="display: none" value=""> -- wybierz datę -- </option>';
									while($date_results=$date->fetch_row())
									{
										echo '<option value="'.$date_results[0].'">'.$date_results[0].'</option>';
										$_SESSION['date'][$i]=$date_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
									echo 'Nie znaleziono żadnych testów';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr></table></div>
							<p><button class="editing" name="for_date">Wyszukaj <i class="icon-search"></i></button></p>
							</fieldset></p></form></div>';

							echo '
							<script>
							$("#student_class").bind("change", function () {
							    $("#student_form").submit();
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
						echo '<div class="col-12">'.$_SESSION['error'].'</div>';
						unset($_SESSION['error']);
					}

					function echo_results($report, $option, $login)
					{
						if($report->num_rows>0)
						{
							$i=0;
							echo '<div class="table-responsive"><div class="searched_question">';
							switch($option)
							{
								case 1: echo '<h3>Testy z dnia '.$_POST['date_test'].':</h3>'; break;
								case 3: echo '<h3>Testy z dnia '.$_POST['date'].':</h3>'; break;
								case 2: echo '<h3>Testy użytkownika '.$login.':</h3>'; break;
							}
							while($report_results=$report->fetch_assoc())
							{
								echo '
								<div class="this_question">
									<div class="content_question">';
										if($option==1 || $option==3)
										{
											echo 'Użytkownik: '.$report_results['login'].'<br>
											Klasa: '.$report_results['class'].''.$report_results['section'].'<br>';
										}
										echo 'Przedmiot: '.$report_results['exam_category'].'<br>
										Zakres materiału: '.$report_results['comment'].'<br>';
										if($option==2)
											echo 'Data testu: '.$report_results['date'].'<br>';
										echo '
									</div>
									<div class="info_question">
										<div class="info_question_answer">
											Wynik: '.$report_results['score'].'%<br>
											Ocena: '.$report_results['mark'].'<br>
											Ilość pytań: '.$report_results['count_question'].'<br>
											Dodatkowe % na start: '.$report_results['extra_points'].'<br>
											Mnożnik punktów: '.$report_results['multipler_points'].'<br>
										</div>
										<div class="change_question">
											Kryteria oceniania: <input type="button" class="editing hidding" data-toggle="collapse" data-target="#norm'.$i.'" value="Pokaż">
											<div id="norm'.$i.'" class="collapse" style="margin-top: 10px;">
											<div class="table-responsive"><table class="table table-bordered">
											<tr><th colspan="2">Kryteria ocen</th></tr>
											<tr><td>2</td><td>'.$report_results['grade_2'].'%</td></tr>
											<tr><td>3</td><td>'.$report_results['grade_3'].'%</td></tr>
											<tr><td>4</td><td>'.$report_results['grade_4'].'%</td></tr>
											<tr><td>5</td><td>'.$report_results['grade_5'].'%</td></tr>
											<tr><td>6</td><td>'.$report_results['grade_6'].'%</td></tr>
											</table></div>
											</div>
										</div>
									</div>
								</div>';
								$i++;
							}
							echo '</div></div>';
						}
						else
							echo '<p>Nie znaleziono w bazie zapisanych testów.</p>';
					}

					if(isset($_POST['search_date']))
					{
						echo '<div class="col-12">';
						if(isset($_POST['date_test']))
						{
							$date_ok=false;
							for($i=0; $i<count($_SESSION['date_test']); $i++)
							{
								if($_SESSION['date_test'][$i]==$_POST['date_test'])
									$date_ok=true;
							}
							unset($_SESSION['date_test']);
							if($date_ok==false)
							{
								unset($_SESSION['class_type']);
								unset($_SESSION['subject_type']);
								echo'<p>Wybrana data jest niezgodna z rezultatami wyszukań!<p>';
							}
							else
							{
								if($_SESSION['class_type']=='all')
								{
									if($report=$connect->query("SELECT `users`.`login`, `results`.`exam_category`, `results`.`comment`, `results`.`score`, `results`.`mark`, `results`.`count_question`, `results`.`extra_points`, `results`.`multipler_points`, `results`.`date`, `results`.`grade_2`, `results`.`grade_3`, `results`.`grade_4`, `results`.`grade_5`, `results`.`grade_6`, `classes`.`class`, `classes`.`section`, `classes`.`id` FROM `classes`, `results`, `users` WHERE `classes`.`id`=`users`.`id_klasy` AND `users`.`id`=`results`.`id_users` AND `results`.`exam_category`='".$_SESSION['subject_type']."' AND `results`.`date`='".$_POST['date_test']."' "))
										echo_results($report, 1, '');
									else
										throw new Exception($connect->error);
								}
								else
								{
									if($report=$connect->query("SELECT `users`.`login`, `results`.`exam_category`, `results`.`comment`, `results`.`score`, `results`.`mark`, `results`.`count_question`, `results`.`extra_points`, `results`.`multipler_points`, `results`.`date`, `results`.`grade_2`, `results`.`grade_3`, `results`.`grade_4`, `results`.`grade_5`, `results`.`grade_6`, `classes`.`class`, `classes`.`section` FROM `results`, `users`, `classes` WHERE `users`.`id`=`results`.`id_users` AND `results`.`exam_category`='".$_SESSION['subject_type']."' AND `results`.`date`='".$_POST['date_test']."' AND `users`.`id_klasy`='".$_SESSION['class_type']."' AND `classes`.`id`='".$_SESSION['class_type']."' "))
										echo_results($report, 1, '');
									else
										throw new Exception($connect->error);
								}
							}
							unset($_SESSION['subject_type']);
							unset($_SESSION['class_type']);
							echo '
							<script>
							$("#for_class").html("Najpierw wybierz klasę");
							$("#for_subject").html("Najpierw wybierz przedmiot");
							</script>
							';
						}
						else
							echo '<p>Nie wybrano daty!</p>';
						echo '</div>';
					}

					if(isset($_POST['mark_student']))
					{
						echo '<div class="col-12">';
						if(isset($_POST['student']))
						{
							$student_ok=false;
							$login='';
							for($i=0; $i<count($_SESSION['student']); $i++)
							{
								if($_SESSION['student'][$i]==$_POST['student'])
								{
									$student_ok=true;
									$login=$_SESSION['data'][$i];
									break;
								}
							}
							unset($_SESSION['student_class']);
							if($student_ok==false)
							{
								echo '<p>Wybrany uczeń jest niezgodny z rezultatami wyszukań!</p>';
								unset($_SESSION['student']);
							}
							else
							{
								if($tests=$connect->query("SELECT DISTINCT `users`.`login`, `results`.`exam_category`, `results`.`comment`, `results`.`score`, `results`.`mark`, `results`.`count_question`, `results`.`extra_points`, `results`.`multipler_points`, `results`.`date`, `results`.`grade_2`, `results`.`grade_3`, `results`.`grade_4`, `results`.`grade_5`, `results`.`grade_6` FROM `users`, `results` WHERE `results`.`id_users`='".$_POST['student']."' AND `users`.`id`=`results`.`id_users` "))
									echo_results($tests, 2, $login);
								else
									throw new Exception($connect->error);
							}
							echo '
							<script>
							$("#for_mark").html("Najpierw wybierz klasę");
							</script>
							';
						}
						else
							echo '<p>Nie wybrano ucznia!</p>';
						echo "</div>";
						unset($_SESSION['student']);
					}

					if(isset($_POST['for_date']))
					{
						echo '<div class="col-12"><div class="table-responsive">';
						if(isset($_POST['date']))
						{
							$date_ok=false;
							for($i=0; $i<count($_SESSION['date']); $i++)
							{
								if($_SESSION['date'][$i]==$_POST['date'])
									$date_ok=true;
							}
							if($date_ok==false)
							{
								echo '<p>Wybrana data jst niezgodna z rezultatami wyszukań!</p>';
							}
							else
							{
								if($test_date=$connect->query("SELECT `results`.`exam_category`, `results`.`comment`, `results`.`score`, `results`.`mark`, `results`.`count_question`, `results`.`extra_points`, `results`.`multipler_points`, `results`.`date`, `users`.`login`, `results`.`grade_2`, `results`.`grade_3`, `results`.`grade_4`, `results`.`grade_5`, `results`.`grade_6`, `classes`.`class`, `classes`.`section`, `classes`.`id` FROM `results`, `users`, `classes` WHERE `date`='".$_POST['date']."' AND `users`.`id`=`results`.`id_users` AND `classes`.`id`=`users`.`id_klasy` ORDER BY `users`.`id_klasy` ASC "))
									echo_results($test_date, 3, true);
								else
									throw new Exception($connect->error);
							}
						}
						else
							echo 'Nie wybrano daty!';
						echo '</div>';
						unset($_SESSION['date']);
					}

					if(isset($_SESSION['subject_type']))
					{
						echo '
							<script>
							$("#subject_type").val("'.$_SESSION['subject_type'].'");
							$("#dla_klas").css("display", "block");
							</script>';
					}
					if(isset($_SESSION['class_type']))
					{
							echo '
							<script>
							$("#class_type").val("'.$_SESSION['class_type'].'");
							</script>';
					}
					if(isset($_SESSION['student_class']))
					{
						echo '
							<script>
							$("#student_class").val("'.$_SESSION['student_class'].'");
							$("#dla_ucznia").css("display", "block");
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