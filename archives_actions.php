<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['admin_log_now']=true;

function forwarding()
{
	header('Location: archives.php');
	exit();
}

function connect_error()
{
	$_SESSION['connect_error']='error';
	forwarding();
}

if(isset($_POST['results']))
{
	unset($_SESSION['yearbook']);
	if(isset($_POST['archived_student']) && $_POST['archived_student']!='' && isset($_POST['archived_login']) && $_POST['archived_login']!='')
	{
		require_once 'dbconnect.php';
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$connect=new mysqli($address, $db_login, $db_password, $db_name);
			if($connect->connect_errno!=0)
			{
				$_SESSION['connect_error']='error';
				forwarding();
			}
			else
			{
				$connect->query("SET CHARSET utf8");
				$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

				if($tests=$connect->query("SELECT * FROM archive_results WHERE id_users='".$_POST['archived_student']."' "))
				{
					if($tests->num_rows>0)
					{
						$before='<div class="table-responsive"><div class="searched_question"><h3>Testy użytkownika '.$_POST['archived_login'].':</h3>';
						$content='';
						$i=0;
						while($tests_results=$tests->fetch_assoc())
						{
							$content.='
							<div class="this_question">
								<div class="content_question">
									Przedmiot: '.$tests_results['exam_category'].'<br>
									Zakres materiału: '.$tests_results['comment'].'<br>
									Data testu: '.$tests_results['date'].'<br>
								</div>
								<div class="info_question">
									<div class="info_question_answer">
										Wynik: '.$tests_results['score'].'%<br>
										Ocena: '.$tests_results['mark'].'<br>
										Ilość pytań: '.$tests_results['count_question'].'<br>
										Dodatkowe % na start: '.$tests_results['extra_points'].'<br>
										Mnożnik punktów: '.$tests_results['multipler_points'].'<br>
									</div>
									<div class="change_question">
										Kryteria oceniania: <input type="button" class="editing hidding" data-toggle="collapse" data-target="#norm'.$i.'" value="Pokaż">
										<div id="norm'.$i.'" class="collapse" style="margin-top: 10px;">
										<div class="table-responsive"><table class="table table-bordered">
										<tr><th colspan="2">Kryteria ocen</th></tr>
										<tr><td>2</td><td>'.$tests_results['grade_2'].'%</td></tr>
										<tr><td>3</td><td>'.$tests_results['grade_3'].'%</td></tr>
										<tr><td>4</td><td>'.$tests_results['grade_4'].'%</td></tr>
										<tr><td>5</td><td>'.$tests_results['grade_5'].'%</td></tr>
										<tr><td>6</td><td>'.$tests_results['grade_6'].'%</td></tr>
										</table></div>
										</div>
									</div>
								</div>
							</div>';
							$i++;
						}
						$after='</div></div>';
						$_SESSION['error']=$before.''.$content.''.$after;
						forwarding();
					}
					else
					{
						$_SESSION['error']='<p>Wybrany użytkownik nie miał przeprowadzanych żadnych testów.</p>';
						forwarding();
					}
				}
				else
					connect_error();

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
		$_SESSION['error']='<p>Błąd! Nie udało się odczytać wybranego użytkownika.</p>';
		forwarding();
	}
}
else if(isset($_POST['delete_archived']))
{
	unset($_SESSION['yearbook']);
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
		{
			$_SESSION['connect_error']='error';
			forwarding();
		}
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			$j=0;
			for($i=0; $i<=$_SESSION['how_users']; $i++)
			{
				if(isset($_POST['student'.$i]))
				{
					if((!$connect->query("DELETE FROM archive_results WHERE id_users='".$_POST['student'.$i]."' ")) || (!$connect->query("DELETE FROM archive_users WHERE id='".$_POST['student'.$i]."' ")))
					{
						unset($_SESSION['how_users']);
						$_SESSION['connect_error']='<p>Wystąpił błąd! Usunięto '.$j.' użytkowników z archiwum.</p>';
						forwarding();
					}
					$j++;
				}
			}

			if($j<=0)
				$_SESSION['error']='<p>Nie wybrano żadnych użytkowników do usunięcia!</p>';
			else if($j==1)
				$_SESSION['error']='<p>Pomyślnie usunięto '.$j.' użytkownika.</p>';
			else
				$_SESSION['error']='<p>Pomyślnie usunięto '.$j.' użytkowników.</p>';
			forwarding();

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else
	forwarding();