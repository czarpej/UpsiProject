<?php
session_start();

if(!isset($_SESSION['user_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['user_now']=true;

?>

<!DOCTYPE html>
<html>
<head>
	<title>UPSI 3.0 <?php if(isset($_SESSION['which_user'])) {echo $_SESSION['which_user'];} ?> </title>

	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">

	<style>
	body
	{
		background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(img/background_user3.png);
	}
	table
	{
		margin-left: 0;
		margin-right: 0;
	}
	</style>

	<script src="jquery-3.3.1.min.js"></script>
	<script>
		if(sessionStorage.getItem('end_time') == null)
		{
			window.location.replace("test.php");
		}
		<?php
		unset($_SESSION['test_now']);
		$_SESSION['score_now']=true;
		header('Location: score.php');
		exit();
		?>
	</script>

</head>

<body>
</body>
</html>