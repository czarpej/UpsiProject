<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

if(isset($_SESSION['test_now']))
{
	header('Location: test.php');
	exit();
}

if(isset($_SESSION['score_now']))
{
	header('Location: score.php');
	exit();
}

$_SESSION['user_now']=true;
?>

<!DOCTYPE html>
<html>
<head>
	<title>UPSI 2.1 <?php if(isset($_SESSION['which_user'])) {echo $_SESSION['which_user'];} ?> </title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type='text/css' href='fontello/css/fontello.css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/background_user.css">

	<script src="js/jquery-3.3.1.min.js"></script>

</head>
<body>

	<?php
	require_once 'menu_user.php';
	echo $menu_user;
	?>

	<section class='user'>
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
						throw new Exception($connect->connect_errno());
					else
					{

						$connect->query("SET CHARSET utf8");
						$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

						if($actual_test=$connect->query("SELECT * FROM actual_test WHERE id_class='".$_SESSION['which_user_class']."' "))
						{
							if($actual_test->num_rows>0)
							{
								$actual_test_results=$actual_test->fetch_assoc();
								if($actual_test_results['exam_type']=='all')
								{
									$actual_test_results['exam_type']='Super Combo';
									$actual_test_results['test_type']='Super Combo';
								}

								echo '<form method="post" action="random.php">
									<div class="table-responsive"><table class="table table-bordered"><thead><tr><th colspan="2">Ustawiony test</th></tr></thead><tbody>
									<tr><td>Przedmiot</td><td>'.$actual_test_results['exam_type'].'</td></tr>
									<tr><td>Zakres materiału</td><td>'.$actual_test_results['test_type'].'</td></tr>
									<tr><td>Ilość pytań</td><td>'.$actual_test_results['count_question'].'</td></tr>
									<tr><td>Ilość sekund na pytanie</td><td>'.$actual_test_results['time_on_question'].'</td></tr>
									<tr><td>Dodatkowe % na start</td><td>'.$actual_test_results['extra_points'].'</td></tr>
									<tr><td>Mnożnik punktów</td><td>'.$actual_test_results['multipler_points'].'</td></tr>
									</tbody></table></div>
									<div class="table-responsive"><table class="table table-bordered"><thead><tr><th colspan="2">Kryteria oceniania</th></tr></thead>';
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
									echo '</tbody></table></div>
									<p><button name="start_test" class="editing">Rozpocznij test <i class="icon-play"></i></button></p>
									</form>';
							}
							else
								echo '<p>Brak ustawionego testu.</p>';
						}
						else
							throw new Exception($connect->error);

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