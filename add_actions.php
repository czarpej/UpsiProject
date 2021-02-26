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

function forwarding()
{
	header('Location: add.php');
	exit();
}

if(isset($_POST['add']))
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

			$username=$_POST['login'];
			if($username=='')
			{
				$_SESSION['error']='<p>Nie wprowadzono nazwy użytkownika!</p>';
				forwarding();
			}
			else if(!isset($_POST['class_type']))
			{
				$_SESSION['error']='<p>Nie wybrano klasy!</p>';
				forwarding();
			}
			else
			{
				$class_ok=false;
				foreach($_SESSION['default_class_user'] as $value)
				{
					if($value==$_POST['class_type'])
					{
						$class_ok=true;
						break;
					}
				}
				unset($_SESSION['default_class_user']);
				if($class_ok!=true)
				{
					$_SESSION['error']='<p>Wybrana klasa nie jest rezultatem wyszukania przez system!</p>';
					forwarding();
				}
				else
				{
					if($connect->query("INSERT INTO users VALUES ('', '".$username."', '', '".$_POST['class_type']."', 0)"))
					{
						$_SESSION['adding_ok']='<p>Pomyślnie dodano użytkownika do systemu.</p>';
						forwarding();
					}
					else
					{
						$_SESSION['connect_error']='error';
						forwarding();
					}
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

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/