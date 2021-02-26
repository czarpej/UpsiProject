<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

if(!isset($_SESSION['which_class']))
{
	header('Location: admin.php');
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
	<script>
		function grades(i)
		{
			var grade=$("#grade"+i).val();
			document.cookie='grade'+i+'='+grade;
		}

		function option(i)
		{
			var option=$("#option"+i).val();
			document.cookie='option'+i+'='+option;
		}

		function question()
		{
			var question=$("#count_question").val();
			document.cookie='question='+question;
		}
	</script>


</head>
<body>

	<nav class="navbar navbar-dark navbar-expand">
		<ol class="navbar-nav">
			<li class="nav-item"><a class="nav-link"><form action="edit_test_action.php" method="post"><button name="canel"><i class="icon-cancel-circled"></i> Anuluj</button></form></a></li>
			<li class="nav-item"><a class="nav-link"><form action='logout.php' method='post'><button name='logout'><i class="icon-logout"></i> Wyloguj</button></form></a></li>
		</ol>
	</nav>

	<section class='edit_test'>
	<div class='container'>

		<div class='row'>
			<div class='col-12'>
				<h2>Testy dla uczniów</h2>

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

						echo '<p><div class="table-responsive"><table class="table table-bordered">
						<tr><th colspan="2">';
						//test for which class
						if($_SESSION['which_class']!='new_test')
						{
							if($class=$connect->query("SELECT * FROM classes WHERE id='".$_SESSION['which_class']."' "))
							{
								if($class->num_rows>0)
								{
									$class_results=$class->fetch_assoc();
									echo 'Ustaw test dla klasy: '.$class_results['class'].''.$class_results['section'];
								}
								else
									echo 'Nie znaleziono żadnej klasy.';
							}
							else
								throw new Exception($connect->error);
						}
						else
						{
							if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy'"))
							{
								if($class->num_rows>0)
								{
									$_SESSION['class_to_test']=Array();
									$i=0;
									echo '<form method="post" id="which_class"><label for="class">Ustaw test dla klasy:</label> <select name="class" id="class" style="width: auto;"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
									while($class_results=$class->fetch_assoc())
									{
										echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
										$_SESSION['class_to_test'][$i]=$class_results['id'];
										$i++;
									}
									echo "</select></form>";
								}
								else
									echo 'Nie znaleziono żadnej klasy.';
							}
							else
								throw new Exception($connect->error);
						}
						if(isset($_POST['class']))
						{
							$class_ok=false;
							foreach($_SESSION['class_to_test'] as $value)
							{
								if($value==$_POST['class'])
								{
									$class_ok=true;
									break;
								}
							}
							unset($_SESSION['class_to_test']);
							if($class_ok==false)
							{
								$_SESSION['error']='<p>Wybrana klasa jest niezgodna z rezultatami wyszukań!</p>';
								header('Location: edit_test.php');
								exit();
							}
							else
								$_SESSION['class']=$_POST['class'];
						}
						
						echo '</th>
						<tr><td><label for="exam_type">Przedmiot</label></td><td><form method="post" id="exam_form">';
						if($exam_type=$connect->query("SELECT DISTINCT exam_category FROM question"))
						{
							if($exam_type->num_rows>0)
							{
								$_SESSION['exam']=Array();
								$i=0;
								echo '<select name="exam_type" id="exam_type"><option selected disabled hidden style="display: none" value=""> -- przedmiot -- </option>';
								while($exam_type_results=$exam_type->fetch_row())
								{
									echo '<option value="'.$exam_type_results[0].'">'.$exam_type_results[0].'</option>';
									$_SESSION['exam'][$i]=$exam_type_results[0];
									$i++;
								}
								if($exam_type->num_rows>=2)
								{
									echo '<option value="all">Super Combo</option></select>';
									$_SESSION['exam'][$i]='all';
								}
							}
							else
								echo 'Nie znaleziono przedmiotów.';
						}
						else
							throw new Exception($connect->error);
						echo '</form></td></tr>
						<tr><td><label for="test_type">Zakres materiału</label></td><td id="for_test_type"><form method="post" id="test_form">';
						if(isset($_POST['exam_type']))
						{
							$exam_ok=false;
							foreach($_SESSION['exam'] as $value)
							{
								if($value==$_POST['exam_type'])
								{
									$exam_ok=true;
									break;
								}
							}
							unset($_SESSION['exam']);
							if($exam_ok==false)
							{
								$_SESSION['error']='<p>Wybrany przedmiot jest niezgodny z rezultatami wyszukań!</p>';
								header('Location: edit_test.php');
								exit();
							}
							else
							{
								$_SESSION['exam_type']=$_POST['exam_type'];
								if(isset($_SESSION['test_type']))
								{
									unset($_SESSION['test_type']);
									unset($_POST['test_type']);
								}

								if($_POST['exam_type']=='all')
								{
									$_SESSION['category']=Array();
									$_SESSION['category'][0]='all';
									echo '<select name="test_type" id="test_type"><option selected disabled hidden style="display: none" value=""> </option><option value="all">Super Combo</option></select>';
								}
								else
								{
									if($test_type=$connect->query("SELECT DISTINCT test_category FROM question WHERE exam_category='".$_POST['exam_type']."' "))
									{
										if($test_type->num_rows>0)
										{
											$_SESSION['category']=Array();
											$i=0;
											echo '<select name="test_type" id="test_type"><option selected disabled hidden style="display: none" value=""> -- materiał -- </option>';
											while($test_type_results=$test_type->fetch_row())
											{
												echo '<option value="'.$test_type_results[0].'">'.$test_type_results[0].'</option>';
												$_SESSION['category'][$i]=$test_type_results[0];
												$i++;
											}
											if($test_type->num_rows>=2)
											{
												echo '<option value="Combo">Combo</option></select>';
												$_SESSION['category'][$i]='Combo';
											}
										}
										else
											echo 'Nie znaleziono zakresów tego materiału.';
									}
									else
										throw new Exception($connect->error);
								}
							}
						}
						else if(isset($_SESSION['exam_type']))
						{
							if($_SESSION['exam_type']=='all')
							{
								$_SESSION['category']=Array();
								$_SESSION['category'][0]='all';
								echo '<select name="test_type" id="test_type"><option selected disabled hidden style="display: none" value=""> -- materiał -- </option><option value="all">Super Combo</option></select>';
							}
							else
							{
								if($test_type=$connect->query("SELECT DISTINCT test_category FROM question WHERE exam_category='".$_SESSION['exam_type']."' "))
								{
									if($test_type->num_rows>0)
									{
										$_SESSION['category']=Array();
										$i=0;
										echo '<select name="test_type" id="test_type"><option selected disabled hidden style="display: none" value=""> -- materiał -- </option>';
										while($test_type_results=$test_type->fetch_row())
										{
											echo '<option value="'.$test_type_results[0].'">'.$test_type_results[0].'</option>';
											$_SESSION['category'][$i]=$test_type_results[0];
											$i++;
										}
										if($test_type->num_rows>=2)
										{
											echo '<option value="Combo">Combo</option></select>';
											$_SESSION['category'][$i]='Combo';
										}
									}
									else
										echo 'Nie znaleziono zakresów tego materiału.';
								}
								else
									throw new Exception($connect->error);
							}
						}
						else
							echo 'Najpierw wybierz przedmiot.';
						echo '</form></td></tr>
						<tr><td><label for="count_question">Ilość pytań</label></td><form action="edit_test_action.php" method="post"><td>';

						function question($count_question)
						{
							$count_question_results=$count_question->fetch_row();
							$i=0;
							$_SESSION['count_question']=Array();
							echo '<select id="count_question" name="count_question" id="count_question" onchange="question()"><option selected disabled hidden style="display: none" value=""> -- ilość pytań -- </option>';
							while($i<$count_question_results[0])
							{
								$i+=5;
								if($i>$count_question_results[0])
								{
									echo '<option>'.$count_question_results[0].'</option>';
									$_SESSION['count_question'][$i]=$count_question_results[0];
								}
								else
								{
									echo '<option>'.$i.'</option>';
									$_SESSION['count_question'][$i]=$i;
								}
							}
							echo '</select>';
						}

						if(isset($_POST['test_type']))
						{
							$category_ok=false;
							foreach($_SESSION['category'] as $value)
							{
								if($value==$_POST['test_type'])
								{
									$category_ok=true;
									break;
								}
							}
							unset($_SESSION['category']);
							if($category_ok==false)
							{
								$_SESSION['error']='<p>Wybrany zakres materiału jest niezgodny z rezultatami wyszukań!</p>';
								header('Location: edit_test.php');
								exit();
							}
							else
							{
								$_SESSION['test_type']=$_POST['test_type'];
								if(isset($_SESSION['exam_type']))
									unset($_POST['exam_type']);
								if($_POST['test_type']=='all')
								{
									if($count_question=$connect->query("SELECT COUNT('id') FROM question"))
										question($count_question);
									else
										throw new Exception($connect->error);
								}
								else if($_POST['test_type']=='Combo')
								{
									if($count_question=$connect->query("SELECT COUNT('id') FROM question WHERE exam_category='".$_SESSION['exam_type']."' "))
										question($count_question);
									else
										throw new Exception($connect->error);
								}
								else
								{
									if($count_question=$connect->query("SELECT COUNT('id') FROM question WHERE exam_category='".$_SESSION['exam_type']."' AND test_category='".$_POST['test_type']."' "))
										question($count_question);
									else
										throw new Exception($connect->error);
								}
							}

						}
						else if(isset($_SESSION['test_type']))
						{
							if($_SESSION['test_type']=='all')
							{
								if($count_question=$connect->query("SELECT COUNT('id') FROM question"))
									question($count_question);
								else
									throw new Exception($connect->error);
							}
							else if($_SESSION['test_type']=='Combo')
							{
								if($count_question=$connect->query("SELECT COUNT('id') FROM question WHERE exam_category='".$_SESSION['exam_type']."' "))
									question($count_question);
								else
									throw new Exception($connect->error);
							}
							else
							{
								if($count_question=$connect->query("SELECT COUNT('id') FROM question WHERE exam_category='".$_SESSION['exam_type']."' AND test_category='".$_SESSION['test_type']."' "))
									question($count_question);
								else
									throw new Exception($connect->error);
							}
						}
						else
							echo 'Najpierw wybierz zakres materiału.';

						echo '</td></tr>
						<tr><td><label for="option1">Ilość sekund na pytanie</label></td><td><input type="number" id="option1" onchange="option(1)" name="time_on_question" min="1" max="100" value="30"></td></tr>
						<tr><td><label for="option2">Dodatkowe % na start</label></td><td><input type="number" id="option2" onchange="option(2)" name="extra_points" value="0" min="0" max="50" step="any"></td></tr>
						<tr><td><label for="option3">Mnożnik punktów</label></td><td><input type="number" id="option3" onchange="option(3)" name="multipler_points" value="1" min="0.5" max="3" step="any"></td></tr>
						</table></div>
						<div class="table-responsive"><table class="table table-bordered">
						<tr><th colspan="2">Kryteria oceniania</th></tr>';
						function cookies($i)
						{
							$grade='';
							if($i==2)
								$grade=50;
							else if($i==3)
								$grade=60;
							else if($i==4)
								$grade=75;
							else if($i==5)
								$grade=90;
							else if($i==6)
								$grade=100;
							echo '<tr><td><label for="grade'.$i.'">'.$i.'</label></td><td><input type="number" name="grade'.$i.'" id="grade'.$i.'" min="1" max="100" onchange="grades('.$i.')" value="'.$grade.'"> %</td></tr>';
						}
						if($_SESSION['which_class']!='new_test')
						{
							if($grade_norm=$connect->query("SELECT * FROM grade_norm WHERE id_class='".$_SESSION['which_class']."' "))
							{
								if($grade_norm->num_rows>0)
								{
									$grade_norm_results=$grade_norm->fetch_row();
									for($i=2; $i<=6; $i++)
										echo '<tr><td><label for="grade'.$i.'">'.$i.'</label></td><td><input type="number" name="grade'.$i.'" id="grade'.$i.'" onchange="grades('.$i.')" min="1" max="100" value="'.$grade_norm_results[$i-1].'"> %</td></tr>';	
								}
								else
								{
									for($i=2; $i<=6; $i++)
										cookies($i);
								}
							}
							else
								throw new Exception($connect->error);
						}
						else
						{
							for($i=2; $i<=6; $i++)
							{
								cookies($i);
							}
						}
						echo '</table></div>
						<p><button name="change" class="editing">Zatwierdź <i class="icon-ok"></i></button></p>
						</form>
						</p>';

						echo '
						<script>
						$("#class").bind("change", function () {
						    $("#which_class").submit();
						});
						$("#exam_type").bind("change", function () {
						    $("#exam_form").submit();
						});
						$("#test_type").bind("change", function () {
						    $("#test_form").submit();
						});
						</script>';

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

			if(isset($_SESSION['class']))
			{
				echo '<script>
				$("#class").val("'.$_SESSION['class'].'");
				</script>';
			}

			if(isset($_SESSION['exam_type']))
			{
				echo '<script>
				$("#exam_type").val("'.$_SESSION['exam_type'].'");
				</script>';
			}

			if(isset($_SESSION['test_type']))
			{
				echo '<script>
				$("#test_type").val("'.$_SESSION['test_type'].'");
				</script>';
			}

			if(isset($_COOKIE['question']))
			{
				echo '<script>
				$("#count_question").val("'.$_COOKIE['question'].'");
				</script>';
			}

			for($i=1; $i<=6; $i++)
			{
				if(isset($_COOKIE['grade'.$i]))
				{
					echo '<script>
					$("#grade'.$i.'").val("'.$_COOKIE['grade'.$i].'");
					</script>';
				}

				if(isset($_COOKIE['option'.$i]))
				{
					echo '<script>
					$("#option'.$i.'").val("'.$_COOKIE['option'.$i].'");
					</script>';
				}
			}
			?>

		</div>

	</div>
</section>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->