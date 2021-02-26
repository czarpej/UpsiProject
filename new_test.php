<?php

session_start();

function forwarding()
{
	header('Location: edit_test.php');
	exit();
}

if(isset($_POST['new_test']))
{
	$_SESSION['which_class']='new_test';
	forwarding();
}
else if(isset($_POST['change_test']))
{
	$_SESSION['which_class']=$_POST['class'];
	forwarding();
}
else if(isset($_POST['delete_test']))
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

			if(($connect->query("DELETE FROM actual_test WHERE id_class='".$_POST['class']."' ")) && ($connect->query("DELETE FROM grade_norm WHERE id_class='".$_POST['class']."' ")))
			{
				$_SESSION['delete_ok']='<p>Pomyślnie usunięto ustawiony test.</p>';
				header('Location: admin.php');
				exit();
			}
			else
			{
				$_SESSION['connect_error']='error';
				header('Location: admin.php');
				exit();
			}
		}
	}
	catch(Exception $e)
	{
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
}
else
{
	header('Location: admin.php');
	exit();
}