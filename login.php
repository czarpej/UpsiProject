<?php

session_start();

function forwarding()
{
	header('Location: index.php');
	exit();
}

mysqli_report(MYSQLI_REPORT_STRICT);
if (isset($_POST['zaloguj'])) {
	try {
		require_once 'dbconnect.php';
		$connect = new mysqli($address, $db_login, $db_password, $db_name);
		if ($connect->connect_errno != 0)
			throw new Exception(mysqli_connect_errno());
		else {
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			$login = $_POST['login'];
			$password = $_POST['password'];

			if ($login_query = $connect->query(sprintf("SELECT * FROM `users` WHERE `login`='%s' ", mysqli_real_escape_string($connect, $login)))) {
				if ($login_query->num_rows > 0) {
					$login_results = $login_query->fetch_assoc();
					if ($class = $connect->query("SELECT `classes`.`section`FROM classes WHERE `classes`.`id`='" . $login_results['id_klasy'] . "' ")) {
						if ($class->num_rows > 0) {
							$class_results = $class->fetch_assoc();
							if ($password != '' && $login_results['haslo'] == '') {
								$_SESSION['login_error'] = '<p>Błędne dane logowania!</p>';
								$_SESSION['login'] = $login;
								forwarding();
							} else if (password_verify($password, $login_results['haslo']) || $login_results['haslo'] == '') {
								if ($login_results['freezing'] != 0) {
									$_SESSION['login_error'] = '<p>Twoje konto jest zablokowane! Skontaktuj się z administratorem systemu.</p>';
									$_SESSION['login'] = $login;
									forwarding();
								} else {
									if ($class_results['section'] == 'admin' || $class_results['section'] == 'Administratorzy') {
										$_SESSION['admin_log_now'] = true;
										$_SESSION['which_admin'] = $login_results['login'];
										$_SESSION['which_admin_id'] = $login_results['id'];
										$_SESSION['which_admin_class'] = $login_results['id_klasy'];
										header('Location: admin.php');
										exit();
									} else {
										$_SESSION['user_now'] = true;
										$_SESSION['which_user'] = $login_results['login'];
										$_SESSION['which_user_id'] = $login_results['id'];
										$_SESSION['which_user_class'] = $login_results['id_klasy'];
										header('Location: user.php');
										exit();
									}
								}
							} else {
								$_SESSION['login_error'] = '<p>Błędne dane logowania!</p>';
								$_SESSION['login'] = $login;
								forwarding();
							}
						} else {
							$_SESSION['login_error'] = '<p>Błąd! Nie jesteś przypisany do żadnej klasy!</p>';
							$_SESSION['login'] = $login;
							forwarding();
						}
					}
				} else {
					$_SESSION['login_error'] = '<p>Błędne dane logowania!</p>';
					$_SESSION['login'] = $login;
					forwarding();
				}
			}
			$connect->close();
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		echo '<p>Przepraszamy, serwer niedostępny.</p>';
	}
} else
	forwarding();

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/