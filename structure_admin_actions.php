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
	header('Location: structure_admin.php');
	exit();
}

function forwarding_to_index()
{
	header('Location: index.php');
	exit();
}

function connect_error()
{
	$_SESSION['connect_error']='error';
	forwarding();
}

function query_good()
{
	$login=$_SESSION['which_admin'];
	session_destroy();
	session_start();
	$_SESSION['login']=$login;
}

if(isset($_POST['pass']))
{
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
			connect_error();
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			$class_ok=false;
			foreach($_SESSION['move_class'] as $value)
			{
				if($value==$_POST['class'])
				{
					$class_ok=true;
					break;
				}
			}

			if($class_ok==false)
			{
				$_SESSION['error']='<p>Wybrana klasa jest niezgodna z rezultatami wyszukań!</p>';
				forwarding();
			}
			else
			{
				if($connect->query("UPDATE users SET id_klasy='".$_POST['class']."' WHERE id='".$_SESSION['which_admin_id']."' "))
				{
					query_good();
					$_SESSION['login_error']='<p>Pomyślnie przeniesiono do wybranej klasy. Można się zalogować.</p>';
					forwarding_to_index();
				}
				else
					connect_error();
			}

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else if(isset($_POST['block']))
{
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
			connect_error();
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if($connect->query("UPDATE users SET freezing=1 WHERE id='".$_SESSION['which_admin_id']."' "))
			{
				query_good();
				$_SESSION['login_error']='<p>Pomyślnie zamroziłeś swoje konto i pozostanie ono nieaktywne do czasu jego odblokowania.</p>';
				forwarding_to_index();
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