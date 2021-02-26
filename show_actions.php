<?php

session_start();

function forwarding()
{
	header('Location: show.php');
	exit();
}

function connect_error()
{
	$_SESSION['connect_error']='error';
	forwarding();
}

if(isset($_SESSION['archive']))
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

			if($checked_users=$connect->query("SELECT id, login, id_klasy FROM users"))
			{
				if($checked_users->num_rows>0)
				{
					$i=0;
					$users_id=Array();
					while($checked_users_results=$checked_users->fetch_assoc())
					{
						if(isset($_POST['to_delete'.$checked_users_results['id']]))
						{
							if($class_users=$connect->query("SELECT * FROM classes WHERE id='".$checked_users_results['id_klasy']."' "))
							{
								if($class_users->num_rows>0)
								{
									$class_users_results=$class_users->fetch_assoc();
									if($connect->query("INSERT INTO archive_users VALUES('', '".$checked_users_results['login']."', '".$class_users_results['class']."".$class_users_results['section']."', '".$class_users_results['year_started']."-".($class_users_results['year_started']+$class_users_results['class'])."')"))
									{
										if($now_archive_users=$connect->query("SELECT id FROM archive_users"))
										{
											if($now_archive_users->num_rows>0)
											{
												$now_archive_users_id='';
												while($now_archive_users_results=$now_archive_users->fetch_row())
													$now_archive_users_id=$now_archive_users_results[0];
												if($actual_score=$connect->query("SELECT * FROM results WHERE id_users='".$checked_users_results['id']."' "))
												{
													if($actual_score->num_rows>0)
													{
														while($actual_score_results=$actual_score->fetch_assoc())
														{
															if($connect->query("INSERT INTO archive_results VALUES('', '".$now_archive_users_id."', '".$actual_score_results['exam_type']."', '".$actual_score_results['comment']."', '".$actual_score_results['score']."', '".$actual_score_results['mark']."', '".$actual_score_results['count_question']."', '".$actual_score_results['extra_points']."', '".$actual_score_results['multipler_points']."', '".$actual_score_results['date']."', '".$actual_score_results['grade_2']."', '".$actual_score_results['grade_3']."', '".$actual_score_results['grade_4']."', '".$actual_score_results['grade_5']."', '".$actual_score_results['grade_6']."')"))
																;
															else
																connect_error();
														}
													}
													else
														;
												}
												else
													connect_error();
											}
											else
												connect_error();
										}
										else
											connect_error();
									}
									else
										connect_error();
								}
								else
									;
							}
							$users_id[$i]=$_POST['to_delete'.$checked_users_results['id']];
							$i++;
						}
					}
					$k=0;
					for($l=0; $l<count($users_id); $l++)
					{
						if(($connect->query("DELETE FROM results WHERE id_users='".$users_id[$l]."' ")) && ($connect->query("DELETE FROM users WHERE id='".$users_id[$l]."' ")))
							$k++;
						else
						{
							$_SESSION['connect_error']='<p>Dodano użytkowników do archiwum razem z wynikami.<br>Usunięto '.$l.' użytkowników z systemu wraz z wynikami.</p>';
							forwarding();
						}
					}
					if($k==0)
						$_SESSION['action_ok']='<p>Nie wybrano żadnego użytkownika do archiwizacji!</p>';
					else if($k==1)
						$_SESSION['action_ok']='<p>Pomyślnie przeniesiono '.$k.' użytkownika do archiwum.</p>';
					else
						$_SESSION['action_ok']='<p>Pomyślnie przeniesiono '.$k.' użytkowników do archiwum.</p>';
					forwarding();
				}
				else
				{
					$_SESSION['error']='<p>Błąd! Brak użytkowników z systemie!</p>';
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
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else if(isset($_SESSION['delete']))
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

			if($checked_users=$connect->query("SELECT id FROM users"))
			{
				if($checked_users->num_rows>0)
				{
					$i=0;
					$users_id=Array();
					while($checked_users_results=$checked_users->fetch_assoc())
					{
						if(isset($_POST['to_delete'.$checked_users_results['id']]))
						{
							$users_id[$i]=$checked_users_results['id'];
							$i++;
						}
					}
					$k=0;
					for($l=0; $l<count($users_id); $l++)
					{
						if(($connect->query("DELETE FROM results WHERE id_users='".$users_id[$l]."' ")) && ($connect->query("DELETE FROM users WHERE id='".$users_id[$l]."' ")))
							$k++;
						else
						{
							$_SESSION['connect_error']='<p>Wystąpił błąd! Usunięto '.$l.' użytkowników z systemu.</p>';
							forwarding();
						}
					}
					if($k==0)
						$_SESSION['action_ok']='<p>Nie wybrano żadnego użytkownika do usunięcia!</p>';
					else if($k==1)
						$_SESSION['action_ok']='<p>Pomyślnie usunięto '.$k.' użytkownika z systemu.</p>';
					else
						$_SESSION['action_ok']='<p>Pomyślnie usunięto '.$k.' użytkowników z systemu.</p>';
					forwarding();
				}
				else
				{
					$_SESSION['error']='<p>Błąd! Brak użytkowników z systemie!</p>';
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
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else if(isset($_POST['unblock_one']))
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

			if(!isset($_POST['which_block']) || $_POST['which_block']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie udało się odblokować użytkownika.</p>';
				forwarding();
			}
			else
			{
				if($connect->query("UPDATE users SET freezing=0 WHERE id='".$_POST['which_block']."' "))
				{
					$_SESSION['action_ok']='<p>Pomyślnie odblokowano użytkownika.</p>';
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
else if(isset($_POST['mass_unblock']))
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

			$k=0;
			for($i=0; $i<=count($_SESSION['block_users']); $i++)
			{
				if(isset($_POST['unblock'.$i]))
				{
					if($connect->query("UPDATE users SET freezing=0 WHERE id='".$_SESSION['block_users'][$i]."' "))
						$k++;
					else
					{
						$_SESSION['connect_error']='<p>Wystąpił błąd! Odblokowano '.$i.' użytkowników.</p>';
						forwarding();
					}
				}
			}

			if($k==0)
				$_SESSION['action_ok']='<p>Nie wybrano żadnego użytkownika do odblokowania!</p>';
			else if($k==1)
				$_SESSION['action_ok']='<p>Pomyślnie odblokowano '.$k.' użytkownika.</p>';
			else
				$_SESSION['action_ok']='<p>Pomyślnie odblokowano '.$k.' użytkowników.</p>';
			forwarding();

			$connect->close();
		}
	}
	catch(Exception $e)
	{
		echo 'Przepraszamy, serwer niedostępny.';
	}
}
else if(isset($_POST['block_one']))
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

			if(!isset($_POST['which_block']) || $_POST['which_block']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie udało się zablokować użytkownika.</p>';
				forwarding();
			}
			else
			{
				if($connect->query("UPDATE users SET freezing=1 WHERE id='".$_POST['which_block']."' "))
				{
					$_SESSION['action_ok']='<p>Pomyślnie zablokowano wybranego użytkownika.</p>';
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