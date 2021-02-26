<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['user_now']=true;

if(isset($_POST['start_test']))
{
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

			$actual_test=$connect->query("SELECT * FROM actual_test WHERE id_class='".$_SESSION['which_user_class']."' ");
			$actual_test_results=$actual_test->fetch_assoc();

			if($actual_test_results['exam_type']=='all')
			{
				$k=0; //how question
				if($questions=$connect->query("SELECT * FROM question"))
				{
					while($how_question=$questions->fetch_assoc())
					{
						$k++;
						$question[$k]=$how_question['id_question'];
					}
					$how_random=$actual_test_results['count_question'];
					$how_now_random=0;
					$_SESSION['random']=Array(); //tab with randomed question
					for ($i=0; $i<$how_random; $i++)
					{
						do
						{
							$number=rand($question[1], $question[$k]);
							$los=$connect->query("SELECT id_question FROM question WHERE id_question='".$number."' ");
							while(!$los->num_rows>0)
							{
								$number=rand($question[1], $question[$k]);
								$los=$connect->query("SELECT id_question FROM question WHERE id_question='".$number."' ");
							}
							$random_ok=true;

							for ($j=1; $j<=$how_now_random; $j++)
							{
								if ($number==$_SESSION['random'][$j]) $random_ok=false;
							}

							if ($random_ok==true)
							{
								$how_now_random++;
								$_SESSION['random'][$how_now_random]=$number; //randomed number's question
							}

						} while($random_ok!=true);
					}
					$_SESSION['test_now']=true;
					$_SESSION['k']=$how_now_random; //help variable, how question is now
					$_SESSION['pkt']=0+$actual_test_results['extra_points'];
					$_SESSION['max_pkt']=$how_now_random;
					$_SESSION['max_time']=$actual_test_results['count_question']*$actual_test_results['time_on_question'];
					$_SESSION['time']=$_SESSION['max_time'];
					$_SESSION['extra_points']=$actual_test_results['extra_points'];
					$_SESSION['multipler_points']=$actual_test_results['multipler_points'];
					header('Location: test.php');
					exit();
				}
				else
					throw new Exception($connect->error);
			}
			else if($actual_test_results['test_type']=='Combo')
			{
				$k=0;
				if($questions=$connect->query("SELECT * FROM question WHERE exam_category='".$actual_test_results['exam_type']."' "))
				{
					while($how_question=$questions->fetch_assoc())
					{
						$k++;
						$question[$k]=$how_question['id_question'];
					}
					$how_random=$actual_test_results['count_question'];
					$how_now_random=0;
					$_SESSION['random']=Array();
					for ($i=0; $i<$how_random; $i++)
					{
						do
						{
							$number=rand($question[1], $question[$k]);
							$los=$connect->query("SELECT id_question FROM question WHERE id_question='".$number."' ");
							while(!$los->num_rows>0)
							{
								$number=rand($question[1], $question[$k]);
								$los=$connect->query("SELECT id_question FROM question WHERE id_question='".$number."' ");
							}
							$random_ok=true;

							for ($j=1; $j<=$how_now_random; $j++)
							{
								if ($number==$_SESSION['random'][$j]) $random_ok=false;
							}

							if ($random_ok==true)
							{
								$how_now_random++;
								$_SESSION['random'][$how_now_random]=$number;
							}

						} while($random_ok!=true);
					}
					$_SESSION['test_now']=true;
					$_SESSION['k']=$how_now_random;
					$_SESSION['pkt']=0+$actual_test_results['extra_points'];
					$_SESSION['max_pkt']=$how_now_random;
					$_SESSION['max_time']=$actual_test_results['count_question']*$actual_test_results['time_on_question'];
					$_SESSION['time']=$_SESSION['max_time'];
					$_SESSION['extra_points']=$actual_test_results['extra_points'];
					$_SESSION['multipler_points']=$actual_test_results['multipler_points'];
					header('Location: test.php');
					exit();
				}
				else
					throw new Exception($connect->error);
			}
			else
			{
				$k=0;
				if($questions=$connect->query("SELECT * FROM question WHERE exam_category='".$actual_test_results['exam_type']."' AND test_category='".$actual_test_results['test_type']."' "))
				{
					while($how_question=$questions->fetch_assoc())
					{
						$k++;
						$question[$k]=$how_question['id_question'];
					}
					$how_random=$actual_test_results['count_question'];
					$how_now_random=0;
					$_SESSION['random']=Array();
					for ($i=0; $i<$how_random; $i++)
					{
						do
						{
							$number=rand($question[1], $question[$k]);
							$los=$connect->query("SELECT id_question FROM question WHERE id_question='".$number."' ");
							while(!$los->num_rows>0)
							{
								$number=rand($question[1], $question[$k]);
								$los=$connect->query("SELECT id_question FROM question WHERE id_question='".$number."' ");
							}
							$random_ok=true;

							for ($j=1; $j<=$how_now_random; $j++)
							{
								if ($number==$_SESSION['random'][$j]) $random_ok=false;
							}

							if ($random_ok==true)
							{
								$how_now_random++;
								$_SESSION['random'][$how_now_random]=$number;
							}

						} while($random_ok!=true);
					}
					$_SESSION['test_now']=true;
					$_SESSION['k']=$how_now_random;
					$_SESSION['pkt']=0+(($actual_test_results['extra_points']/100)*$how_now_random);
					$_SESSION['max_pkt']=$how_now_random;
					$_SESSION['max_time']=$actual_test_results['count_question']*$actual_test_results['time_on_question'];
					$_SESSION['time']=$_SESSION['max_time'];
					$_SESSION['extra_points']=$actual_test_results['extra_points'];
					$_SESSION['multipler_points']=$actual_test_results['multipler_points'];
					header('Location: test.php');
					exit();
				}
				else
					throw new Exception($connect->error);
			}

			$connect->close();

		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else
{
	header('Location: user.php');
	exit();
}

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/