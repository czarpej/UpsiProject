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
	header('Location: password_admin.php');
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
			connect_error();
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if(isset($_POST['actual_password']) && (isset($_POST['new_password']) && isset($_POST['repeat_password'])))
			{
				if($_POST['actual_password']=='' || $_POST['new_password']=='' || $_POST['repeat_password']=='')
				{
					$_SESSION['error']='<p>Uzupełnij wszystkie pola!</p>';
					forwarding();
				}
				else
				{
					if($actual_password=$connect->query("SELECT haslo FROM users WHERE id='".$_SESSION['which_admin_id']."' "))
					{
						if($actual_password->num_rows>0)
						{
							$actual_password_results=$actual_password->fetch_row();
							if(!password_verify($_POST['actual_password'], $actual_password_results[0]))
							{
								$_SESSION['error']='<p>Obecne hasło jest nieprawidłowe!</p>';
								forwarding();
							}
							else if($_POST['new_password']!==$_POST['repeat_password'])
							{
								$_SESSION['error']='<p>Podane nowe hasła nie są zgodne!</p>';
								forwarding();
							}
							else
							{
								if($connect->query("UPDATE users SET haslo='".password_hash($_POST['repeat_password'], PASSWORD_DEFAULT)."' WHERE id='".$_SESSION['which_admin_id']."'"))
								{
									$_SESSION['error']='<p>Pomyślnie ustawiono nowe hasło.</p>';
									forwarding();
								}
								else
									connect_error();
							}
						}
						else
							connect_error();
					}
				}
			}
			else if(isset($_POST['new_password']) && isset($_POST['repeat_password']))
			{
				if($_POST['new_password']=='' || $_POST['repeat_password']=='')
				{
					$_SESSION['error']='<p>Uzupełnij wszystkie pola!</p>';
					forwarding();
				}
				else
				{
					if($_POST['new_password']!==$_POST['repeat_password'])
					{
						$_SESSION['error']='<p>Podane nowe hasła nie są zgodne!</p>';
						forwarding();
					}
					else
					{
						if($connect->query("UPDATE users SET haslo='".password_hash($_POST['repeat_password'], PASSWORD_DEFAULT)."' WHERE id='".$_SESSION['which_admin_id']."'"))
						{
							$_SESSION['error']='<p>Pomyślnie ustawiono nowe hasło.</p>';
							forwarding();
						}
						else
							connect_error();
					}
				}
			}
			else
			{
				$_SESSION['error']='<p>Błąd! Uzupełnij wszystkie pola!</p>';
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
else
	forwarding();