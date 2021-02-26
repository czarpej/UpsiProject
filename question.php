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
		function ans(i)
		{
			var ans=$("#ans_"+i).val();
			document.cookie="ans_"+i+"="+ans;
		}

		function new_subject(value)
		{
			object='';
			if(value==1)
			{
				object='new_subject';
				value=$("#to_new_subject").val();
			}
			else if(value==2)
			{
				object='new_material';
				value=$("#material_new").val();
			}
			document.cookie=object+"="+value;
		}

		function show_picture(i)
		{
			var picture=$("#hidden"+i).val();
			$(".show_picture").css({"opacity":"1", "z-index":"1"});
			$("#image").html('<img src="img/img_to_question/'+picture+'" alt="Nie znaleziono obrazka na serwerze!">');
			$(".question_page").css({"opacity":"0.4", "z-index":"-1"});
			$(".question_page *").prop("disabled", true).off('click');
			//window.scrollTo({ top: 0, behavior: 'smooth' });
		}

		function close_picture()
		{
			$(".show_picture").css({"opacity":"0", "z-index":"-1"});
			$(".question_page").css({"opacity":"1", "z-index":"0"});
			$(".question_page *").prop("disabled", false).off('click');
		}
	</script>

</head>
<body>

	<?php
	require_once 'menu_admin.php';
	echo $menu_admin;
	?>

	<div class="show_picture">
		<p><span id="image"></span></p>
		<input type="button" value="Zamknij" class="editing" onclick="close_picture()">
	</div>

	<section class='question_page'>
	<div class='container'>
		<div class='row'>

				<div class='col-12'>
					<h3>Zarządzanie bazą pytań</h3>

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

							if(isset($_SESSION['exists_file']))
							{
								echo '<div class="info_to_save">
									<form method="post" action="question_actions.php">
										<p>Plik o podanej nazwie już istnieje w systemie. Jest to:</p>
											<img src="img/img_to_question/'.$_SESSION['optional_image'].'">
										<p>Nadpisać?</p>
										<p><span class="version"> </span></p>
										<p><input type="submit" name="cancel" value="Anuluj" class="editing"></p>
									</form>
								</div>';
								echo $_SESSION['exists_file'];
							}

							function forwarding()
							{
								header('Location: question.php');
								exit();
							}

							function show_connect_error()
							{
								unset($_SESSION['exam_from_question']);
								$_SESSION['connect_error']='error';
								forwarding();
							}

							echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#show_question">Przeglądanie pytań <i class="icon-book-open"></i></button><br>
							<div class="collapse" id="show_question">
							<fieldset><legend>Przeglądanie pytań</legend><div class="table-responsive"><table class="table">
							<tr><td><label for="exam">Przedmiot:</label></td><td>';
							if($exam=$connect->query("SELECT DISTINCT exam_category FROM question"))
							{
								if($exam->num_rows>0)
								{
									echo '<form method="post" id="exam_form"><select name="exam" id="exam"><option selected disabled hidden style="display: none;" value=""> -- wybierz przedmiot -- </option>';
									$_SESSION['to_exam_category']=Array();
									$i=0;
									while($exam_results=$exam->fetch_row())
									{
										echo '<option value="'.$exam_results[0].'">'.$exam_results[0].'</option>';
										$_SESSION['to_exam_category'][$i]=$exam_results[0];
										$i++;
									}
									echo '</select></form>';
								}
								else
									echo 'Brak pytań w systemie.';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr>
							<form method="post"><tr><td><label for="category">Zakres materiału:</label></td><td id="for_material">';

							function exam($category)
							{
								if($category->num_rows>0)
								{
									echo '<select name="category" id="category"><option selected disabled hidden style="display: none" value=""> -- wybierz zakres materiału -- </option>';
									$_SESSION['category_from_question']=Array();
									$i=0;
									while($category_results=$category->fetch_row())
									{
										echo '<option value="'.$category_results[0].'">'.$category_results[0].'</option>';
										$_SESSION['category_from_question'][$i]=$category_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
								{
									unset($_SESSION['exam_from_question']);
									$_SESSION['error']='<p>Błąd! Nie odnaleziono pytań w systemie!</p>';
									forwarding();
								}
							}

							if(isset($_POST['exam']))
							{
								unset($_SESSION['subject_to_add_question']);
								$exam_ok=false;
								foreach($_SESSION['to_exam_category'] as $value)
								{
									if($value==$_POST['exam'])
									{
										$exam_ok=true;
										break;
									}
								}
								if($exam_ok==false)
								{
									$_SESSION['error']='<p>Wybrany przedmiot jest niezgodny z rezultatami wyszukań!</p>';
									forwarding();
								}
								else
								{
									$_SESSION['exam_from_question']=$_POST['exam'];
									if($category=$connect->query("SELECT DISTINCT test_category FROM question WHERE exam_category='".$_SESSION['exam_from_question']."' "))
										exam($category);
									else
										show_connect_error();
								}
							}
							else if(isset($_SESSION['exam_from_question']))
							{
								if($category=$connect->query("SELECT DISTINCT test_category FROM question WHERE exam_category='".$_SESSION['exam_from_question']."'"))
									exam($category);
								else
									show_connect_error();
							}
							else
								echo 'Najpierw wybierz przedmiot.';
							echo '</td></tr></table>
							<p><button name="search" class="editing">Wyszukaj <i class="icon-search"></i></button></p>
							</form></div></fieldset>
							</div>';

							function add_connect_error()
							{
								unset($_SESSION['subject_to_add_question']);
								$_SESSION['connect_error']='error';
								forwarding();
							}

							echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#add_question">Dodawanie pytania <i class="icon-plus"></i></button>
							<div class="collapse" id="add_question">
							<fieldset><legend>Dodawanie pytania</legend><div class="table-responsive"><table class="table">
							<tr><td><label for="subject">Przedmiot</label><span style="margin-left: 7px;"><input type="checkbox" id="new_subject" value="new_subject"><label for="new_subject"> (nowy)</label></span></td><td id="actual_subject">';
							if($actual_subject=$connect->query("SELECT DISTINCT exam_category FROM question"))
							{
								if($actual_subject->num_rows>0)
								{
									echo '<form method="post" id="subject_form"><select name="subject" id="subject"><option selected disabled hidden style="display: none" value=""> -- wybierz przedmiot -- </option>';
									$_SESSION['for_subject']=Array();
									$i=0;
									while($actual_subject_results=$actual_subject->fetch_row())
									{
										echo '<option value="'.$actual_subject_results[0].'">'.$actual_subject_results[0].'</option>';
										$_SESSION['for_subject'][$i]=$actual_subject_results[0];
										$i++;
									}
									echo '</select></form>';
								}
								else
									echo 'Brak pytań w systemie.';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr>
							<form method="post" enctype="multipart/form-data" action="question_actions.php">
							<tr><td><label for="material">Zakres materiału</label> <span style="margin-left: 7px;"><input type="checkbox" id="new_material" value="val"><label for="new_material"> (nowy)</label></span></td><td id="actual_material">';

							function subject_do($actual_material)
							{
								if($actual_material->num_rows>0)
								{
									echo '<select name="material" id="material"><option selected disabled hidden style="display: none" value=""> -- wybierz zakres materiału -- </option>';
									$_SESSION['for_material']=Array();
									$i=0;
									while($actual_material_results=$actual_material->fetch_row())
									{
										echo '<option value="'.$actual_material_results[0].'">'.$actual_material_results[0].'</option>';
										$_SESSION['for_material'][$i]=$actual_material_results[0];
										$i++;
									}
									echo '</select>';
								}
								else
								{
									unset($_SESSION['subject_to_add_question']);
									$_SESSION['error']='<p>Błąd! Pytania z tego przedmiotu nie mają przypisanego materiału.</p>';
									forwarding();
								}
							}

							if(isset($_POST['subject']))
							{
								unset($_SESSION['exam_from_question']);
								$subject_ok=false;
								foreach($_SESSION['for_subject'] as $value)
								{
									if($value==$_POST['subject'])
									{
										$subject_ok=true;
										break;
									}
								}
								if($subject_ok==false)
								{
									$_SESSION['error']='<p>Wybrany przedmiot jest niezgodny z rezultatami wyszukań!</p>';
									forwarding();
								}
								else
								{
									$_SESSION['subject_to_add_question']=$_POST['subject'];
									if($actual_material=$connect->query("SELECT DISTINCT test_category FROM question WHERE exam_category='".$_SESSION['subject_to_add_question']."' "))
										subject_do($actual_material);
									else
										add_connect_error();
								}
							}
							else if(isset($_SESSION['subject_to_add_question']))
							{
								if($actual_material=$connect->query("SELECT DISTINCT test_category FROM question WHERE exam_category='".$_SESSION['subject_to_add_question']."' "))
									subject_do($actual_material);
								else
									add_connect_error();
							}
							else
								echo 'Najpierw wybierz przedmiot.';
							echo '</td></tr>
							<tr><td><label for="question">Treść pytania</label></td><td><textarea name="ans_0" id="ans_0" onchange="ans(0)" placeholder="Treść pytania oraz odpowiedzi zostaną zapamiętane do momentu poprawnego dodania ich do bazy pytań."></textarea></td></tr>
							<tr><td><label for="optional_image">Opcjonalny obrazek</label></td><td><input type="file" id="optional_image" name="optional_image" accept="image/png, image/jpeg"></td></tr>';
							for($i=65; $i<=68; $i++)
								echo '<tr><td><label for="ans_'.$i.'">Odpowiedź '.chr($i).'</label></td><td><textarea name="ans_'.$i.'" id="ans_'.$i.'" onchange="ans('.$i.')"></textarea></td></tr>';
							echo '<tr><td><label for="ans_good">Poprawne odpowiedzi</label></td><td>';
							for($i=65; $i<=68; $i++)
								echo '<input type="checkbox" id="ans'.$i.'" name="ans'.$i.'" value="'.chr($i).'" class="good_ans"><label for="ans'.$i.'">'.chr($i).'</label>';
							echo '</td></tr>
							</table>
							<p><button name="add" class="editing">Dodaj <i class="icon-plus"></i></button></p>
							</form></div></fieldset>
							</div>

							<form method="post">
							<button class="editing editing_button" name="show_image">Zapisane obrazki <i class="icon-art-gallery"></i></button>
							</form>';

							echo '
							<script>
							$("#exam").bind("change", function () {
							    $("#exam_form").submit();
							});
							$("#subject").bind("change", function () {
							    $("#subject_form").submit();
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

					if(isset($_SESSION['adding_ok']))
					{
						echo '<div class="col-12" id="users">'.$_SESSION['adding_ok'].'</div>';
						unset($_SESSION['adding_ok']);
					}

					if(isset($_POST['search']))
					{
						unset($_SESSION['subject_to_add_question']);
						echo '<div class="col-12" id="users">';
						if(!isset($_POST['category']) || $_POST['category']=='')
							echo '<p>Nie wybrano zakresu materiału!</p>';
						else
						{
							$category_ok=false;
							foreach($_SESSION['category_from_question'] as $value)
							{
								if($value==$_POST['category'])
								{
									$category_ok=true;
									break;
								}
							}
							if($category_ok==false)
								echo '<p>Wybrany zakres materiału jest niezgodny z rezultatami wyszukań!</p>';
							else
							{
								if(@$question=$connect->query("SELECT * FROM question WHERE exam_category='".$_SESSION['exam_from_question']."' AND test_category='".$_POST['category']."' "))
								{
									if($question->num_rows>0)
									{
										$_SESSION['which_question']=1;
										echo '<form method="post" action="question_actions.php"><div class="table-responsive">
										<div class="searched_question">
										<h3>Pytania z przedmiotu "'.$_SESSION['exam_from_question'].'" z zakresu "'.$_POST['category'].'":</h3>';
										while($question_results=$question->fetch_assoc())
										{
											echo '<div class="this_question">';
											$image='';
											$hidden='';
											if($question_results['image']!='')
											{
												$image='Obrazek:<input type="button" id="image'.$_SESSION['which_question'].'" class="editing" value="Pokaż" onclick="show_picture('.$_SESSION['which_question'].')">';
												$hidden='<input type="hidden" value="'.$question_results['image'].'" id="hidden'.$_SESSION['which_question'].'">';
											}
											echo '
											<div class="content_question">'.$question_results['question'].'</div>
												<div class="info_question">
													<div class="info_question_answer">
													Odpowiedzi:
													<ol class="info_question_answers">
														<li>'.$question_results['ans_a'].'</li>
														<li>'.$question_results['ans_b'].'</li>
														<li>'.$question_results['ans_c'].'</li>
														<li>'.$question_results['ans_d'].'</li>
													</ol>
													Poprawne odpowiedzi: '.$question_results['ans_good'].'
													</div>
													<div class="change_question">
														Zazncz do usunięcia <input type="checkbox" name="object'.$_SESSION['which_question'].'" value="'.$question_results['id_question'].'"><br>
														<p><button type="button" class="editing hidding" data-toggle="collapse" data-target="#change'.$_SESSION['which_question'].'">Edytuj <i class="icon-edit"></i></button></p>
														'.$image.''.$hidden.'
													</div>
												</div>
											</div>
											<div id="change'.$_SESSION['which_question'].'" class="collapse edit_question">
												<form method="post" enctype="multipart/form-data" action="question_actions.php"><div class="table-responsive"><table class="table table-bordered">
												<tr><th colspan="2">Zmiana pytania</th></tr>
												<tr><td><label for="edit_ans_0">Treść pytania</label></td><td><textarea id="edit_ans_0" name="edit_ans_0">'.$question_results['question'].'</textarea></td></tr>
												<tr><td><label for="edit_optional_image">Opcjonalny obrazek</label> (brak wybranego pliku zachowa obecny)</td><td><input type="file" id="edit_optional_image" name="edit_optional_image" accept="image/png, image/jpeg"><input type="hidden" name="old_file" value="'.$question_results['image'].'"> <input type="checkbox" name="delete_image" value="delete_image" class="good_ans"> (Usuń obecny)</td></tr>';
												for($j=65; $j<=68; $j++)
													echo '<tr><td><label for="edit_ans_'.strtolower(chr($j)).'">Odpowiedź '.chr($j).'</label></td><td><textarea id="edit_ans_'.strtolower(chr($j)).'" name="edit_ans_'.strtolower(chr($j)).'">'.$question_results['ans_'.strtolower(chr($j))].'</textarea></td></tr>';
												echo '<tr><td>Poprawne odpowiedzi</td><td>';
												for($j=65; $j<=68; $j++)
													echo '<input type="checkbox" id="edit_ans'.strtolower(chr($j)).'" name="edit_ans'.strtolower(chr($j)).'" value="'.chr($j).'" class="good_ans"><label for="edit_ans'.strtolower(chr($j)).'">'.chr($j).'</label>';
												echo '</table>
												<input type="hidden" name="question_id" value="'.$question_results['id_question'].'">
												<button name="change" class="editing">Zmień <i class="icon-ok"></i></button>
												</div></form>
											</div>';
											$_SESSION['which_question']++;
										}
										echo '</div>
										<p><button name="delete" class="editing">Usuń wybrane <i class="icon-trash-empty"></i></button></p>
										</div></form>';
									}
									else
										echo '<p>Błąd! Nie znaleziono pytań z wybranych kategorii.</p>';
								}
								else
									throw new Exception($connect->error);
							}
						}
						echo '</div>';
						echo '<script>
							$("#for_material").html("Najpierw wybierz przedmiot");
							</script>';
					}

					if(isset($_POST['show_image']))
					{
						unset($_SESSION['subject_to_add_question']);
						unset($_SESSION['exam_from_question']);
						echo '<div class="col-12">';
						$png = glob("img/img_to_question/*.png");
						$jpg = glob("img/img_to_question/*.jpg");

						$i=1;
						foreach($png as $image) {
							if($i%3==1)
								echo '<div style="display: flex;">';
						    echo '<div class="server_image"><div class="image_question"><div class="zoom" onclick="show_picture('.$i.')"><i class="icon-zoom-in"></i></div><img src="'.$image.'"></div><br><div style="width:100%; word-wrap: break-word;"><input type="hidden" id="hidden'.$i.'" value="'.substr($image, 20).'">'.substr($image, 20).'</div></div>';
						    if($i%3==0)
						    	echo '</div>';
						    $i++;
						}
						foreach($jpg as $image) {
							if($i%3==1)
								echo '<div style="display: flex;">';
						    echo '<div class="server_image"><div class="image_question"><div class="zoom" onclick="show_picture('.$i.')"><i class="icon-zoom-in"></i></div><img src="'.$image.'"></div><br><div style="width:100%; word-wrap: break-word;"><input type="hidden" id="hidden'.$i.'" value="'.substr($image, 20).'">'.substr($image, 20).'</div></div>';
						    if($i%3==0)
						    	echo '</div>';
						    $i++;
						}
						echo '</div>';
					}

					if(isset($_SESSION['subject_to_add_question']))
					{
						echo '
							<script>
							$("#subject").val("'.$_SESSION['subject_to_add_question'].'");
							$("#add_question").css("display", "block");
							</script>';
					}

					if(isset($_SESSION['exam_from_question']) && !isset($_POST['search']))
					{
						echo '
							<script>
							$("#exam").val("'.$_SESSION['exam_from_question'].'");
							$("#show_question").css("display", "block");
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

			if(isset($_COOKIE['ans_0']))
			{
				echo '<script>
				$("#ans_0").val("'.$_COOKIE['ans_0'].'");
				</script>';
			}
			for($i=65; $i<=68; $i++)
			{
				if(isset($_COOKIE['ans_'.$i]))
				{
					echo '<script>
					$("#ans_'.$i.'").val("'.$_COOKIE['ans_'.$i].'");
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
<script src="js/width_fieldset.js"></script>
<script>
	var actual_subject=$("#actual_subject").html();
	var checked_subject=function() {
		if($("#new_subject:checked").val())
		{
			$("#actual_subject").html('<input type="text" name="new_subject" onchange="new_subject(1)" id="to_new_subject">');
			$("#actual_material").html('<input type="text" name="new_material" id="material_new" onchange="new_subject(2)">');
			$("#new_material").prop('disabled', true);
		}
		else
		{
			$("#actual_subject").html(actual_subject);
			$("#actual_material").html(actual_material);
			$("#new_material").prop('disabled', false);
			if($("#new_material:checked").val())
				$("#actual_material").html('<input type="text" name="new_material" id="material_new" onchange="new_subject(2)">');
		}
	};
	$("#new_subject").on("click", checked_subject);

	var actual_material=$("#actual_material").html();
	var checked_material=function() {
		if($("#new_material:checked").val())
			$("#actual_material").html('<input type="text" name="new_material" id="material_new" onchange="new_subject(2)">');
		else
			$("#actual_material").html(actual_material);
	};
	$("#new_material").on("click", checked_material);
</script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->