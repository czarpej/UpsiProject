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

<section class='show_class'>
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
						<h3>Klasy</h3>
						<form method="post"><button class="editing editing_button" name="promotion">Awans wszystkich klas o 1 <i class="icon-award"></i></button></form>
						<button type="button" class="editing hidding" style="margin-top: 5px;" data-toggle="collapse" data-target="#edit_class">Edycja klasy <i class="icon-edit"></i></button>
						<div class="collapse" id="edit_class">
						<fieldset><legend>Edycja klasy</legend><div class="table-responsive"><table class="table">
						<form method="post" id="for_classes"><tr><td><label for="classes">Klasa do zmiany</label></td><td>';
						if($classes=$connect->query("SELECT * FROM classes WHERE section!='admin' AND section!='Administratorzy'"))
						{
							if($classes->num_rows>0)
							{
								echo '<select name="classes" id="classes"><option selected disabled hidden style="display: none" value=""> -- wybierz klasę -- </option>';
								while($classes_results=$classes->fetch_assoc())
									echo '<option value="'.$classes_results['id'].'">'.$classes_results['class'].''.$classes_results['section'].'</option>';
								echo '</select>';
							}
							else
								echo 'Brak klas w systemie.';
						}
						else
							throw new Exception($connect->error);
						echo '</td></tr></form>';

						function dupa()
						{
							unset($_SESSION['class_to_show']);
							$_SESSION['connect_error']='error';
						}

						if(isset($_POST['classes']))
						{
							if($choosen_class=$connect->query("SELECT * FROM classes WHERE id='".$_POST['classes']."' "))
							{
								if($choosen_class->num_rows>0)
								{
									$_SESSION['class_to_show']=$_POST['classes'];
									$choosen_class_results=$choosen_class->fetch_assoc();
									echo '<form method="post" action="show_class_actions.php">
									<tr><td><label for="class">Klasa:</label></td><td><input type="number" id="class" name="class" value="'.$choosen_class_results['class'].'"></td></tr>
									<tr><td><label for="section">Przydział:</label> <span style="margin-left: 7px;"><input type="checkbox" id="new_section" name="to_new_section" value="new"><label for="new_section"> (nowy)</label></span></td><td id="actual_section">';
									if($sections=$connect->query("SELECT section FROM classes WHERE section!='admin'"))
									{
										if($sections->num_rows>0)
										{
											echo '<select id="section" name="section"><option selected hidden style="display: none" value="'.$choosen_class_results['section'].'">'.$choosen_class_results['section'].'</option>';
											$_SESSION['actual_section']=Array();
											$i=0;
											while($sections_results=$sections->fetch_row())
											{
												echo '<option value="'.$sections_results[0].'">'.$sections_results[0].'</option>';
												$_SESSION['actual_section'][$i]=$sections_results[0];
												$i++;
											}
											echo '<select>';
										}
										else
											dupa();
									}
									else
										dupa();
									echo '</td></tr>
									<tr><td><label for="year">Rok rozpoczęcia:</label></td><td><select id="year" name="year"><option selected hidden style="display: none" value="'.$choosen_class_results['year_started'].'">'.$choosen_class_results['year_started'].'</option>';
									$_SESSION['year_to_change']=Array();
									$_SESSION['year_to_change'][date('Y')-11]=$choosen_class_results['year_started'];
									for($i=date('Y')-10; $i<=date('Y')+10; $i++)
									{
										echo '<option value="'.$i.'">'.$i.'</option>';
										$_SESSION['year_to_change'][$i]=$i;
									}
									echo '</select></td></tr></table>
									<p><button name="change" class="editing">Zmień <i class="icon-ok"></i></button></p>
									</form>';
								}
								else
								{
									$_SESSION['error']='<p>Nie znaleziono wybranej klasy w systemie!</p>';
									header('Location: show_class.php');
									exit();
								}
							}
							else
								dupa();
						}
						echo '</table></div></fieldset>
						</div>
						';
						
						echo '
							<script>
							$("#classes").bind("change", function () {
							    $("#for_classes").submit();
							});
							</script>
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

						if(isset($_SESSION['update_ok']))
						{
							echo '<div class="col-12" id="users">'.$_SESSION['update_ok'].'</div>';
							unset($_SESSION['update_ok']);
						}

						if(isset($_POST['promotion']))
						{
							if($connect->query("UPDATE classes set class=class+1"))
							{
								$_SESSION['update_ok']='<p>Pomyślnie awansowano wszystkie klasy o 1.</p>';
								header('Location: show_class.php');
								exit();
							}
							else
								throw new Exception($connect->error);
						}

						if(isset($_SESSION['error']))
						{
							echo '<div class="col-12">'.$_SESSION['error'].'</div>';
							unset($_SESSION['error']);
						}

						if(isset($_SESSION['class_to_show']))
						{
							echo '
							<script>
							$("#classes").val("'.$_SESSION['class_to_show'].'");
							$("#edit_class").css("display", "block");
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
			?>

		</div>
	</div>
</section>

		<?php
		if(isset($_POST['new_test']))
		{
			$_SESSION['which_class']='new_test';
			header('Location: edit_test.php');
			exit();
		}
		?>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/width_fieldset.js"></script>
<script>
	var actual_section=$("#actual_section").html();
	var checked=function() {
		if($("#new_section:checked").val())
			$("#actual_section").html('<input type="text" name="new_section">');
		else
			$("#actual_section").html(actual_section);
	};
	$("#new_section").on("click", checked);
</script>

</body>
</html>

<!--
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
-->