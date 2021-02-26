<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['admin_log_now']=true;

if(isset($_SESSION['select_user']))
	unset($_SESSION['select_user']);

if(isset($_SESSION['which_class']))
{
	header('Location: edit_test.php');
	exit();
}

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
	<style>
	input[type="text"], input[type="number"]
	{
		width: 100%;
		max-width: 150px;
	}
	select
	{
		width: 100%;
		max-width: 150px;
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

	<section class='add_class'>
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
						<h2>Dodawanie klasy</h2>
						<form method="post">
						<p><div class="table-responsive one_option"><table class="table">
						<tr><td><label for="class">Klasa<label></td><td><input type="number" min="1" max="10" name="class" id="class"></td></tr>
						<tr><td><label for="section">Przydział</label></td><td><input type="text" name="section" id="section"></td></tr>
						<tr><td><label for="year">Rok rozpoczęcia</label></td><td><select name="year" id="year"><option selected hidden style="display: none" value="'.date('Y').'">'.date('Y').'</option>';
						$_SESSION['year_for_class']=Array();
						for($i=date('Y')-10; $i<=date('Y')+10; $i++)
						{
							$_SESSION['year_for_class'][$i]=$i;
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						echo '</select></td></tr>
						</table></div>
						<p><button class="editing" name="add_class">Dodaj <i class="icon-plus"></i></button></p>
						</p>
						</form>';
						
				?>				
				
			</div>

			<?php
						if(isset($_SESSION['add_class_ok']))
						{
							echo '<div class="col-12" id="users">'.$_SESSION['add_class_ok'].'</div>';
							unset($_SESSION['add_class_ok']);
						}

						if(isset($_POST['add_class']))
						{
							echo '<div class="col-12" id="users">';
							if(!isset($_POST['class']) || !isset($_POST['year']) || !isset($_POST['section']))
								echo '<p>Błąd, nie odnaleziono w formularzu wymaganych pól!</p>';
							else
							{
								$year_ok=false;
								foreach($_SESSION['year_for_class'] as $value)
								{
									if($value==$_POST['year'])
									{
										$year_ok=true;
										break;
									}
								}
								if($year_ok==false)
									echo '<p>Wybrany rok nie mieści się w podanym zakresie!</p>';
								else
								{
									if($_POST['class']=='' || $_POST['section']=='')
										echo '<p>Uzupełnij poprawnie wszystkie pola!</p>';
									else
									{
										if($actual_class=$connect->query("SELECT class, section FROM classes"))
										{
											if($actual_class->num_rows>0)
											{
												$class_ok=true;
												while($actual_class_results=$actual_class->fetch_assoc())
												{
													if($actual_class_results['class']==$_POST['class'] && $actual_class_results['section']==$_POST['section'])
													{
														$class_ok=false;
														break;
													}
												}
												if($class_ok==false)
													echo '<p>Wprowadzona klasa już istnieje w systemie!</p>';
												else
												{
													if($connect->query("INSERT INTO classes VALUES('', '".$_POST['class']."', '".$_POST['section']."', '".$_POST['year']."')"))
													{
														$_SESSION['add_class_ok']='<p>Pomyślnie dodano klasę do systemu.</p>';
														header('Location: add_class.php');
														exit();
													}
													else
														throw new Exception($connect->error);
												}
											}
											else
											{
												if($connect->query("INSERT INTO classes VALUES('', '".$_POST['class']."', '".$_POST['section']."', '".$_POST['year']."')"))
												{
													$_SESSION['add_class_ok']='<p>Pomyślnie dodano klasę do systemu.</p>';
													header('Location: add_class.php');
													exit();
												}
												else
													throw new Exception($connect->error);
											}
										}
										else
											throw new Exception($connect->error);
									}
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