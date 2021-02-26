<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

if(!isset($_SESSION['test_now']))
{
	header('Location: user.php');
	exit();
}

if($_SESSION['k']==0)
{
	unset($_SESSION['test_now']);
	$_SESSION['score_now']=true;
	header('Location: score.php');
	exit();
}

if(isset($_COOKIE['time']))
{
	unset($_SESSION['test_now']);
	setcookie("time", "", time() - 3600);
	$_SESSION['score_now']=true;
	header('Location: score.php');
	exit();
}

if(isset($_POST['canel']))
{
	unset($_SESSION['test_now']);
	$_SESSION['score_now']=true;
	header('Location: score.php');
	exit();
}

$_SESSION['user_now']=true;
$_SESSION['test_now']=true;
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
	<script>
		<?php
		if(isset($_COOKIE['cookie_help']))
		{
			$_SESSION['time']=$_COOKIE['cookie_help'];
		}
		echo '				
		var sec='.$_SESSION['time'].';
		function change_time()
		{
			var minutes=sec/60;
			var second=sec%60;
			if(second<=9)
				second="0"+second;
			$("#showed_time").html(Math.trunc(minutes)+":"+second);

			var width=((sec/'.$_SESSION['max_time'].')*100);
			$(".time_belt").css("width", (width+"%"));

			$("progress").val(sec);
			$("input[type=hidden]").val(sec);
			sec--;
			document.cookie="cookie_help="+sec;
			if(sec==0)
			{
				document.cookie="time=0;";
				window.location.replace("test.php");			
			}
			setTimeout("change_time()", 1000);
		}
		';
		?>
	</script>
	<script type="text/javascript" src="js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="js/scroll_to_id_users.js"></script>

</head>
<body onload="change_time()">

	<nav class="navbar navbar-dark navbar-expand">
		<ol class="navbar-nav">
			<li class="nav-item"><a class="nav-link"><form method="post"><button name="canel"><i class="icon-to-end-alt"></i> Zakończ test</button></form></a></li>
			<li class="nav-item"><a class="nav-link"><form action='logout.php' method='post'><button name='logout'><i class="icon-logout"></i> Wyloguj</button></form></a></li>
		</ol>
	</nav>

	<section class='test'>
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

						if($question=$connect->query("SELECT question, image, ans_a, ans_b, ans_c, ans_d, ans_good FROM question WHERE id_question='".$_SESSION['random'][$_SESSION['k']]."' "))
						{
							$question_results=$question->fetch_assoc();

							echo '<form method="post"><div class="info_test">';
							//info of actual test in the moment
							if($info_test=$connect->query("SELECT * FROM actual_test WHERE id_class='".$_SESSION['which_user_class']."' "))
							{
								$info_test_results=$info_test->fetch_assoc();
								if($info_test_results['exam_type']=='all')
								{
									$info_test_results['exam_type']='Super Combo';
									$info_test_results['test_type']='Super Combo';
								}
								echo '
								<div class="table-responsive"><table class="table" style="margin: 0;">
								<tr><th>Przedmiot:</th><td>'.$info_test_results['exam_type'].'</td></tr>
								<tr><th>Zakres materiału:</th><td>'.$info_test_results['test_type'].'</td></tr>
								<tr><th>Ilość pytań:</th><td>'.count($_SESSION['random']).'</td></tr>
								<tr><th>Pozostało pytań:</th><td>'.$_SESSION['k'].'</td></tr>
								<tr><th>Pozostalo czasu: <i class="icon-hourglass-2"></i></th><td id="showed_time"></td></tr>
								<tr><td style="border: 0;" colspan="2">
								<div class="progress_bar">
								<span class="time_belt"></span>
								</div>
								</td></tr>
								</table></div>	
								<input type="hidden" name="time_bar" value="'.$_SESSION['time'].'">							
								';
							}
							else 
								throw new Exception($connect->error);
							echo '</div>';

							//question and answers
							echo '
							<div id="users"><div class="question">'.$question_results['question'].'</div>';
							if($question_results['image']!='')
								echo '<div class="question"><center><img src="img/img_to_question/'.$question_results['image'].'" alt="Brak obrazka? Odśwież stronę, jak nie podziała zgłoś błąd nauczycielowi!"></center></div>';
							$how_now_random=0;
							$repeat;
							$answer=Array(); //answers in tab
							$answer[0]='<label for="ans_1"><div class="answer" id="answer1"><input type="checkbox" name="answer1" value="a" id="ans_1">'.$question_results['ans_a'].'</div></label>';
							$answer[1]='<label for="ans_2"><div class="answer" id="answer2"><input type="checkbox" name="answer2" value="b" id="ans_2">'.$question_results['ans_b'].'</div></label>';
							$answer[2]='<label for="ans_3"><div class="answer" id="answer3"><input type="checkbox" name="answer3" value="c" id="ans_3">'.$question_results['ans_c'].'</div></label>';
							$answer[3]='<label for="ans_4"><div class="answer" id="answer4"><input type="checkbox" name="answer4" value="d" id="ans_4">'.$question_results['ans_d'].'</div></label>';
							for ($k=0; $k<4; $k++) //mixing answer
							{
								do
								{
									$number=rand(0, 3);
									$random_ok=true;

									for ($l=0; $l<$how_now_random; $l++)
									{
										if ($number==$repeat[$l]) $random_ok=false;
									}

									if ($random_ok==true)
									{
										$how_now_random++;
										$repeat[$k]=$number;
										echo $answer[$number];
									}

								} while($random_ok!=true);
							}
							echo '												
							<p><button name="set" class="editing">Zatwierdź <i class="icon-to-end"></i></button></p>
							</form></div>';

							$tab=Array();
							$good_ans=Array();

							function answer_repeat($i) //help function, this function checks repeats good answers in downloaded good answer with database
							{
								$repeat=false;
								for($j=0; $j<$i; $j++)
								{
									
										if(@$GLOBALS['tab'][$j]==$GLOBALS['tab'][$i])
										{
											$repeat=true;
										}
								}
								if($repeat==false)
									return false;
								else
									return true;
							}

							if(isset($_POST['set']))
							{
								$good_answer=strlen($question_results['ans_good']); //length row good answer downloaded with database
								$how_answer=0;
								$pkt=0;
								$good=false;
								for($i=0; $i<$good_answer; $i++)
								{
									if(substr($question_results['ans_good'], $i, 1)=='a' || substr($question_results['ans_good'], $i, 1)=='b' || substr($question_results['ans_good'], $i, 1)=='c' || substr($question_results['ans_good'], $i, 1)=='d') //checks any letters
									{
										$tab[$i]=substr($question_results['ans_good'], $i, 1); //write separately any letters
										if(answer_repeat($i)==false)
										{
											$how_answer++;
											$good_ans[$how_answer]=$tab[$i]=substr($question_results['ans_good'], $i, 1);
										}
										else
											continue;
									}
								}

								//pkt for this question
								$pkt_question=1/count($good_ans); //how pkt have one question
								function score($t, $pkt, $good) //help function, give or take pkt with stakes
								{
									if($t==3)
									{
										if($good==true)
											$pkt+=(1/3);
										else
											$pkt-=1;
									}
									else if($t==1)
									{
										if($good==true)
											$pkt+=1;
										else
											$pkt-=(1/3);
									}
									else if($t==2)
									{
										if($good==true)
											$pkt+=(1/2);
										else
											$pkt-=(1/2);
									}
									else if($t==4)
									{
										if($good==true)
											$pkt+=(1/4);
										else
											$pkt-=(1/4);
									}
									$_SESSION['pkt']+=$pkt*$_SESSION['multipler_points']; //this pkt are multipled by multipler points
								}

								//checks which answers exist and give or take pkt
								for($i=1; $i<=$how_answer; $i++)
								{
									if(isset($_POST['answer1']))
									{
										if($_POST['answer1']==$good_ans[$i])
										{
											$good=true;
											score(count($good_ans), $pkt, $good);
											unset($_POST['answer1']);
										}
										else if($i==$how_answer)
										{
											$good=false;
											score(count($good_ans), $pkt, $good);
										}
									}
									if(isset($_POST['answer2']))
									{
										if($_POST['answer2']==$good_ans[$i])
										{
											$good=true;
											score(count($good_ans), $pkt, $good);
											unset($_POST['answer2']);
										}
										else if($i==$how_answer)
										{
											$good=false;
											score(count($good_ans), $pkt, $good);
										}
									}
									if(isset($_POST['answer3']))
									{
										if($_POST['answer3']==$good_ans[$i])
										{
											$good=true;
											score(count($good_ans), $pkt, $good);
											unset($_POST['answer3']);
										}
										else if($i==$how_answer)
										{
											$good=false;
											score(count($good_ans), $pkt, $good);
										}
									}
									if(isset($_POST['answer4']))
									{
										if($_POST['answer4']==$good_ans[$i])
										{
											$good=true;
											score(count($good_ans), $pkt, $good);
											unset($_POST['answer4']);
										}
										else if($i==$how_answer)
										{
											$good=false;
											score(count($good_ans), $pkt, $good);
										}
									}
								}
								$_SESSION['k']--; //how question is now 
								$_SESSION['time']=$_POST['time_bar'];
								header('Location: test.php');
							}
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

				<script>
					$('input[type=checkbox]').click(function(){
						for(i=1; i<=4; i++)
						{
							if($('#ans_'+i).prop('checked'))
							{
								$('#answer'+i).addClass('check_answer');
								$('#answer'+i).removeClass('answer');
							}
							else
							{
								$('#answer'+i).addClass('answer');
								$('#answer'+i).removeClass('check_answer');
							}
						}
					});
				</script>

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