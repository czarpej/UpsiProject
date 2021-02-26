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
	<script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="js/scroll_to_id_users.js"></script>

</head>
<body>

	<?php
	require_once 'menu_user.php';
	echo $menu_user;
	?>

	<section class='reviews'>
	<div class='container'>

		<div class='row'>
			
			<div class='col-12'>

				<h3>Propozycje ulepszeń</h3>

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

						echo '<p>W ramach rozwoju projektu możesz przesyłać opinie oraz uwagi odnośnie błędów, problemów, które zauważyłeś w trakcie użytkowania.</p><p>Jeżeli masz pomysły, jak ulepszyć użytkowanie, działanie, wygląd - napisz. W odpowiedniej zakładce możesz także przesłać swoje projekty.</p>';
						echo '<form method="post" action="reviews_actions.php">
						<textarea class="feedback" name="proposition" placeholder="Tutaj możesz wpisać swoje opinie na forum.."></textarea>
						<p><button name="add" class="editing">Prześlij <i class="icon-publish"></i></button></p>
						</form>';

						echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#forum">Forum <i class="icon-users"></i></button><br>
						<div class="collapse" id="forum"><div class="forum"><h4>Forum</h4>';
						if($reviews=$connect->query("SELECT `reviews`.`review`, `reviews`.`date`, `users`.`login`, `classes`.`class`, `classes`.`section`, `classes`.`year_started` FROM reviews, users, classes WHERE `reviews`.`id_users`=`users`.`id` AND `users`.`id_klasy`=`classes`.`id`"))
						{
							if($reviews->num_rows>0)
							{
								while($reviews_results=$reviews->fetch_assoc())
								{
									echo '<div class="post">
										<div class="user_info">';
											if($reviews_results['section']=='admin')
												echo 'Administrator główny: '.$reviews_results['login'].'<br>';
											else if($reviews_results['section']=='Administratorzy')
												echo 'Administrator: '.$reviews_results['login'].'<br>';
											else
											{
												echo 'Użytkownik: '.$reviews_results['login'].'<br>
												Klasa: '.$reviews_results['class'].''.$reviews_results['section'].'<br>
												Data dołączenia: '.$reviews_results['year_started'].'<br>';
											}
											echo '</div>
										<div class="reviews">
											'.$reviews_results['review'].'
											<hr>
											<div class="info_time">Data opublikowania: '.$reviews_results['date'].'</div>
										</div>
									</div>';
								}
							}
							else
								echo '<p>Jeszcze nikt się nie wypowiedział. Bądź pierwszy!</p>';
						}
						echo '<div class="reviews" style="width:100%; text-align:center;"><input type="button" class="editing hidding" value="Odpowiedz" data-toggle="collapse" data-target="#comment"><br>
						<div class="collapse" id="comment"><fieldset><legend>Wypowiedź na forum</legend><form method="post" action="reviews_actions.php">
						<textarea class="feedback" name="proposition" placeholder="Wypowiedz się"></textarea>
						<p><button name="add" class="editing">Opublikuj <i class="icon-publish"></i></button></p>
						</form></fieldset></div></div>
						</div></div>';

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

						if(isset($_SESSION['add_info']))
						{
							echo '<div class="col-12" id="users">'.$_SESSION['add_info'].'</div>';
							unset($_SESSION['add_info']);
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

	</div>
	</section>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/width_fieldset.js"></script>
<script src="js/auto_size_textarea.js"></script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->