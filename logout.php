<?php

session_start();

if(isset($_POST['logout']))
{
	//if in the moment now test is active
	if(isset($_SESSION['test_now']))
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
				unset($_SESSION['test_now']);
				unset($_SESSION['user_now']);

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

					if(isset($_COOKIE['time']))
						setcookie("time", "", time() - 3600);
					
					if(isset($_COOKIE['cookie_help']))
						setcookie("cookie_help", "", time() - 3600);

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

					$_SESSION['end_pkt']=($_SESSION['pkt']/$_SESSION['max_pkt'])*100;
					$_SESSION['end_pkt']=number_format($_SESSION['end_pkt'], 4);

					if($_SESSION['end_pkt']<($grade_norm_results['grade_2']-0.000001))
					{
						if($_SESSION['end_pkt']<(-99.999999))
							$_SESSION['end_pkt']=(-100);
						$_SESSION['mark']=1;
					}	
					else if($_SESSION['end_pkt']>=($grade_norm_results['grade_2']-0.000001) && $_SESSION['end_pkt']<($grade_norm_results['grade_3']-0.000001))
					{
						$_SESSION['mark']=2;
					}			
					else if($_SESSION['end_pkt']>=($grade_norm_results['grade_3']-0.000001) && $_SESSION['end_pkt']<($grade_norm_results['grade_4']-0.000001))
					{
						$_SESSION['mark']=3;
					}		
					else if($_SESSION['end_pkt']>=($grade_norm_results['grade_4']-0.000001) && $_SESSION['end_pkt']<($grade_norm_results['grade_5']-0.000001))
					{
						$_SESSION['mark']=4;
					}
					else if($_SESSION['end_pkt']>=($grade_norm_results['grade_5']-0.000001) && $_SESSION['end_pkt']<($grade_norm_results['grade_6']-0.000001))
					{
						$_SESSION['mark']=5;
					}
					else if($_SESSION['end_pkt']>=($grade_norm_results['grade_6']-0.000001))
					{
						if($_SESSION['end_pkt']>99.999999)
							$_SESSION['end_pkt']=100;
						$_SESSION['mark']=6;
					}

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
						$_SESSION['end_test']='<p style="margin-top: 0px; margin-bottom: 0px; font-size: 16px;">Wynik testu zapisano w bazie danych. <br> Ocena: '.$_SESSION['mark'].' <br> Ilość punktów: '.number_format($_SESSION['end_pkt'], 2).'% </p><a href="index.php"><span style="font-size: 12px;">(Odśwież stronę, aby informacja znikła) <i class="icon-arrows-cw"></i></span></a>';
						header('Location: index.php');
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

	for($i=2; $i<=6; $i++)
		setcookie("grade".$i, "", time() -3600);
	for($i=1; $i<=3; $i++)
		setcookie("option".$i, "", time() -3600);
	setcookie("option".$i, "", time() -3600);

	header('Location: index.php');
	session_destroy();
	exit();
}
else
{
	header('Location: index.php');
	exit();
}

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/