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

	<section class='your_files'>
	<div class='container'>

		<div class='row'>
			
			<div class='col-12'>

				<h3>Twoje pliki</h3>

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

						echo '<p>W ramach rozwoju projektu możesz przesyłać swoje własne lub zmodyfikowane pliki, które chcesz zobaczyć jako funkcjonujące zamiast oryginalnych.</p>';
						if(!file_exists('new_files/'.$_SESSION['which_user']))
							mkdir('new_files/'.$_SESSION['which_user']);
						if(!file_exists('new_files/'.$_SESSION['which_user'].'/file_temp'))
							mkdir('new_files/'.$_SESSION['which_user'].'/file_temp');

						echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#your_files">Przesłane pliki <i class="icon-docs"></i></button><br>';
						echo '<div class="collapse" id="your_files"><fieldset><legend>Przesłane pliki</legend>';
						$files=glob('new_files/'.$_SESSION['which_user'].'/*.*');
						if(empty($files))
							echo '<p>Brak przesłanych plików z twojej strony.</p>';
						else
						{
							echo '<div class="table-responsive"><table class="table table-bordered">
							<tr><th>Lp.</th><th>Plik</th>';
							$i=0;
							foreach($files as $file) 
							{
							    echo '<tr><td>'.($i+1).'</td><td><a href="'.$file.'" download>'.substr($file, (strlen($_SESSION['which_user'])+11)).'</a></td><tr>';
							    $i++;
							}
							echo '</table></div>';
						}
						echo '</fieldset></div>';

						echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#upload_file">Przesyłanie pliku <i class="icon-doc-add"></i></button>
						<div class="collapse" id="upload_file" style="text-align: left;"><fieldset><legend>Przesyłanie pliku</legend><form method="post" enctype="multipart/form-data">
						<p>W celu łatwiejszej identyfikacji i testowania przesłanych plików prosimy o nadanie im nazwy odpowiadające ich stronom, np. <b>report.php</b>. Dobrze byłoby dopisać do nazwy pliku nazwisko autora, aby łatwiej można było się z nim skontaktować oraz dopisać go w późniejszych fazach projektu do autorów.</p>
						<center><p><input type="file" name="your_file" accept=".jpg, .jpeg, .png, .php, .html, .js, .json, .hjs, .css, .txt"></p>
						<button name="add" class="editing">Prześlij <i class="icon-upload"></i></button></center>
						<p>Pamiętaj! Jeżeli nie podoba ci się jakieś wspólne konto np. czarnota, utwórz własne korzystając z konta administratora!</p>
						</form></fieldset></div>';

						?>

				</div>

						<?php

						if(isset($_POST['cancel']))
						{
							echo '<div class="col-12" id="users">';
							if(unlink('new_files/'.$_SESSION['which_user'].'/file_temp/'.$_SESSION['your_file']))
								echo '<p>Anulowano nadpisywanie pliku.</p>';
							else
								throw new Exception($connect->error);
							echo '</div>';
							unset($_SESSION['your_file']);
						}

						if(isset($_POST['overwrite']))
						{
							echo '<div class="col-12" id="users">';
							if((copy('new_files/'.$_SESSION['which_user'].'/file_temp/'.$_SESSION['your_file'], 'new_files/'.$_SESSION['which_user'].'/'.$_SESSION['your_file'])) && (unlink('new_files/'.$_SESSION['which_user'].'/file_temp/'.$_SESSION['your_file'])))
								echo '<p>Pomyślnie nadpisano plik.</p>';
							else
								echo '<p>Wystąpił błąd! Nie udało się nadpisać pliku.</p>';
							echo '</div>';
							unset($_SESSION['your_file']);
						}

						if(isset($_POST['add']))
						{
							echo '<div class="col-12" id="users">';
							if(!isset($_FILES['your_file']))
								echo '<p>Nie przesłano żadnego pliku!</p>';
							else if($_FILES['your_file']['error'] > 0)
							{
								switch ($_FILES['your_file']['error'])
								{
								  // jest większy niż domyślny maksymalny rozmiar,
								  // podany w pliku konfiguracyjnym
								  case 1: {echo '<p>Rozmiar pliku jest zbyt duży!</p>'; break;}

								  // jest większy niż wartość pola formularza
								  // MAX_FILE_SIZE
								  case 2: {echo '<p>Rozmiar pliku jest zbyt duży!</p>'; break;}

								  // plik nie został wysłany w całości
								  case 3: {echo '<p>Plik wysłany tylko częściowo!</p>'; break;}

								  // plik nie został wysłany
								  case 4: {echo '<p>Nie wysłano żadnego pliku!</p>'; break;}

								  //zaginiony folder tymczasowy
								  case 6: {echo '<p>Brak tymczasowego folderu przechowywania plików!</p>'; break;}

								  //błąd zapisu na dysku
								  case 7: {echo '<p>Nie udało się zapisać pliku na serwerze, brak uprawnień do zapisu!</p>'; break;}

								  //zbyt długi czas zapisu
								  case 8: {echo '<p>Nie udało się zapisać pliku na dysku, zbyt długi czas zapisu.</p>'; break;}

								  // pozostałe błędy
								  default: {echo '<p>Wystąpił błąd podczas wysyłania pliku!</p>'; break;}
								}
							}
							else
							{
								if(file_exists('new_files/'.$_SESSION['which_user'].'/'.$_FILES['your_file']['name']))
								{
									if(move_uploaded_file($_FILES['your_file']['tmp_name'], 'new_files/'.$_SESSION['which_user'].'/file_temp/'.$_FILES['your_file']['name']))
									{
										echo '<form method="post">
										<p>Plik o nazwie "'.$_FILES['your_file']['name'].'" już istnieje. Nadpisać?</p>
										<p><input type="submit" name="overwrite" class="editing" value="Nadpisz"></p><p><input type="submit" name="cancel" class="editing" value="Anuluj"></p>
										</form>';
										$_SESSION['your_file']=$_FILES['your_file']['name'];
									}
									else
										echo '<p>Wystąpił błąd podczas wysyłania pliku!</p>';
								}
								else
								{
									if(is_uploaded_file($_FILES['your_file']['tmp_name']))
									{
										if(!move_uploaded_file($_FILES['your_file']['tmp_name'], 'new_files/'.$_SESSION['which_user'].'/'.$_FILES['your_file']['name']))
										 	echo '<p>Nie udało się skopiować pliku na serwer.</p>';
										else
											echo '<p>Pomyślnie przesłano plik.</p>';
									}
									else
										echo '<p>Możliwy atak podczas przesyłania pliku! Plik nie został zapisany.</p>';
								}
							}
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

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->