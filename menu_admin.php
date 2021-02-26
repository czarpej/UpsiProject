<?php

require_once 'dbconnect.php';
mysqli_report(MYSQLI_REPORT_STRICT);
try
{
	$connect=new mysqli($address, $db_login, $db_password, $db_name);
	if($connect->connect_errno!=0)
		throw new Exception($connect->error);
	else
	{
		$connect->query("SET CHARSET utf8");
		$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if($which_admin=$connect->query("SELECT id FROM users WHERE id_klasy=2 AND id_klasy='".$_SESSION['which_admin_class']."' "))
		{
			$menu_admin='
			<nav class="navbar navbar-dark navbar-expand-md">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu"><span class="navbar-toggler-icon"></span></button>
				<div class="collapse navbar-collapse ml-3" id="menu">
					<ol class="navbar-nav ml-auto">';
					if($which_admin->num_rows>0)
					{
						$menu_admin.= '
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Administratorzy</a>
							<div class="dropdown-menu">
								<a class="dropdown-item"><form action="show_admin.php" method="post"><button name="show"><i class="icon-crown"></i> Zarządzaj</button></form></a>
								<a class="dropdown-item"><form action="add_admin.php" method="post"><button name="adding"><i class="icon-crown-plus"></i> Dodaj</button></form></a>
							</div>
						</li>';
					}
					else
					{
						$menu_admin.= '
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Działania</a>
							<div class="dropdown-menu">
								<a class="dropdown-item"><form action="password_admin.php" method="post"><button name="password"><i class="icon-key"></i> Zmień hasło</button></form></a>
								<a class="dropdown-item"><form action="structure_admin.php" method="post"><button name="adding"><i class="icon-cog"></i> Struktura konta</button></form></a>
							</div>
						</li>
						';
					}
					$menu_admin.= '
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Klasy</a>
							<div class="dropdown-menu">
								<a class="dropdown-item"><form action="show_class.php" method="post"><button name="show"><i class="icon-clipboard"></i> Zarządzaj</button></form></a>
								<a class="dropdown-item"><form action="add_class.php" method="post"><button name="adding"><i class="icon-plus"></i> Dodaj</button></form></a>
								<a class="dropdown-item"><form action="delete_class.php" method="post"><button name="deleting"><i class="icon-trash-empty"></i> Usuń</button></form></a>
							</div>
						</li>
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Użytkownicy</a>
							<div class="dropdown-menu">
								<a class="dropdown-item"><form action="show.php" method="post"><button name="show"><i class="icon-clipboard"></i> Zarządzaj</button></form></a>
								<a class="dropdown-item"><form action="add.php" method="post"><button name="adding"><i class="icon-user-plus"></i> Dodaj</button></form></a>
								<a class="dropdown-item"><form action="delete.php" method="post"><button name="deleting"><i class="icon-user-times"></i> Usuń</button></form></a>
							</div>
						</li>
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Testy</a>
							<div class="dropdown-menu">
								<a class="dropdown-item"><form action="admin.php" method="post"><button name="first_look"><i class="icon-home"></i> Aktywne testy</button></form></a>
								<a class="dropdown-item"><form method="post" action="new_test.php"><button name="new_test"><i class="icon-params"></i> Ustaw test</button></form></a>
								<a class="dropdown-item"><form action="report.php" method="post"><button name="report"><i class="icon-graduation-cap"></i> Raport ocen</button></form></a>
								<a class="dropdown-item"><form action="question.php" method="post"><button name="question"><i class="icon-book"></i> Pytania</button></form></a>
							</div>
						</li>
						<li class="nav-item"><a class="nav-link"><form action="archives.php" method="post"><button name="archives" style="text-align: left;"><i class="icon-archive"></i> Archiwum</button></form></a></li>
						<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Wsparcie</a>
							<div class="dropdown-menu">
								<a class="dropdown-item"><form action="comment.php" method="post"><input type="submit" value="Opinie użytkowników" name="comment"></form></a>
								<a class="dropdown-item"><form action="users_files.php" method="post"><input type="submit" value="Pliki użytkowników" name="users_files"></form></a>
							</div>
						</li>
						<li class="nav-item"><a class="nav-link"><form action="logout.php" method="post"><button name="logout" style="text-align: left;">Wyloguj <i class="icon-logout"></i></button></form></a></li>
					</ol>
				</div>
			</nav>
			';
		}

		

		$connect->close();
	}
}
catch(Exception $e)
{
	echo '<p>Przepraszamy, serwer niedostępny.</p>';
}