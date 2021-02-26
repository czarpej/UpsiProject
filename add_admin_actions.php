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
	header('Location: add_admin.php');
	exit();
}

function connect_error()
{
	$_SESSION['connect_error']='error';
	forwarding();
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
			else
			{
				if($isset_admin=$connect->query("SELECT login FROM users WHERE id_klasy=5"))
				{
					$login_ok=true;
					while($isset_admin_results=$isset_admin->fetch_row())
					{
						if($isset_admin_results[0]==$_POST['login'])
						{
							$login_ok=false;
							break;
						}
					}

					if($login_ok!=false)
					{
						if($connect->query("INSERT INTO users VALUES ('', '".$_POST['login']."', '', 5, 0) "))
						{
							$_SESSION['error']='<p>Pomyślnie dodano administratora '.$_POST['login'].' do systemu.</p>';
							forwarding();
						}
						else
							connect_error();
					}
					else
					{
						$_SESSION['error'].='<p>Nie dodano administratora '.$_POST['login'	].', ponieważ istnieje już administrator o takim loginie w systemie.</p>';
						forwarding();
					}
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
else if(isset($_POST['add_serially']))
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
			for($i=0; $i<=$_SESSION['add_serially']; $i++)
			{
				if(isset($_POST['login'.$i]) && $_POST['login'.$i]!='')
				{
					if($isset_admin=$connect->query("SELECT login FROM users WHERE id_klasy=5"))
					{
						$login_ok=true;
						while($isset_admin_results=$isset_admin->fetch_row())
						{
							if($isset_admin_results[0]==$_POST['login'.$i])
							{
								$login_ok=false;
								break;
							}
						}
						if($login_ok!=false)
						{
							if($connect->query("INSERT INTO users VALUES ('', '".$_POST['login'.$i]."', '', 5, 0) "))
								$j++;
							else
							{
								$_SESSION['connect_error']='<p>Wystąpił błąd! Dodano '.$j.' administratorów.</p>';
								forwarding();
							}
						}
						else
							$_SESSION['error'].='<p>Nie dodano administratora '.$_POST['login'.$i].', ponieważ istnieje już administrator o takim loginie w systemie.</p>';
					}
					else
						connect_error();
				}
			}

			if($j==0)
				$_SESSION['error'].='<p>Operacja dodawania nie została wykonana, ponieważ nie wprowadzono żadnego loginu.</p>';
			else if($j==1)
				$_SESSION['error'].='<p>Pomyślnie dodano '.$j.' administratora do systemu.</p>';
			else
				$_SESSION['error'].='<p>Pomyślnie dodano '.$j.' administratorów do systemu.</p>';
			forwarding();

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