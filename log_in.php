<?php
session_start();

$login = $_POST['username'];
$pass = $_POST['password'];

if($login != "admin")
{
	$_SESSION['err_username_log'] = "Nie poprawny login :/";
	header('Location:login_panel.php');
	exit();
}
else if($pass != "admin")
{
	$_SESSION['err_password_log'] = "Nie poprawne hasło :/";
	header('Location:login_panel.php');
	exit();
}
else if($login == "admin" AND $pass == "admin")
{
	
$_SESSION['logged'] = true;

require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$connect = new mysqli($host,$db_user,$db_password,$db_name);
		$connect->set_charset("utf8mb4");
		
		if($connect->connect_errno != 0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{				
			$querylog = "UPDATE logg SET logged = 'true' WHERE id = '1'";
			if(!$execute_query_log = $connect->query($querylog))
			{
				throw new Exception($connect->error);
				$connect->close();
			}
			$connect->close();
		}	
	}
	catch(Exception $error)
	{
		$_SESSION['error'] = $error;
		echo $_SESSION['error'];
	}	
header('Location:admin.php');
}
?>