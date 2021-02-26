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
	<style>
	.table-bordered tr > td:nth-child(2)
	{
		text-align: left;
	}
	</style>

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
					<h3>Opinie użytkowników</h3>

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

							//echo '<a href="#" class="scrollup"></a>';

							echo '<input type="button" class="editing hidding" value="Wypowiedzi wybranego użytkownika" data-toggle="collapse" data-target="#select_user"><br>
							<div class="collapse" id="select_user"><div class="table-responsive"><form method="post"><fieldset><legend>Wypowiedzi wybranego użytkownika</legend><table class="table">
							<tr><td><label for="user">Wybierz ucznia:</label></td><td>';
							if($user=$connect->query("SELECT DISTINCT `reviews`.`id_users`, `users`.`login` FROM reviews, users WHERE `reviews`.`id_users`=`users`.`id`"))
							{
								if($user->num_rows>0)
								{
									echo '<select name="user" id="user"><option selected disabled hidden style="display: none" value=""> -- wybierz ucznia -- </option>';
									$_SESSION['users_from_reviews']=Array();
									$_SESSION['data']=Array();
									$i=0;
									while($user_results=$user->fetch_assoc())
									{
										echo '<option value="'.$user_results['id_users'].'">'.$user_results['login'].'</option>';
										$_SESSION['users_from_reviews'][$i]=$user_results['id_users'];
										$_SESSION['data'][$i]=$user_results['login'];
										$i++;
									}
									echo '</select>';
								}
								else
									echo 'Nie znaleziono żadnych opini.';
							}
							else
								throw new Exception($connect->error);
							echo '</td></tr></table>
							<p><button name="search" class="editing">Wyszukaj <i class="icon-search"></i></button></p>
							</fieldset></form></div></div>';

							echo '<button type="button" class="editing hidding" data-toggle="collapse" data-target="#forum">Forum <i class="icon-users"></i></button><br>
							<div class="collapse" id="forum">
							<div class="forum"><h4>Forum</h4>';
							if($reviews=$connect->query("SELECT `reviews`.`review`, `reviews`.`date`, `users`.`login`, `classes`.`class`, `classes`.`section`, `classes`.`year_started` FROM reviews, users, classes WHERE `reviews`.`id_users`=`users`.`id` AND `users`.`id_klasy`=`classes`.`id` ORDER BY `reviews`.`date` ASC"))
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
							<div class="collapse" id="comment"><fieldset><legend>Wypowiedź na forum</legend><form method="post" action="comment_actions.php">
							<textarea class="feedback" name="proposition" placeholder="Wypowiedz się"></textarea>
							<p><button name="add" class="editing">Opublikuj <i class="icon-publish"></i></button></p>
							</form></fieldset></div></div>';
							echo '</div></div>';

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

					if(isset($_POST['search']))
					{
						echo '<div class="col-12" id="users">';
						if(!isset($_POST['user']) || $_POST['user']=='')
							echo '<p>Nie wybrano użytkownika!</p>';
						else
						{
							$user_ok=false;
							$i=0;
							foreach($_SESSION['users_from_reviews'] as $value)
							{
								if($value==$_POST['user'])
								{
									$user_ok=true;
									break;
								}
								$i++;
							}
							if($user_ok==false)
								echo '<p>Wybrany użytkownik jest niezgodny z rezultatami wyszukań!</p>';
							else
							{
								if($reviews=$connect->query("SELECT review, `date` FROM reviews WHERE `reviews`.`id_users`='".$_POST['user']."' "))
								{
									if($reviews->num_rows>0)
									{
										echo '<h3>Opinie użytkownika "'.$_SESSION['data'][$i].'":</h3>
										<div class="table-responsive"><table class="table table-bordered">
										<tr><th>Data opublikowania:</th><th>Opinie</th></tr>';
										while($reviews_results=$reviews->fetch_assoc())
											echo '<tr><td>'.$reviews_results['date'].'</td><td>'.$reviews_results['review'].'</td></tr>';
										echo '</table></div>';
									}
									else
										throw new Exception($connect->error);
								}
								else
									throw new Exception($connect->error);
							}
						}
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
