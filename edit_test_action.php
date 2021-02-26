<?php

session_start();

function unsetting()
{
	unset($_SESSION['which_class']);
	unset($_SESSION['class']);
	unset($_SESSION['exam_type']);
	unset($_SESSION['test_type']);
	for($i=2; $i<=6; $i++)
		setcookie("grade".$i, "", time() -3600);
	for($i=1; $i<=3; $i++)
		setcookie("option".$i, "", time() -3600);
	setcookie("count_question", "", time() -3600);
	setcookie("question", "", time() - 3600);
	if(!isset($_POST['canel']))
		$_SESSION['error']='<p>Pomyślnie ustawiono test dla wybranej klasy.</p>';
	header('Location: admin.php');
	exit();
}

if(isset($_POST['change']))
{
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
		{
			$_SESSION['connect_error']='error';
			header('Location: edit_test.php');
			exit();
		}
		else
		{	
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
			
			$grade_empty=false;
			$grade_ok=false;
			for($i=2; $i<=6; $i++)
			{
				if($_POST['grade'.$i]=='')
				{
					$grade_empty=true;
					break;
				}
			}
			if(!is_numeric($_POST['grade2']) || !is_numeric($_POST['grade3']) || !is_numeric($_POST['grade4']) || !is_numeric($_POST['grade5']) || !is_numeric($_POST['grade6']))
				$grade_ok=false;
			else if($_POST['grade2']!=min($_POST['grade2'], $_POST['grade3'], $_POST['grade4'], $_POST['grade5'], $_POST['grade6']))
				$grade_ok=false;
			else if($_POST['grade3']!=min($_POST['grade3'], $_POST['grade4'], $_POST['grade5'], $_POST['grade6']))
				$grade_ok=false;
			else if($_POST['grade4']!=min($_POST['grade4'], $_POST['grade5'], $_POST['grade6']))
				$grade_ok=false;
			else if($_POST['grade5']!=min($_POST['grade5'], $_POST['grade6']))
				$grade_ok=false;
			else if($_POST['grade6']!=max($_POST['grade2'], $_POST['grade3'], $_POST['grade4'], $_POST['grade5'], $_POST['grade6']))
				$grade_ok=false;
			else
				$grade_ok=true;

			function forwarding()
			{
				unset($_SESSION['count_question']);
				header('Location: edit_test.php');
				exit();
			}

			if($_SESSION['which_class']!='new_test')
			{
				if(isset($_POST['count_question']) && isset($_POST['time_on_question']) && isset($_POST['extra_points']) && isset($_POST['multipler_points']) && $grade_empty==false)
				{
					$question_ok=false;
					foreach($_SESSION['count_question'] as $value)
					{
						if($value==$_POST['count_question'])
						{
							$question_ok=true;
							break;
						}
					}
					if(!is_numeric($_POST['count_question']) || !is_numeric($_POST['time_on_question']) || !is_numeric($_POST['extra_points']) || !is_numeric($_POST['multipler_points']))
					{
						$_SESSION['error']='<p>Wszystkie wprowadzone dane muszą być liczbami!</p>';
						forwarding();
					}
					else if($question_ok==false)
					{
						$_SESSION['error']='<p>Wybrana ilość pytań jest niezgodna z rezultatami wyszukań!</p>';
						header('Location: edit_test.php');
						exit();
					}
					else if($_POST['time_on_question']<1 || $_POST['time_on_question']>100)
					{
						$_SESSION['error']='<p>Wprowadzona ilość sekund na pytanie nie mieści się w dozwolonym przedziale!</p>';
						forwarding();
					}
					else if($_POST['extra_points']<0 || $_POST['extra_points']>50)
					{
						$_SESSION['error']='<p>Wprowadzona ilość dodatkowych punktów nie mieści się w dozwolonym przedziale!</p>';
						forwarding();
					}
					else if($_POST['multipler_points']<1 || $_POST['multipler_points']>3)
					{
						$_SESSION['error']='<p>Liczba mnożnika punktów nie mieści się w dozwolonym przedziale!</p>';
						forwarding();
					}
					else if($grade_ok==false)
					{
						$_SESSION['error']='<p>Niepoprawnie wprowadzone zakresy kryteriów oceniania!</p>';
						forwarding();
					}
					else
					{
						if(($actual_test=$connect->query("SELECT id FROM actual_test WHERE id_class='".$_SESSION['which_class']."' ")) && ($new_grade_norm=$connect->query("SELECT * FROM grade_norm WHERE id_class='".$_SESSION['which_class']."' ")))
						{
							if($actual_test->num_rows>0)
							{
								$actual_test_results=$actual_test->fetch_row();
								if($new_grade_norm->num_rows>0)
								{
									if(($connect->query("UPDATE actual_test SET exam_type='".$_SESSION['exam_type']."', test_type='".$_SESSION['test_type']."', count_question='".$_POST['count_question']."', time_on_question='".$_POST['time_on_question']."', extra_points='".$_POST['extra_points']."', multipler_points='".$_POST['multipler_points']."' WHERE id='".$actual_test_results[0]."' ")) && ($connect->query("UPDATE grade_norm SET grade_2='".$_POST['grade2']."', grade_3='".$_POST['grade3']."', grade_4='".$_POST['grade4']."', grade_5='".$_POST['grade5']."', grade_6='".$_POST['grade6']."' WHERE id_class='".$_SESSION['which_class']."' ")))
										unsetting();
									else
									{
										$_SESSION['connect_error']='error';
										forwarding();
									}
								}
								else
								{
									if(($connect->query("UPDATE actual_test SET exam_type='".$_SESSION['exam_type']."', test_type='".$_SESSION['test_type']."', count_question='".$_POST['count_question']."', time_on_question='".$_POST['time_on_question']."', extra_points='".$_POST['extra_points']."', multipler_points='".$_POST['multipler_points']."' WHERE id='".$actual_test_results[0]."' ")) && ($connect->query("INSERT INTO grade_norm VALUES('', '".$_POST['grade2']."', '".$_POST['grade3']."', '".$_POST['grade4']."', '".$_POST['grade5']."', '".$_POST['grade6']."', '".$_SESSION['which_class']."') ")))
										unsetting();
									else
									{
										$_SESSION['connect_error']='error';
										forwarding();
									}
								}
							}
							else
							{
								$_SESSION['error']='<p>Błąd, nie znaleziono danych w systemie do zaktualizowania.</p>';
								forwarding();
							}
						}
						else
						{
							$_SESSION['connect_error']='error';
							forwarding();
						}
					}
				}
				else
				{
					$_SESSION['error']='<p>Uzupełnij poprawnie wszystkie pola!</p>';
					forwarding();
				}
			}
			else
			{
				if(isset($_POST['count_question']) && isset($_POST['time_on_question']) && isset($_POST['extra_points']) && isset($_POST['multipler_points']) && isset($_SESSION['class']) && $grade_empty==false)
				{
					$question_ok=false;
					foreach($_SESSION['count_question'] as $value)
					{
						if($value==$_POST['count_question'])
						{
							$question_ok=true;
							break;
						}
					}
					if(!is_numeric($_POST['count_question']) || !is_numeric($_POST['time_on_question']) || !is_numeric($_POST['extra_points']) || !is_numeric($_POST['multipler_points']))
					{
						$_SESSION['error']='<p>Wszystkie wprowadzone dane muszą być liczbami!</p>';
						forwarding();
					}
					else if($question_ok==false)
					{
						$_SESSION['error']='<p>Wybrana ilość pytań jest niezgodna z rezultatami wyszukań!</p>';
						header('Location: edit_test.php');
						exit();
					}
					else if($_POST['time_on_question']<1 || $_POST['time_on_question']>100)
					{
						$_SESSION['error']='<p>Wprowadzona ilość sekund na pytanie nie mieści się w dozwolonym przedziale!</p>';
						forwarding();
					}
					else if($_POST['extra_points']<0 || $_POST['extra_points']>50)
					{
						$_SESSION['error']='<p>Wprowadzona ilość dodatkowych punktów nie mieści się w dozwolonym przedziale!</p>';
						forwarding();
					}
					else if($_POST['multipler_points']<1 || $_POST['multipler_points']>3)
					{
						$_SESSION['error']='<p>Liczba mnożnika punktów nie mieści się w dozwolonym przedziale!</p>';
						forwarding();
					}
					else if($grade_ok==false)
					{
						$_SESSION['error']='<p>Niepoprawnie wprowadzone zakresy kryteriów oceniania!</p>';
						forwarding();
					}
					else
					{
						if(($actual_test=$connect->query("SELECT id FROM actual_test WHERE id_class='".$_SESSION['class']."' ")) && ($new_grade_norm=$connect->query("SELECT * FROM grade_norm WHERE id_class='".$_SESSION['class']."' ")))
						{
							if($actual_test->num_rows>0)
							{
								$actual_test_results=$actual_test->fetch_row();
								if($new_grade_norm->num_rows>0)
								{
									if(($connect->query("UPDATE actual_test SET exam_type='".$_SESSION['exam_type']."', test_type='".$_SESSION['test_type']."', count_question='".$_POST['count_question']."', time_on_question='".$_POST['time_on_question']."', extra_points='".$_POST['extra_points']."', multipler_points='".$_POST['multipler_points']."' WHERE id='".$actual_test_results[0]."' ")) && ($connect->query("UPDATE grade_norm SET grade_2='".$_POST['grade2']."', grade_3='".$_POST['grade3']."', grade_4='".$_POST['grade4']."', grade_5='".$_POST['grade5']."', grade_6='".$_POST['grade6']."' WHERE id_class='".$_SESSION['class']."' ")))
										unsetting();
									else
									{
										$_SESSION['connect_error']='error';
										forwarding();
									}
								}
								else
								{
									if(($connect->query("UPDATE actual_test SET exam_type='".$_SESSION['exam_type']."', test_type='".$_SESSION['test_type']."', count_question='".$_POST['count_question']."', time_on_question='".$_POST['time_on_question']."', extra_points='".$_POST['extra_points']."', multipler_points='".$_POST['multipler_points']."' WHERE id='".$actual_test_results[0]."' ")) && ($connect->query("INSERT INTO grade_norm VALUES('', '".$_POST['grade2']."', '".$_POST['grade3']."', '".$_POST['grade4']."', '".$_POST['grade5']."', '".$_POST['grade6']."', '".$_POST['class']."') ")))
										unsetting();
									else
									{
										$_SESSION['connect_error']='error';
										forwarding();
									}
								}
							}
							else
							{
								if(($connect->query("INSERT INTO actual_test VALUES('', '".$_SESSION['exam_type']."', '".$_SESSION['test_type']."', '".$_POST['count_question']."', '".$_POST['time_on_question']."', '".$_SESSION['class']."', '".$_POST['extra_points']."', '".$_POST['multipler_points']."' ) ")) && ($connect->query("INSERT INTO grade_norm VALUES('', '".$_POST['grade2']."', '".$_POST['grade3']."', '".$_POST['grade4']."', '".$_POST['grade5']."', '".$_POST['grade6']."', '".$_SESSION['class']."') ")))
									unsetting();
								else
								{
									$_SESSION['connect_error']='error';
									forwarding();
								}
							}
						}
						else
						{
							$_SESSION['connect_error']='error';
							forwarding();
						}
					}
				}
				else
				{
					$_SESSION['error']='<p>Uzupełnij poprawnie wszystkie pola!</p>';
					forwarding();
				}
			}

			$connect->close();

		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else if(isset($_POST['canel']))
	unsetting();
else
{
	header('Location: edit_test.php');
	exit();
}

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/