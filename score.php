<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

if(!isset($_SESSION['score_now']))
{
	header('Location: test.php');
	exit();
}

if(isset($_COOKIE['cookie_help']))
	setcookie("cookie_help", "", time() - 3600);

$_SESSION['user_now']=true;
$_SESSION['score_now']=true;
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

<nav class="navbar navbar-dark navbar-expand">
	<ol class="navbar-nav">
		<li class="nav-item"><a class="nav-link"><form action='logout.php' method='post'><button name='logout'><i class="icon-logout"></i> Wyloguj</button></form></a></li>
	</ol>
</nav>

<section class='score'>
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

					if(($actual_test=$connect->query("SELECT * FROM actual_test WHERE id_class='".$_SESSION['which_user_class']."' ")) && ($grade_norm=$connect->query("SELECT * FROM grade_norm WHERE id_class='".$_SESSION['which_user_class']."' ")))
					{
						$actual_test_results=$actual_test->fetch_assoc();
						if($actual_test_results['exam_type']=='all')
						{
							$actual_test_results['exam_type']='Super Combo';
							$actual_test_results['test_type']='Super Combo';
						}

						if($grade_norm->num_rows>0)
							$grade_norm_results=$grade_norm->fetch_assoc();
						else
						{
							$grade_norm_results=Array();
							$grade_norm_results['grade_2']=50;
							$grade_norm_results['grade_3']=60;
							$grade_norm_results['grade_4']=75;
							$grade_norm_results['grade_5']=90;
							$grade_norm_results['grade_6']=100;
						}

						function mark_info($k)
						{
							if($k==1)
							{
								echo 'NEGATYWNY</h1></div>';
								echo '<div class="image_info"><img src="img/mark_'.$k.'.png" alt="Ocena '.$k.'"></div>';
								echo '<div class="test_info"><h3>Procentowa ilość puktów: '.number_format($_SESSION['end_pkt'], 2).'%<br>Ocena: '.$k.'</h3></div>';
								$_SESSION['mark']=$k;
							}
							else
							{
								echo 'POZYTYWNY</h1></div>';
								echo '<div class="image_info"><img src="img/mark_'.$k.'.png" alt="Ocena '.$k.'"></div>';
								echo '<div class="test_info"><h3>Procentowa ilość puktów: '.number_format($_SESSION['end_pkt'], 2).'%<br>Ocena: '.$k.'</h3></div>';
								$_SESSION['mark']=$k;
							}
						}

						$_SESSION['end_pkt']=($_SESSION['pkt']/$_SESSION['max_pkt'])*100;
						$_SESSION['end_pkt']=number_format($_SESSION['end_pkt'], 4);
						function mark($grade_2, $grade_3, $grade_4, $grade_5, $grade_6)
						{
							echo '<div class="test_info"><h1>Wynik testu: ';
							if($_SESSION['end_pkt']<($grade_2-0.000001))
							{
								if($_SESSION['end_pkt']<(-99.999999))
									$_SESSION['end_pkt']=(-100);
								mark_info(1);
							}	
							else if($_SESSION['end_pkt']>=($grade_2-0.000001) && $_SESSION['end_pkt']<($grade_3-0.000001))
							{
								mark_info(2);
							}			
							else if($_SESSION['end_pkt']>=($grade_3-0.000001) && $_SESSION['end_pkt']<($grade_4-0.000001))
							{
								mark_info(3);
							}		
							else if($_SESSION['end_pkt']>=($grade_4-0.000001) && $_SESSION['end_pkt']<($grade_5-0.000001))
							{
								mark_info(4);
							}
							else if($_SESSION['end_pkt']>=($grade_5-0.000001) && $_SESSION['end_pkt']<($grade_6-0.000001))
							{
								mark_info(5);
							}
							else if($_SESSION['end_pkt']>=($grade_6-0.000001))
							{
								if($_SESSION['end_pkt']>99.999999)
									$_SESSION['end_pkt']=100;
								mark_info(6);
							}
						}
						mark($grade_norm_results['grade_2'], $grade_norm_results['grade_3'], $grade_norm_results['grade_4'], $grade_norm_results['grade_5'], $grade_norm_results['grade_6']);

						if($this_test=$connect->query("SELECT * FROM results"))
						{
							$repeat=false;
							while($this_test_results=$this_test->fetch_assoc())
							{
								if($this_test_results['id_users']==$_SESSION['which_user_id'] && $this_test_results['exam_category']==$actual_test_results['exam_type'] && $this_test_results['comment']==$actual_test_results['test_type'] && $this_test_results['score']==$_SESSION['end_pkt'] && $this_test_results['mark']==$_SESSION['mark'] && $this_test_results['count_question']==count($_SESSION['random']) && $this_test_results['extra_points']==$_SESSION['extra_points'] && $this_test_results['multipler_points']==$_SESSION['multipler_points'] && $this_test_results['date']==date("Y-m-d") && $this_test_results['grade_2']==$grade_norm_results['grade_2'] && $this_test_results['grade_3']==$grade_norm_results['grade_3'] && $this_test_results['grade_4']==$grade_norm_results['grade_4'] && $this_test_results['grade_5']==$grade_norm_results['grade_5'] && $this_test_results['grade_6']==$grade_norm_results['grade_6']) //if in database is record, where this is same like this test score now
								{
									$repeat=true;
									break;
								}
								
							}
							if($repeat==false) //if record isn't in database - add new record
							{
								if($connect->query("INSERT INTO results VALUES ('', '".$_SESSION['which_user_id']."', '".$actual_test_results['exam_type']."', '".$actual_test_results['test_type']."', '".$_SESSION['end_pkt']."', '".$_SESSION['mark']."', '".count($_SESSION['random'])."', '".$_SESSION['extra_points']."', '".$_SESSION['multipler_points']."', '".date("Y-m-d")."', '".$grade_norm_results['grade_2']."', '".$grade_norm_results['grade_3']."', '".$grade_norm_results['grade_4']."', '".$grade_norm_results['grade_5']."', '".$grade_norm_results['grade_6']."')"))
								{
									;
								}
								else
									throw new Exception($connect->error);
							}
							else //if record is in database - will update this
							{
								if($connect->query("UPDATE results SET id_users='".$_SESSION['which_user_id']."', exam_category='".$actual_test_results['exam_type']."', comment='".$actual_test_results['test_type']."', score='".$_SESSION['end_pkt']."', mark='".$_SESSION['mark']."', count_question='".count($_SESSION['random'])."', extra_points='".$_SESSION['extra_points']."', multipler_points='".$_SESSION['multipler_points']."', `date`='".date("Y-m-d")."', grade_2='".$grade_norm_results['grade_2']."', grade_3='".$grade_norm_results['grade_3']."', grade_4='".$grade_norm_results['grade_4']."', grade_5='".$grade_norm_results['grade_5']."', grade_6='".$grade_norm_results['grade_6']."' WHERE id_results='".$this_test_results['id_results']."' "))
								{
									;
								}
								else
									throw new Exception($connect->error);
							}
						}
						else
							throw new Exception($connect->error);
						
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