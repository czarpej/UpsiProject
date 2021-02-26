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
	header('Location: show_class.php');
	exit();
}

function connect_error()
{
	$_SESSION['connect_error']='error';
	forwarding();
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
			connect_error();
		}
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			function section_ok()
			{
				foreach($_SESSION['actual_section'] as $value)
				{
					if($value==$_POST['section'])
						return true;
				}
				return false;
			}
			function year_ok()
			{
				foreach($_SESSION['year_to_change'] as $value)
				{
					if($value==$_POST['year'])
						return true;
				}
				return false;
			}

			$id_class=$_SESSION['class_to_show'];
			unset($_SESSION['class_to_show']);
			if(!isset($_POST['class']) || !isset($_POST['year']))
			{
				$_SESSION['error']='<p>Błąd! Nie odnaleziono wszystkich pól z formularza!</p>';
				forwarding();
			}
			else if(isset($_POST['section']))
			{						
				if(section_ok()==false || year_ok()==false)
				{
					$_SESSION['error']='<p>Wybrane opcje nie są zgodne z rezultatami wyszukań!</p>';
					forwarding();
				}
				else
				{
					if($connect->query("UPDATE classes set class='".$_POST['class']."', section='".$_POST['section']."', year_started='".$_POST['year']."' WHERE id='".$id_class."' "))
					{
						$_SESSION['update_ok']='<p>Pomyślnie zaktualizowano dane wybranej klasy.</p>';
						forwarding();
					}
					else
						connect_error();
				}
			}
			else if(isset($_POST['to_new_section']))
			{
				if(year_ok()==false)
				{
					$_SESSION['error']='<p>Wybrane opcje nie są zgodne z rezultatami wyszukań!</p>';
					forwarding();
				}
				else if(!isset($_POST['new_section']))
				{
					$_SESSION['error']='<p>Błąd! Nie odnaleziono nowej sekcji w formularzu!</p>';
					forwarding();
				}
				else if($_POST['new_section']=='')
				{
					$_SESSION['error']='<p>Nie wprowadzono nowej sekcji dla klasy!</p>';
					forwarding();
				}
				else
				{
					if($connect->query("UPDATE classes set class='".$_POST['class']."', section='".$_POST['section']."', year_started='".$_POST['year']."' WHERE id='".$id_class."' "))
					{
						$_SESSION['update_ok']='<p>Pomyślnie zaktualizowano dane wybranej klasy.</p>';
						forwarding();
					}
					else
						connect_error();
				}
			}
			else
			{
				$_SESSION['error']='<p>Błąd! Nie odnaleziono wszystkich pól z formularza!</p>';
				forwarding();
			}

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else if(isset($_POST['promotion']))
{
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
		{
			connect_error();
		}
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if($connect->query("UPDATE classes set class=class+1"))
			{
				$_SESSION['update_ok']='<p>Pomyślnie awansowano wszystkie klasy o 1.</p>';
				forwarding();
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
	forwarding();