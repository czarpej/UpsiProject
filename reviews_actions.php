<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

if(isset($_SESSION['test_now']))
{
	header('Location: test.php');
	exit();
}

if(isset($_SESSION['score_now']))
{
	header('Location: score.php');
	exit();
}

$_SESSION['user_now']=true;

function forwarding()
{
	header('Location: reviews.php');
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

			if(!isset($_POST['proposition']) || $_POST['proposition']=='')
			{
				$_SESSION['add_info']='<p>Brak opini do przesłania!</p>';
				forwarding();
			}
			else
			{
				if($connect->query("INSERT INTO reviews VALUES('', '".$_SESSION['which_user_id']."', '".$_POST['proposition']."', now() )"))
				{
					$_SESSION['add_info']='<p>Pomyślnie przesłano opinię.</p>';
					forwarding();
				}
				else
					$_SESSION['connect_error']='error';
					forwarding();
			}

		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else
	forwarding();