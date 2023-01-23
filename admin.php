<?php
session_start();

if(!isset($_SESSION['logged'])) 
{
	//header('Location:login_panel.php');
//	exit();
}	

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

			is_logged($connect);
			
			$query_data_pages = "SELECT * FROM pages";
			if(!$execute_query_p = $connect->query($query_data_pages))
			{
				throw new Exception($connect->error);
				$connect->close();
			}
			else
			{
				$how_many_pages = $execute_query_p->num_rows;
				
				$query_banners = "SELECT * FROM banners_main_page";
				if(!$execute_query = $connect->query($query_banners))
				{
					throw new Exception($connect->error);
					$connect->close();
				}
				else
				{
					$how_many_banners = $execute_query->num_rows;
				}
			}			
			
		}
	}
	catch(Exception $error)
	{
		$_SESSION['error'] = $error;
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
    <titlePanel administracyjny</title>
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
                <form class="d-flex mt-2" action="index.php" method="GET">
                  <button class="btn btn-outline-success" type="submit">Powrót do strony</button>
                </form>
				
				<form class="d-flex ms-3 mt-2" action="log_out.php" method="GET">
                  <button class="btn btn-outline-warning" type="submit">Wyloguj się</button>
                </form>
				
              </div>
            </div>
          </nav>
    
        <div class="container mt-5">

            <div class="row bg-dark text-light rounded-3">               
                      <div class="mx-auto text-center mb-3 px-5">      						  
						  <h1 class="text-center mt-3 mb-3">Panel administracyjny</h1>
						</div>												

					  <div class="bg-dark-custom col-10 mx-auto mb-5 p-4">
							<h4> Edycja podstron: </h4>
							<a class="btn btn-primary ms-3 mt-3" href="/edit/add_page.php">Dodaj nową podstronę</a>
						  <ul class="navbar-nav me-auto mb-2 mt-3 mb-lg-0">
							  <li class="nav-item">
								<?php
								for($i=1;$i<=$how_many_pages;$i++)
								{
									$row = mysqli_fetch_assoc($execute_query_p);
									echo '<li class="nav-item">
									<a class="btn btn-success ms-3 mb-3" href="http://chimiak.4suns.pl/edit/edit_page.php?id='.$row['id'].'">Edytuj: '.$row['name_page'].'</a>
									<a class="btn btn-danger ms-3 mb-3" href="http://chimiak.4suns.pl/edit/delete_page.php?id='.$row['id'].'">Usuń: '.$row['name_page'].'</a></li>';
								}						
								?>
							</ul>
							<h4 class="my-4"> Edycja zakładki Kontakt: </h4>
							<a class="btn btn-primary ms-3" href="/edit/edit_contact.php">Edytuj</a>
							<h4 class="my-4"> Edycja strony głównej: </h4>
							<a class="btn btn-primary ms-3" href="/edit/edit_main_page.php">Edytuj</a>
							<h4 class="my-4"> Edycja stopki: </h4>
							<a class="btn btn-primary ms-3" href="/edit/edit_footer.php">Edytuj</a>
							
						</div>
                </div>
            </div>
        </div>
	<?php 
	$execute_query->free_result();
	$execute_query_p->free_result();
	$connect->close();
	?> 
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/3.6.0-jquery.min.js"></script>
<script src="js/sticky-nav.js"></script>
</body>
</html>