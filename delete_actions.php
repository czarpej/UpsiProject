<?php

session_start();

if(isset($_POST['manual_delete']))
{
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
		{
			$_SESSION['connect_error']='error';
			header('Location: delete.php');
			exit();
		}
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if(!isset($_POST['user_type']))
			{
				$_SESSION['error']='<p>Nie wybrano użytkownika!</p><script>$("#first_class").html("Najpierw wybierz klasę."); $("#action_user").remove();</script>';
				unset($_SESSION['type_class']);
				header('Location: delete.php');
				exit();
			}
			else if(!isset($_POST['action_user']))
			{
				$_SESSION['error']='<p>Nie wybrano jaką akcję podjąć dla wybranego użytkownika!</p><script>$("#first_class").html("Najpierw wybierz klasę."); $("#action_user").remove();</script>';
				unset($_SESSION['type_class']);
				header('Location: delete.php');
				exit();
			}
			else
			{
				unset($_SESSION['type_class']);
				$users_ok=false;
				foreach($_SESSION['users_with_class'] as $value)
				{
					if($value==$_POST['user_type'])
						$users_ok=true;
					break;
				}
				unset($_SESSION['users_with_class']);
				if($users_ok==false)
				{
					$_SESSION['error']='<p>Wybrany uczeń jest niezgodny z rezultatami wyszukań!</p>';
					header('Location: delete.php');
					exit();
				}
				else
				{
					$user=$_POST['user_type'];
					if($_POST['action_user']=='archive')
					{
						if($choosen_user=$connect->query("SELECT id, login, id_klasy FROM users WHERE id='".$user."' "))
						{
							if($choosen_user->num_rows>0)
							{
								$choosen_user_results=$choosen_user->fetch_assoc();
								if($choosen_class=$connect->query("SELECT * FROM classes WHERE id='".$choosen_user_results['id_klasy']."' "))
								{
									if($choosen_class->num_rows>0)
									{
										$choosen_class_results=$choosen_class->fetch_assoc();
										if($connect->query("INSERT INTO archive_users VALUES('', '".$choosen_user_results['login']."', '".$choosen_class_results['class']."".$choosen_class_results['section']."', '".$choosen_class_results['year_started']."-".($choosen_class_results['year_started']+$choosen_class_results['class'])."')"))
										{
											if($choosen_archive_user=$connect->query("SELECT id FROM archive_users"))
											{
												if($choosen_archive_user->num_rows>0)
												{
													if($actual_score=$connect->query("SELECT * FROM results WHERE id_users='".$user."' "))
													{
														if($actual_score->num_rows>0)
														{
															$choosen_id_archive_user='';
															while($choosen_archive_user_results=$choosen_archive_user->fetch_row())
																$choosen_id_archive_user=$choosen_archive_user_results[0];
															while($actual_score_results=$actual_score->fetch_assoc())
															{
																if($connect->query("INSERT INTO archive_results VALUES('', '".$choosen_id_archive_user."', '".$actual_score_results['exam_category']."', '".$actual_score_results['comment']."', '".$actual_score_results['score']."', '".$actual_score_results['mark']."', '".$actual_score_results['count_question']."', '".$actual_score_results['extra_points']."', '".$actual_score_results['multipler_points']."', '".$actual_score_results['date']."', '".$actual_score_results['grade_2']."', '".$actual_score_results['grade_3']."', '".$actual_score_results['grade_4']."', '".$actual_score_results['grade_5']."', '".$actual_score_results['grade_6']."')"))
																	;
																else
																{
																	$_SESSION['connect_error']='<p>Zakończono na dodawaniu użytkownika do archiwum.</p>';
																	header('Location: delete.php');
																	exit();
																}
															}
														}
														else
															;
													}
													else
													{
														$_SESSION['connect_error']='<p>Zakończono na dodawaniu użytkownika do archiwum.</p>';
														header('Location: delete.php');
														exit();
													}
												}
												else
												{
													$_SESSION['connect_error']='<p>Zakończono na dodawaniu użytkownika do archiwum.</p>';
													header('Location: delete.php');
													exit();
												}
											}
											else
											{
												$_SESSION['connect_error']='<p>Zakończono na dodawaniu użytkownika do archiwum.</p>';
												header('Location: delete.php');
												exit();
											}

											if(($connect->query("DELETE FROM results WHERE id_users='".$user."' ")) && ($connect->query("DELETE FROM users WHERE id='".$user."' ")))
											{
												$_SESSION['deleting_ok']='<p>Pomyślnie przeniesiono wybranego użytkownika do archiwum.</p>';
												header('Location: delete.php');
												exit();
											}
											else
											{
												$_SESSION['connect_error']='<p>Dodano użytkownika do archiwum wraz z wynikami. Nie udało się usunąć użytkownika z systemu.</p>';
												header('Location: delete.php');
												exit();
											}

										}
										else
										{
											$_SESSION['connect_error']='error';
											header('Location: delete.php');
											exit();
										}
									}
									else
									{
										$_SESSION['connect_error']='error';
										header('Location: delete.php');
										exit();
									}
								}
								else
								{
									$_SESSION['connect_error']='error';
									header('Location: delete.php');
									exit();
								}
							}
							else
							{
								$_SESSION['error']='<p>Nie znaleziono wybranego użytkownika w systemie!</p>';
								header('Location: delete.php');
								exit();
							}
						}
						else
						{
							$_SESSION['connect_error']='error';
							header('Location: delete.php');
							exit();
						}
					}
					else if($_POST['action_user']=='delete')
					{
						if(($connect->query("DELETE FROM results WHERE id_users='".$user."' ")) && ($connect->query("DELETE FROM users WHERE id='".$user."' ")))
						{
							unset($_SESSION['type_class']);
							$_SESSION['deleting_ok']='<p>Pomyślnie usunięto użytkownika z systemu.</p>';
							header('Location: delete.php');
							exit();
						}
						else
						{
							$_SESSION['connect_error']='error';
							header('Location: delete.php');
							exit();
						}	
					}
					else
						echo '<p>Błąd! Wybrano złe działanie!</p>';
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
else
{
	header('Location: delete.php');
	exit();
}

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/