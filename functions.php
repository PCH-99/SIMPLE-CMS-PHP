<?php

function is_logged($connect)
{
	$q = "SELECT * FROM logg";
	if(!$exe = $connect->query($q))
	{
		throw new Exception($connect->error);
		$connect->close();
	}
	else
	{
		$row = mysqli_fetch_assoc($exe);
		$logged = $row['logged'];
		
		if($logged !== 'true')
		{
			$connect->close();
			
			$path = $_SERVER['REQUEST_URI'];
			$find = 'edit';
			$pos = strpos($path, $find);
			if($pos !== false)
			{
				header('Location:../login_panel.php');
				exit();
			}
			else
			{
				header('Location:login_panel.php');
				exit();
			}
		}
	}
}	
	