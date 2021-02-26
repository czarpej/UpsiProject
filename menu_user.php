<?php

$menu_user='
<nav class="navbar navbar-dark navbar-expand-sm">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu"><span class="navbar-toggler-icon"></span></button>
	<div class="collapse navbar-collapse ml-3" id="menu">
		<ol class="navbar-nav ml-auto">
			<li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" role="button">Testy</a>
				<div class="dropdown-menu">
					<a class="dropdown-item"><form method="post" action="user.php"><button name="actual_test"><i class="icon-home"></i> Aktualny test</button></form></a>
					<a class="dropdown-item"><form method="post" action="history.php"><button name="tests_history"><i class="icon-history"></i> Historia testów</button></form></a>
				</div class="dropdown">
			</li>
			<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Działania</a>
				<div class="dropdown-menu">
					<a class="dropdown-item"><form method="post" action="edit_user.php"><button name="change_password"><i class="icon-key"></i> Zmień hasło</button></form></a>
				</div>
			</li>
			<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button">Wsparcie</a>
				<div class="dropdown-menu">
					<a class="dropdown-item"><form method="post" action="reviews.php"><input type="submit" name="reviews" value="Propozycje ulepszeń"></form></a>
					<a class="dropdown-item"><form method="post" action="your_files.php"><button name="your_files"><i class="icon-docs"></i> Twoje pliki</button></form></a>
					<a class="dropdown-item"><form method="post" action="source_code.php"><button name="source_code"><i class="icon-file-code"></i> Kod źródłowy</button></form></a>
				</div>
			</li>
			<li class="nav-item"><a class="nav-link"><form action="logout.php" method="post"><button name="logout" style="text-align: left;"><i class="icon-logout"></i> Wyloguj</button></form></a></li>
		</ol>
	</div>
</nav>
';