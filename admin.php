<?php
session_start();

require_once 'dbconnect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

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

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

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
						<h3>Testy dla uczniów</h3>

						<?php

						try
						{
							$connect=new mysqli($address, $db_login, $db_password, $db_name);
							if($connect->connect_errno!=0)
								throw new Exception($connect->connect_errno());
							else
							{	
								$connect->query("SET CHARSET utf8");
								$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

								if($classes_system=$connect->query("SELECT id FROM classes"))
								{
									if($classes_system->num_rows>0)
									{
										if($actual_test=$connect->query("SELECT * FROM actual_test"))
										{
											if($actual_test->num_rows>0)
											{
												while($actual_test_results=$actual_test->fetch_assoc())
												{
													if($class=$connect->query("SELECT * FROM classes WHERE id='".$actual_test_results['id_class']."' "))
													{
														if($class->num_rows>0)
														{
															$class_results=$class->fetch_assoc();
															if($actual_test_results['exam_type']=='all')
															{
																$actual_test_results['exam_type']="Super Combo";
																$actual_test_results['test_type']="Super Combo";
															}
															echo '<input type="button" class="editing hidding" data-toggle="collapse" data-target="#'.$actual_test_results['id_class'].'" value="'.$class_results['class'].''.$class_results['section'].'"><br>';
															echo '<div class="collapse" id="'.$actual_test_results['id_class'].'"><fieldset><legend>'.$class_results['class'].''.$class_results['section'].'</legend><form method="post" action="new_test.php">
															<div class="table-responsive">
															<input type="hidden" name="class" value="'.$actual_test_results['id_class'].'">
															<table class="table table-bordered">
															<tr><th colspan="2">Ustawiony test</th></tr>
															<tr><td>Przedmiot</td><td>'.$actual_test_results['exam_type'].'</td></tr>
															<tr><td>Zakres materiału</td><td>'.$actual_test_results['test_type'].'</td></tr>
															<tr><td>Ilość pytań</td><td>'.$actual_test_results['count_question'].'</td></tr>
															<tr><td>Ilość sekund na pytanie</td><td>'.$actual_test_results['time_on_question'].'</td></tr>
															<tr><td>Dodatkowe % na start</td><td>'.$actual_test_results['extra_points'].'</td></tr>
															<tr><td>Mnożnik punktów</td><td>'.$actual_test_results['multipler_points'].'</td></tr>
															</table></div>
															<div class="table-responsive">
															<table class="table table-bordered">
															<tr><th colspan="2">Kryteria oceniania</th></tr>';
															if($grade_norm=$connect->query("SELECT * FROM grade_norm WHERE id_class='".$actual_test_results['id_class']."' "))
															{
																if($grade_norm->num_rows>0)
																{
																	$grade_norm_results=$grade_norm->fetch_row();
																	for($i=2; $i<=6; $i++)
																		echo '<tr><td>'.$i.'</td><td>'.$grade_norm_results[$i-1].'%</td></tr>';
																}
																else
																{
																	echo '<tr><td>2</td><td>50%</td></tr>
																		<tr><td>3</td><td>65%</td></tr>
																		<tr><td>4</td><td>75%</td></tr>
																		<tr><td>5</td><td>90%</td></tr>
																		<tr><td>6</td><td>100%</td></tr>';
																}
															}
															else
																throw new Exception($connect->error);												
															echo '</table></div>
															<p><button class="editing" name="change_test">Zmień <i class="icon-edit"></i></button> <button class="editing" name="delete_test">Usuń <i class="icon-trash-empty"></i></button></p>
															</form></fieldset></div>';
														}
														else
															throw new Exception($connect->error);
													}
													else
														throw new Exception($connect->error);
												}
											}
											else
												echo '<p>Brak ustawionych testów w bazie danych. <form method="post" action="new_test.php"><input type="submit" name="new_test" class="editing" value="Ustaw test"></form></p>';
										}
										else
											throw new Exception($connect->error);
									}
									else
										echo '<p>Brak klas w systemie. <form method="post" action="add_class.php"><input type="submit" class="editing" value="Dodaj klasę"></form></p>';
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
					if(isset($_SESSION['delete_ok']))
					{
						echo '<div class="col-12">'.$_SESSION['delete_ok'].'</div>';
						unset($_SESSION['delete_ok']);
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