<?php 
session_start();

if(!isset($_SESSION['logged'])) 
{
	//header('Location:admin.php');
	//exit();
}

require_once '../db_connect.php';
require_once '../functions.php';
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
			is_logged($connect);
			
			if(isset($_GET['id']))
			{	
				$_GET['id'] = mysqli_real_escape_string($connect,$_GET['id']);
				
				$query_data_pages = "DELETE FROM pages WHERE id ='".$_GET['id']."'";
				if(!$execute_query_p = $connect->query($query_data_pages))
				{
					throw new Exception($connect->error);
					$connect->close();
				}
				else
				{
				$connect->close();
				header('Location:../admin.php');
				exit();
				}
			}
			else
			{
				$connect->close();
				header('Location:../index.php');
				exit();
			}
			
		}
	}
	catch(Exception $error)
	{
		$_SESSION['error'] = $error;
	}
?>