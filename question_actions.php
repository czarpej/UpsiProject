<?php
session_start();

if(!isset($_SESSION['admin_log_now']))
{
	header('Location: index.php');
	exit();
}

$_SESSION['admin_log_now']=true;

function save_file($i)
{
  $file='';
  if($i==1)
  	$file=$_FILES['optional_image'];
  else if($i==2)
  	$file=$_FILES['edit_optional_image'];
  if($file['error']==4)
  	return true;
  if(is_uploaded_file($file['tmp_name']))
  {
    if(!move_uploaded_file($file['tmp_name'], 'img/img_to_question/'.$file['name']))
    {
      $_SESSION['error']='<p>Nie udało się skopiować pliku na serwer.</p>';
        return false;
    }
  }
  else
  {
    $_SESSION['error']='<p>Możliwy atak podczas przesyłania pliku! Plik nie został zapisany.</p>';
    return false;
  }
  return true;
}

function check_error($i)
{
  $file='';
  if($i==1)
  	$file=$_FILES['optional_image'];
  else if($i==2)
  	$file=$_FILES['edit_optional_image'];
  if ($file['error'] > 0)
  {
    switch ($file['error'])
    {
      // jest większy niż domyślny maksymalny rozmiar,
      // podany w pliku konfiguracyjnym
      case 1: {$_SESSION['error']='<p>Rozmiar pliku jest zbyt duży!</p>'; break;}

      // jest większy niż wartość pola formularza
      // MAX_FILE_SIZE
      case 2: {$_SESSION['error']='<p>Rozmiar pliku jest zbyt duży!</p>'; break;}

      // plik nie został wysłany w całości
      case 3: {$_SESSION['error']='<p>Plik wysłany tylko częściowo!</p>'; break;}

      // plik nie został wysłany
      case 4: {$_SESSION['error']='<p>Nie wysłano żadnego pliku!</p>'; break;}

      //zaginiony folder tymczasowy
      case 6: {$_SESSION['error']='<p>Brak tymczasowego folderu przechowywania plików!</p>'; break;}

      //błąd zapisu na dysku
      case 7: {$_SESSION['error']='<p>Nie udało się zapisać pliku na serwerze, brak uprawnień do zapisu!</p>'; break;}

      //zbyt długi czas zapisu
      case 8: {$_SESSION['error']='<p>Nie udało się zapisać pliku na dysku, zbyt długi czas zapisu.</p>'; break;}

      // pozostałe błędy
      default: {$_SESSION['error']='<p>Wystąpił błąd podczas wysyłania!</p>'; break;}
    }
    return false;
  }
  else if($file['type']!='image/jpeg' && $file['type']!='image/png')
  {
  	$_SESSION['error']='<p>Wybrany plik ma nieprawidłowy format!</p>';
  	return false;
  }
  return true;
}

function forwarding()
{
	header('Location: question.php');
	exit();
}

function unsetting()
{
	unset($_SESSION['subject_to_add_question']);
	unset($_SESSION['material']);
	setcookie("new_subject", "", time() -3600);
	setcookie("new_material", "", time() -3600);
	unset($_SESSION['optional_image']);
	unset($_SESSION['ans_0']);
	setcookie("ans_0", "", time() -3600);
	for($i=65; $i<=68; $i++)
	{
		unset($_SESSION['ans_'.$i]);
		setcookie("ans_".$i, "", time() -3600);
	}
	unset($_SESSION['ans_good']);
	unset($_SESSION['exists_file']);
	unset($_SESSION['question_id']);
	unset($_SESSION['which_question']);
}

if(isset($_POST['add']))
{
	unset($_SESSION['exam_from_question']);
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

			function elements_ok()
			{
				if(!isset($_FILES['optional_image']))
					return false;

				$ans_ok=false;
				for($i=65; $i<=68; $i++)
				{
					if(!isset($_POST['ans_'.$i]) || $_POST['ans_'.$i]=='')
						return false;

					if(isset($_POST['ans'.$i]))
					{
						$ans_ok=true;
						if($_POST['ans'.$i]!=chr($i))
							return false;
					}
				}

				if($ans_ok==false)
					return false;

				return true;
			}

			function ans_good()
			{
				for($i=65; $i<=68; $i++)
				{
					if(isset($_POST['ans'.$i]))
						$ans_good=$ans_good.''.chr($i).',';
				}
				$ans_good=strtolower($ans_good);
				return $ans_good;
			}

			function file_is($i)
			{
				if($i==1)
					$_SESSION['material']=$_COOKIE['new_material'];
				else
					$_SESSION['material']=$_POST['material'];
				$_SESSION['optional_image']=$_FILES['optional_image']['name'];
				move_uploaded_file($_FILES['optional_image']['tmp_name'], 'img/img_temp/'.$_FILES['optional_image']['name']);
				$_SESSION['ans_0']=$_POST['ans_0'];
				for($i=65; $i<=68; $i++)
					$_SESSION['ans_'.$i]=$_POST['ans_'.$i];
				$_SESSION['ans_good']=ans_good();
				$_SESSION['exists_file']='<script>
				$(".version").html("<input type=submit name=overwrite1 value=Nadpisz class=editing>");
				$(".question_page").css({"visibility":"hidden"});
				$(".info_to_save").css({"opacity":"1", "z-index":"1", "visibility":"visible"});
				</script>';
				forwarding();
			}

			function save_question($connect, $i)
			{
				$material='';
				switch($i)
				{
					case 1: {$material=$_POST['material']; break;}
					case 2: {$material=$_COOKIE['new_material']; break;}
				}
				$image='';
				if($_FILES['optional_image']['name']!='')
					$image=$_FILES['optional_image']['name'];
				if($connect->query("INSERT INTO question VALUES('', '".$_SESSION['subject_to_add_question']."', '".$material."', '".$_POST['ans_0']."', '".$image."', '".$_POST['ans_65']."', '".$_POST['ans_66']."', '".$_POST['ans_67']."', '".$_POST['ans_68']."', '".ans_good()."')"))
				{
					$_SESSION['adding_ok']='<p>Pomyślnie dodano pytanie do bazy pytań.</p>';
					unsetting();
					forwarding();
				}
				else
				{
					$_SESSION['connect_error']='error';
					forwarding();
				}
			}

			if(elements_ok()==false)
			{
				$_SESSION['error']='<p>Błędnie wypełniony fomularz dodawania pytania!</p>';
				forwarding();
			}
			else if($_FILES['optional_image']['error']!=4 && check_error(1)!=true)
				forwarding();
			else if(isset($_POST['material']))
			{
				$material_ok=false;
				foreach($_SESSION['for_material'] as $value)
				{
					if($value==$_POST['material'])
					{
						$material_ok=true;
						break;
					}
				}
				if($material_ok==false)
				{
					$_SESSION['error']='<p>Wybrany zakres materiału jest niezgodny z rezultatami wyszukań!</p>';
					forwarding();
				}
				else
				{
					if(file_exists('img/img_to_question/'.$_FILES['optional_image']['name']) && $_FILES['optional_image']['error']!=4)
					{
						file_is();
					}
					else
					{
						if(save_file(1)==true)
						{
							save_question($connect, 1);
						}
						else
							forwarding();
					}
				}
			}
			else if(isset($_COOKIE['new_material']))
			{
				if($_COOKIE['new_material']=='')
				{
					$_SESSION['error']='<p>Wprowadź nazwę nowego zakresu materiału!</p>';
					forwarding();
				}
				else if(isset($_SESSION['subject_to_add_question']))
				{
					if(file_exists('img/img_to_question/'.$_FILES['optional_image']['name']) && $_FILES['optional_image']['error']!=4)
					{
						file_is(1);
					}
					else
					{
						if(save_file(1)==true)
						{
							save_question($connect, 2);
						}
						else
							forwarding();
					}
				}
				else if(!isset($_COOKIE['new_subject']) || $_COOKIE['new_subject']=='')
				{
					$_SESSION['error']='<p>Brak nazwy nowego przedmiotu!</p>';
					forwarding();
				}
				else
				{
					if(file_exists('img/img_to_question/'.$_FILES['optional_image']['name']) && $_FILES['optional_image']['error']!=4)
					{
						$_SESSION['subject_to_add_question']=$_COOKIE['new_subject'];
						file_is(1);
					}
					else
					{
						if(save_file(1)==true)
						{
							$_SESSION['subject_to_add_question']=$_COOKIE['new_subject'];
							save_question($connect, 2);
						}
						else
							forwarding();
					}
				}
			}
			else
			{
				$_SESSION['error']='<p>Zakres materiału nie może być pusty!</p>';
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
else if(isset($_POST['overwrite1']) || isset($_POST['overwrite2']))
{
	unset($_SESSION['exam_from_question']);
	require_once 'dbconnect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect=new mysqli($address, $db_login, $db_password, $db_name);
		if($connect->connect_errno!=0)
		{
			unsetting();
			$_SESSION['connect_error']='error';
			forwarding();
		}
		else
		{
			$connect->query("SET CHARSET utf8");
			$connect->query("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if(copy('img/img_temp/'.$_SESSION['optional_image'], 'img/img_to_question/'.$_SESSION['optional_image']) && unlink('img/img_temp/'.$_SESSION['optional_image']))
	    	{
	    		if(isset($_POST['overwrite1']))
	    		{
	    			if($connect->query("INSERT INTO question VALUES('', '".$_SESSION['subject_to_add_question']."', '".$_SESSION['material']."', '".$_SESSION['ans_0']."', '".$_SESSION['optional_image']."', '".$_SESSION['ans_65']."', '".$_SESSION['ans_66']."', '".$_SESSION['ans_67']."', '".$_SESSION['ans_68']."', '".$_SESSION['ans_good']."')"))
		      		{
		      			unsetting();
		      			$_SESSION['adding_ok']='<p>Pomyślnie dodano pytanie do bazy pytań.</p>';
		      			forwarding();
		      		}
		      		else
		      		{
		      			unset($_SESSION['exists_file']);
						$_SESSION['connect_error']='error';
						forwarding();
					}
	    		}
	      		else if(isset($_POST['overwrite2']))
	      		{
	      			if($connect->query("UPDATE question SET question='".$_SESSION['ans_0']."', image='".$_SESSION['optional_image']."', ans_a='".$_SESSION['ans_65']."', ans_b='".$_SESSION['ans_66']."', ans_c='".$_SESSION['ans_67']."', ans_d='".$_SESSION['ans_68']."', ans_good='".$_SESSION['ans_good']."' WHERE id_question='".$_SESSION['question_id']."' "))
		      		{
		      			unsetting();
		      			$_SESSION['adding_ok']='<p>Pomyślnie dodano pytanie do bazy pytań.</p>';
		      			forwarding();
		      		}
		      		else
		      		{
		      			unset($_SESSION['exists_file']);
						$_SESSION['connect_error']='error';
						forwarding();
					}
	      		}
	    	}
	      	else
	      	{
	      		unset($_SESSION['exists_file']);
				$_SESSION['error']='<p>Nie udało się nadpisać pliku.</p>';
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
else if(isset($_POST['cancel']))
{
	unset($_SESSION['exam_from_question']);
	unlink('img/img_temp/'.$_SESSION['optional_image']);
	unset($_SESSION['exists_file']);
	$_SESSION['error']='<p>Anulowano dodawanie pytania do bazy.</p>';
	forwarding();
}
else if(isset($_POST['change']))
{
	unset($_SESSION['exam_from_question']);
	function elements_ok()
	{
		if(!isset($_FILES['edit_optional_image']))
			return false;

		$ans_ok=false;
		for($i=65; $i<=68; $i++)
		{
			if(!isset($_POST['edit_ans_'.strtolower(chr($i))]) || $_POST['edit_ans_'.strtolower(chr($i))]=='')
				return false;

			if(isset($_POST['edit_ans'.strtolower(chr($i))]))
			{
				$ans_ok=true;
				if($_POST['edit_ans'.strtolower(chr($i))]!=chr($i))
					return false;
			}
		}

		if($ans_ok==false)
			return false;

		return true;
	}

	function file_is()
	{
		if($_FILES['edit_optional_image']['name']!='')
			$_SESSION['optional_image']=$_FILES['edit_optional_image']['name'];
		else
			$_SESSION['optional_image']=$_POST['old_file'];
		move_uploaded_file($_FILES['edit_optional_image']['tmp_name'], 'img/img_temp/'.$_FILES['edit_optional_image']['name']);
		$_SESSION['ans_0']=$_POST['edit_ans_0'];
		for($i=65; $i<=68; $i++)
			$_SESSION['ans_'.$i]=$_POST['edit_ans_'.strtolower(chr($i))];
		$_SESSION['ans_good']=ans_good();
		$_SESSION['question_id']=$_POST['question_id'];
		$_SESSION['exists_file']='<script>
		$(".version").html("<input type=submit name=overwrite2 value=Nadpisz class=editing>");
		$(".question_page").css({"visibility":"hidden"});
		$(".info_to_save").css({"opacity":"1", "z-index":"1", "visibility":"visible"});
		</script>';
		forwarding();
	}

	function ans_good()
	{
		for($i=65; $i<=68; $i++)
		{
			if(isset($_POST['edit_ans'.strtolower(chr($i))]))
				$ans_good=$ans_good.''.chr($i).',';
		}
		$ans_good=strtolower($ans_good);
		return $ans_good;
	}

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

			if(elements_ok()==false)
			{
				$_SESSION['error']='<p>Błędnie wypełniony fomularz dodawania pytania!</p>';
				forwarding();
			}
			else if($_FILES['edit_optional_image']['error']!=4 && check_error(2)!=true)
				forwarding();
			else if(!isset($_POST['question_id']) || $_POST['question_id']=='')
			{
				$_SESSION['error']='<p>Błąd! Nie odnaleziono wybranego pytania do edycji!</p>';
				forwarding();
			}
			else if(!isset($_POST['old_file']))
			{
				$_SESSION['error']='<p>Błąd! Nie udało się sprawdzić czy do pytania jest przypisany plik graficzny!</p>';
				forwarding();
			}
			else
			{
				if(file_exists('img/img_to_question/'.$_FILES['edit_optional_image']['name']) && $_FILES['edit_optional_image']['error']!=4)
					file_is();
				else
				{
					if(save_file(2)==true)
					{
						$image='';
						if($_FILES['edit_optional_image']['name']!='')
							$image=$_FILES['edit_optional_image']['name'];
						else if(isset($_POST['delete_image']))
							;
						else
							$image=$_POST['old_file'];
						if($connect->query("UPDATE question SET question='".$_POST['edit_ans_0']."', image='".$image."', ans_a='".$_POST['edit_ans_a']."', ans_b='".$_POST['edit_ans_b']."', ans_c='".$_POST['edit_ans_c']."', ans_d='".$_POST['edit_ans_d']."', ans_good='".ans_good()."' WHERE id_question='".$_POST['question_id']."' "))
						{
							unsetting();
							$_SESSION['adding_ok']='<p>Pomyślnie zmieniono wybrane pytanie.</p>';
							forwarding();
						}
						else
						{
							$_SESSION['connect_error']='error';
							forwarding();
						}
					}
					else
						forwarding();
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
else if(isset($_POST['delete']))
{
	unset($_SESSION['exam_from_question']);
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

			$j=0;
			for($i=1; $i<=$_SESSION['which_question']; $i++)
			{
				if(isset($_POST['object'.$i]))
				{
					if(!$connect->query("DELETE FROM question WHERE id_question='".$_POST['object'.$i]."' "))
					{
						$_SESSION['connect_error']='Wystąpił błąd! Usunięto '.$j.' pytań.';
						unsetting();
						forwarding();
					}
					$j++;
				}
			}

			if($j==0)
			{
				$_SESSION['error']='<p>Nie wybrano żadnych pytań do usunięcia!</p>';
				forwarding();
			}
			else
			{
				if($j==1)
					$_SESSION['adding_ok']='<p>Pomyślnie usunięto '.$j.' pytanie.</p>';
				else if($j>=2 && $j<=4)
					$_SESSION['adding_ok']='<p>Pomyślnie usunięto '.$j.' pytania.</p>';
				else
					$_SESSION['adding_ok']='<p>Pomyślnie usunięto '.$j.' pytań.</p>';
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

/*
Project inspired by created Bogdan Pietrzak UPSI
Project idea comes from Michał Peczkis
Project created by Michał Czarnota
*/