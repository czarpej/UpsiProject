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

	<section class="add">
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
						throw new Exception($connect->connect_errno);
					else
					{
						$connect->query("SET CHARSET utf8");
						$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

						echo '
						<h2>Dodawanie użytkowników</h2>
						<input type="button" class="editing hidding" data-toggle="collapse" data-target="#pojedynczy" value="Dodanie pojedynczego użytkownika"><br>
						<div class="collapse" id="pojedynczy"><form method="post" action="add_actions.php">
						<fieldset><legend>Dodanie pojedynczego użytkownika</legend><p><div class="table-responsive"><table class="table ">
						<tr><td><label for="login">Nazwa użytkownika<label></td><td><input type="text" name="login" id="login"></td></tr>
						<tr><td><label for="class_type">Klasa</label></td><td>';
						if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy'"))
						{
							if($class->num_rows>0)
							{
								$_SESSION['default_class_user']=Array();
								$i=0;
								echo '<select name="class_type" id="class_type"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								while($class_results=$class->fetch_assoc())
								{
									echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
									$_SESSION['default_class_user'][$i]=$class_results['id'];
									$i++;
								}
								echo '</select>';
							}
							else
								echo 'Nie istnieją żadne klasy w systemie';
						}
						else
							throw new Exception($connect->error);
						echo '</td></tr>
						</table></div>
						<p><button class="editing" name="add">Dodaj <i class="icon-plus"></i></button></p>
						</p></fieldset>
						</form></div>
						';
						echo '
						<input type="button" class="editing hidding" data-toggle="collapse" data-target="#seryjnie" value="Seryjne dodawanie użytkowników"><br>
						<div class="collapse" id="seryjnie"><form method="post">
						<fieldset><legend>Seryjne dodawanie użytkowników</legend><p><div class="table-responsive"><table class="table ">
						<tr><td><label for="count_users">Ilość uczniów</label></td><td><input type="number" name="count_users" id="count_users"></td></tr>
						<tr><td><label for="default_class">Domyślna klasa</label></td><td>';
						if($class=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy'"))
						{
							if($class->num_rows>0)
							{
								$_SESSION['default_class']=Array();
								$i=0;
								echo '<select name="default_class" id="default_class"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								while($class_results=$class->fetch_assoc())
								{
									echo '<option>'.$class_results['class'].''.$class_results['section'].'</option><input type="hidden" name="id_class" value="'.$class_results['id'].'">';
									$_SESSION['default_class'][$i]=$class_results['class'].''.$class_results['section'];
									$i++;
								}
								echo '</select>';
							}
							else
								echo 'Nie istnieją żadne klasy w systemie';
						}
						else
							throw new Exception($connect->error);
						echo '</td></tr>
						</table></div>
						<p><button name="set" value="Ustaw" class="editing">Ustaw <i class="icon-ok"></i></button></p>
						</p></fieldset>
						</form></div>
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
							echo '<div class="col-12" id="users">'.$_SESSION['error'].'</div>';
							unset($_SESSION['error']);
						}

						if(isset($_SESSION['adding_ok']))
						{
							echo '<div class="col-12">';
							echo $_SESSION['adding_ok'];
							unset($_SESSION['adding_ok']);
							echo '</div>';
						}

						//draw form to add a few users
						if(isset($_POST['set']))
						{
							echo '<div class="col-12">
							<form method="post">
							<p>';
							$count_users=$_POST['count_users'];
							if($count_users=='')
								echo 'Nie podano liczby uczniów do wprowadzenia!';
							else if(!is_numeric($count_users))
								echo 'Wprowadzony ciąg znaków nie jest liczbą całkowitą dodatnią!';
							else if($count_users<=0)
								echo 'Liczba uczniów musi być dodatnia!';
							else if(!isset($_POST['default_class']))
								echo 'Nie wybrano domyślnej klasy!';
							else
							{
								$default_class_ok=false;
								for($i=0; $i<count($_SESSION['default_class']); $i++)
								{
									if($_SESSION['default_class'][$i]==$_POST['default_class'])
										$default_class_ok=true;
									else
									{
										$default_class_ok=false;
										break;
									}
								}
								unset($_SESSION['default_class']);
								if($default_class_ok==false)
									echo 'Wybrana klasa nie jest rezultatem wyszukania przez system!';
								else
								{
									$_SESSION['count_users']=$count_users;
									$default_class=$_POST['default_class'];
									echo '<div class="table-responsive"><table class="table table-bordered" id="users">
									<thead>
									<tr><th>Lp.</th><th>Nazwa użytkownika</th><th>Klasa</th></tr>
									</thead>
									<tbody>';
									$lp=1;
									for($i=0; $i<$count_users; $i++)
									{
										echo '<tr><td><label for="user'.$i.'">'.$lp.'</label></td><td><input type="text" id="user'.$i.'" name="user'.$i.'"></td><td>';
										$lp++;
										$class=$connect->query("SELECT * FROM classes");
										if($class->num_rows>0)
										{
											$_SESSION['default_class'.$i]=Array();
											$l=0;
											echo '<select name="default_class'.$i.'" id="default_class"><option selected hidden value="'.$_POST['id_class'].'">'.$default_class.'</option>';
											while($class_results=$class->fetch_assoc())
											{
												if($class_results['section']=='admin' || $class_results['section']=='Administratorzy')
													continue;
												echo '<option value="'.$class_results['id'].'">'.$class_results['class'].''.$class_results['section'].'</option>';
												$_SESSION['default_class'.$i][$l]=$class_results['id'];
												$l++;
											}
											echo '</select>';
										}
										else
											echo 'Nie istnieją żadne klasy w systemie';
										echo '</td></tr>';
									}
									echo '</tbody></table></div>
									<p><input type="submit" name="add_users" value="Dodaj użytkowników" class="editing"></p>';
								}
							}
							echo '</p></form></div>';
						}

						if(isset($_POST['add_users']))
						{
							echo '<div class="col-12">';
							$new_users=Array();
							$classess=Array();
							$how_users=0;
							$classess_ok=true;
							for($i=0; $i<$_SESSION['count_users']; $i++)
							{
								$new_users[$i]=$_POST['user'.$i];
								$classess[$i]=$_POST['default_class'.$i];

								$classess_ok=false;
								for($l=0; $l<count($_SESSION['default_class'.$i]); $l++)
								{
									if($_SESSION['default_class'.$i][$l]==$classess[$i])
										$classess_ok=true;
								}
								if($classess_ok==false)
									echo '<p>Błąd! Dokonano podmiany klasy w pozycji '.($i+1).'.</p>';
								else 
								{
									if($new_users[$i]!='')
									{
										if($connect->query("INSERT INTO users VALUES ('', '".$new_users[$i]."', '', '".$classess[$i]."', 0 ) "))
										{
											$how_users++;
										}
										else
											throw new Exception ($connect->error);
									}
								}
							}
							if($how_users>1)
							{
								echo '<p>Pomyślnie dodano '.$how_users.' użytkowników.</p>';
								unset($_SESSION['count_users']);
							}
							else if($how_users==1)
							{
								echo '<p>Pomyślnie dodano '.$how_users.' użytkownika.</p>';
								unset($_SESSION['count_users']);
							}
							else
								echo '<p>Nie dodano żadnych użytkowników.</p>';
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

		<?php
		if(isset($_POST['new_test']))
		{
			$_SESSION['which_class']='new_test';
			header('Location: edit_test.php');
			exit();
		}
		?>

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