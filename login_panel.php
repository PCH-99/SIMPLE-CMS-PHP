<?php

session_start();

require_once 'db_connect.php';
require_once 'functions.php';

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
			if($logged == 'true')
			{
				$connect->close();
				header('Location:admin.php');
				exit();
			}
		}
	}
}
catch(Exception $error)
{
	$_SESSION['error'] = $error;
}


if(isset($_SESSION['logged'])) 
{
	//header('Location:admin.php');
	//exit();
}	

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel administracyjny">
	<meta name="robots" content="noindex, nofollow">
    <title>Panel administracyjny</title>
    <link href="style/bootstrap.min.css" rel="stylesheet">
	<link rel="canonical" href=""/>
    <link href="style/font-awesome.min.css" rel="stylesheet" />
    <link href="style/style.css" rel="stylesheet" />
    <link rel="icon" href="img/toast.png" type="image/x-icon" />
</head>
<body class="bg-dark">
    
    <div class="mybox">

        <nav id="navbar_top" class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
			<a href="/"><img src="img/logo1.png" alt="logoCMS" width="150" height="auto"/></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse me-3" id="navbarSupportedContent">
				<a class="btn btn-outline-primary ms-3" href="/">Powrót do strony</a>
              </div>
            </div>
          </nav>
    
        <div class="container mt-5">

            <div class="row bg-dark text-light rounded-3">               
                      <div class="mx-auto text-center mb-3 px-5">      						  
						  <h1 class="text-center mt-3 mb-3">Zaloguj się do panelu administracyjnego:</h1>
						</div>												

					  <div class="bg-dark-custom col-10 col-lg-6 mx-auto mb-5 p-4">

							<form action="log_in.php" method="POST">
							
							<?php

							if(isset($_SESSION['error'])) 
							{
								echo "<div class='navb mb-3 px-4 text-danger'>".$_SESSION['error']."</div>";
								unset($_SESSION['error']);
							}
							?>
							<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
							<label>
							<h4>Nazwa użytkownika:</h4><br>
							<input class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 mb-3 form-control" type="text" placeholder="Tutaj wpisz nazwę..." name="username" maxlength="16" <?php if(isset($_SESSION['username_log'])) echo 'value="'.$_SESSION['username_log'].'"'; ?>/>
							</label>
							<?php
								if(isset($_SESSION['err_username_log'])) 
								{
									echo "<div class='navb mb-3 px-4 text-danger'>".$_SESSION['err_username_log']."</div>";
									unset($_SESSION['err_username_log']);
								}
							?>							
							</div>
							<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
							<label>
							<h4>Hasło:</h4><br>
							<input id="show_password" class="form-control my-0" type="password" placeholder="Tutaj wstaw hasło..." name="password" maxlength="20" <?php if(isset($_SESSION['password_log'])) echo 'value="'.$_SESSION['password_log'].'"'; ?>/>	
							
							</label>
							<?php
								if(isset($_SESSION['err_password_log'])) 
								{
									echo "<div class='navb mb-3 px-4 text-danger'>".$_SESSION['err_password_log']."</div>";
									unset($_SESSION['err_password_log']);
								}
							?>
							</div>
							
							<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
							<input class="mb-4 px-3 btn btn-outline-success mx-auto mt-4" type="submit" value="Zaloguj się"/>						
							</div>
						</form>
						
					</div>
                </div>
            </div>
        </div>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/3.6.0-jquery.min.js"></script>
<script src="js/sticky-nav.js"></script>
</body>
</html>