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
	header('Location: show_admin.php');
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

			if(!isset($_POST['login']) || $_POST['login']=='')
			{
				$_SESSION['error']='<p>Nie wprowadzono loginu!</p>';
				forwarding();
			}
			else if(!isset($_POST['admin_id']) || $_POST['admin_id']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie odnaleziono administratora do zmiany!</p>';
				forwarding();
			}
			else
			{
				$class_ok=false;
				foreach($_SESSION['admins_class'] as $value)
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
					if($connect->query("UPDATE users SET login='".$_POST['login']."', id_klasy='".$_POST['class']."' WHERE id='".$_POST['admin_id']."' "))
					{
						if($_SESSION['which_admin_id']==$_POST['admin_id'])
							$_SESSION['which_admin']=$_POST['login'];

						if($_POST['class']!=2 && $_SESSION['which_admin_id']==$_POST['admin_id'])
						{
							session_destroy();
							session_start();
							$_SESSION['login']=$_POST['login'];
							$_SESSION['login_error']='<p style="font-size: 16px;">Nastąpiło wylogowanie z powodu usunięcia z klasy administratorów.</p>';
							header('Location: index.php');
							exit();
						}
						else if($_POST['class']!=2)
							$_SESSION['error']='<p>Pomyślnie zdegradowano wybranego użytkownika.</p>';
						else
							$_SESSION['error']='<p>Pomyślnie zaktualizowano dane wybranego administratora.</p>';
						forwarding();
					}
					else
						connect_error();
				}
			}

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else if(isset($_POST['delete']))
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

			$j=0;
			for($i=0; $i<$_SESSION['how_admins']; $i++)
			{
				if(isset($_POST['delete'.$i]))
				{
					if(($connect->query("DELETE FROM results WHERE id_users='".$_POST['delete'.$i]."' ")) && ($connect->query("DELETE FROM users WHERE id='".$_POST['delete'.$i]."' ")))
						$j++;
					else
					{
						$_SESSION['connect_error']='<p>Wystąpił błąd! Usunięto '.$j.' wybranych administratorów.</p>';
						forwarding();
					}
				}
			}

			if($j==0)
				$_SESSION['error']='<p>Operacja usuwania nie została wykonana, ponieważ nie wybrano żadnego administratora do usunięcia.</p>';
			else if($j==1)
				$_SESSION['error']='<p>Pomyślnie usunięto wybranego administratora z systemu.</p>';
			else
				$_SESSION['error']='<p>Pomyślnie usunięto wybranych administratorów z systemu.</p>';
			forwarding();

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo 'Przepraszamy, serwer niedostępny.';
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
		{
			connect_error();
		}
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if(!isset($_POST['id_admin']) || $_POST['id_admin']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie udało się zablokować wybranego administratora.</p>';
				forwarding();
			}
			else
			{
				if($connect->query("UPDATE users SET freezing=1 WHERE id='".$_POST['id_admin']."' "))
				{
					$_SESSION['error']='<p>Pomyślnie zablokowano wybranego administratora.</p>';
					forwarding();
				}
				else
					connect_error();
			}

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else if(isset($_POST['unblock']))
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

			if(!isset($_POST['id_admin']) || $_POST['id_admin']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie udało się odblokować wybranego administratora.</p>';
				forwarding();
			}
			else
			{
				if($connect->query("UPDATE users SET freezing=0 WHERE id='".$_POST['id_admin']."' "))
				{
					$_SESSION['error']='<p>Pomyślnie odblokowano wybranego administratora.</p>';
					forwarding();
				}
				else
					connect_error();
			}

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else if(isset($_POST['reset']))
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

			if(!isset($_POST['id_admin']) || $_POST['id_admin']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie udało się zablokować wybranego administratora.</p>';
				forwarding();
			}
			else
			{
				if($connect->query("UPDATE users SET haslo='' WHERE id='".$_POST['id_admin']."' "))
				{
					$_SESSION['error']='<p>Pomyślnie zresetowano hasło wybranego administratora.</p>';
					forwarding();
				}
				else
					connect_error();
			}

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else
	forwarding();