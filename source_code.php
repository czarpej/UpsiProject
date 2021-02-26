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

	<style>
	fieldset .table tr:first-child > td 
	{
    	border: 1px solid white;
	}
	</style>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="js/scroll_to_id_users.js"></script>

</head>
<body>

	<?php
	require_once 'menu_user.php';
	echo $menu_user;
	?>

	<section class='source_code'>
	<div class='container'>

		<div class='row'>
			
			<div class='col-12'>

				<h3>Kod źródłowy</h3>

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

						echo '<p>W ramach rozwoju projektu możesz ściągnąć pliki PHP, aby móc przejrzeć kod źródłowy oraz zobaczyć jak to działa od strony serwera, a także uczyć się, a także modyfikować i nadsyłać swoje własne inspiracje.</p>';
						echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#how_to_do">Jak to zrobić? <i class="icon-help-circled"></i></button><br>
						<div class="collapse" id="how_to_do" style="text-align: left;">
						<fieldset><legend>Jak to zrobić?</legend>
							<p>Po pobraniu rozpakowaniu plików należy do prawidłowego działania zaimportować bazę danych, która także jest zawarta w tej paczce.</p>
							<p>W przypadku problemów oto kilka informacji:
							<ul>
								<li>kodowanie bazy danych domyślnie było ustawione na <i>utf8mb4_polish_ci</i>, w pliku sql zostało zmienione na <i>utf8_polish_ci</i></li>
								<li>baza danych nazywa się "<i>upsi</i>" i nie wymaga do zaimportowania istniejącej bazy danych o tej nazwie, jednak w przypadku błędów warto spróbować utworzyć nową pustą bazę danych</li>
								<li>responsywność powinna działać na wszystkich przeglądarkach, ewentualne problemy, rozjazdy wizualne mogą pojawić się na przeglądarkach nie wspierających nowszych implementacji technologii, jak stare wersje legendarnej przeglądarki <i>Internet Explorer</i></li>
								<li>w razie zauważenia błędów podziel się nimi na forum w zakładce <a href="reviews.php">Opinie użytkowników</a></li>
							</ul></p>
						</fieldset></div>
						<a href="upsi_project.zip" download><button type="button" class="editing">Paczka plików <i class="icon-file-archive"></i></button></a>';

						?>

				</div>

						<?php

						

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