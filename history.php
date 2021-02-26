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

	<section class="history">
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

						if($history=$connect->query("SELECT * FROM results WHERE id_users='".$_SESSION['which_user_id']."' "))
						{
							if($history->num_rows>0)
							{
								echo '<div class="table-responsive"><div class="searched_question"><h3>Historia testów</h3>';
								$i=0;
								while($history_results=$history->fetch_assoc())
								{
									echo '
									<div class="this_question">
										<div class="content_question">
											Przedmiot: '.$history_results['exam_category'].'<br>
											Zakres materiału: '.$history_results['comment'].'<br>
											Data testu: '.$history_results['date'].'<br>
										</div>
										<div class="info_question">
											<div class="info_question_answer">
												Wynik: '.$history_results['score'].'%<br>
												Ocena: '.$history_results['mark'].'<br>
												Ilość pytań: '.$history_results['count_question'].'<br>
												Dodatkowe % na start: '.$history_results['extra_points'].'<br>
												Mnożnik punktów: '.$history_results['multipler_points'].'<br>
											</div>
											<div class="change_question">
												Kryteria oceniania: <input type="button" class="editing hidding" data-toggle="collapse" data-target="#norm'.$i.'" value="Pokaż">
												<div id="norm'.$i.'" class="collapse" style="margin-top: 10px;">
												<div class="table-responsive"><table class="table table-bordered">
												<tr><th colspan="2">Kryteria ocen</th></tr>
												<tr><td>2</td><td>'.$history_results['grade_2'].'%</td></tr>
												<tr><td>3</td><td>'.$history_results['grade_3'].'%</td></tr>
												<tr><td>4</td><td>'.$history_results['grade_4'].'%</td></tr>
												<tr><td>5</td><td>'.$history_results['grade_5'].'%</td></tr>
												<tr><td>6</td><td>'.$history_results['grade_6'].'%</td></tr>
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
								echo '<p>Nie przeprowadzono żadnych testów.</p>';
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

	<div class="kryteria"></div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script>
function pokaz(i)
{
	$(".history").css("opacity", "0.5");
	cookies = document.cookie.split(/; */);
	alert(cookies[i])
	$(".kryteria").html(cookies[i]);
	$(".kryteria").css({"visibility":"visible","opacity":"1"});
}
function zamknij(i)
{
	$(".history").css("opacity", "1");
	$("#"+i).css("visibility", "hidden");
}
</script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->