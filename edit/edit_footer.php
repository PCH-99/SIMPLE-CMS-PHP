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
			
			if(isset($_POST['fb']))
			{	
				$_POST['fb'] = mysqli_real_escape_string($connect,$_POST['fb']);
				$_POST['pin'] = mysqli_real_escape_string($connect,$_POST['pin']);
				$_POST['skype'] = mysqli_real_escape_string($connect,$_POST['skype']);
				$_POST['tw'] = mysqli_real_escape_string($connect,$_POST['tw']);
				
				$up_links = [];
				
				for($i=0;$i<=2;$i++)
				{
					$id = $i + 1;
					$_POST['title_wid'.$i] = mysqli_real_escape_string($connect,$_POST['title_wid'.$i]);
					
					for($j=1;$j<=5;$j++)
					{
						$_POST['adress'.$j.$id] = mysqli_real_escape_string($connect,$_POST['adress'.$j.$id]);
						$_POST['anchor'.$j.$id] = mysqli_real_escape_string($connect,$_POST['anchor'.$j.$id]);
						$_POST['follow'.$j.$id] = mysqli_real_escape_string($connect,$_POST['follow'.$j.$id]);
						
						array_push($up_links, '
						UPDATE footer_widget_'.$id.'
						SET
						link = "'.$_POST['adress'.$j.$id].'",
						anchor = "'.$_POST['anchor'.$j.$id].'",
						nofollow = "'.$_POST['follow'.$j.$id].'"
						WHERE id = "'.$j.'"
						');
					}
				}

				$query_widget_and_social = "
					UPDATE name_footer_widget
					SET 
					wid1 = '".$_POST['title_wid1']."',
					wid2 = '".$_POST['title_wid2']."',
					wid3 = '".$_POST['title_wid3']."',
					fb = '".$_POST['fb']."',
					pin = '".$_POST['pin']."',
					skype = '".$_POST['skype']."',
					tw = '".$_POST['tw']."'
					WHERE id = 1";
				if(!$connect->query($query_widget_and_social))
				{
					throw new Exception($connect->error);
					$connect->close();
				}
				else 
				{
					for($q=0;$q<=14;$q++)
					{
						if(!$connect->query($up_links[$q]))
						{
							throw new Exception($connect->error);
							$connect->close();
						}
						$success_changes = "Pomyślnie zapisano zmiany :)";
					}
				}
			}
		}
	}
	catch(Exception $error)
	{
		$_SESSION['error'] = $error;
		echo $_SESSION['error'];
	}
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow">
    <title>Edytujesz: stopkę</title>
    <link href="../style/bootstrap.min.css" rel="stylesheet">
    <link href="../style/font-awesome.min.css" rel="stylesheet" />
    <link href="../style/style.css" rel="stylesheet" />
    <link rel="icon" href="../img/toast.png" type="image/x-icon" />
</head>
<body class="bg-dark">
    
    <div class="mybox">

     <nav id="navbar_top" class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
			<a href="/"><img src="../img/logo1.png" alt="logoCMS" width="150" height="auto"/></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse me-3" id="navbarSupportedContent">
                <form class="d-flex" action="http://chimiak.4suns.pl/admin.php" method="GET">
                  <button class="btn btn-outline-success" type="submit">Powrót do panelu admina</button>
                </form>
              </div>
            </div>
          </nav>
    
        <div class="container mt-5">
            <div class="row bg-dark text-light rounded-3">               
				<div class="col-12 col-md-10 col-lg-8 mx-auto text-center mb-3 px-5"> 
				<?php
				if(isset($success_changes))
				{
					echo '<h3 class="text-center mt-3 mb-3 alert alert-success">'.$success_changes.'</h3>';
				}
				?>
				  <h1 class="text-center mt-3 mb-3">Edytujesz stopkę</h1>
				  <form action="edit_footer.php" method="POST">
						
						<?php
						for($d=1;$d<=3;$d++)
						{	try
							{
								$query_footer = "SELECT * FROM footer_widget_".$d;
								$query_footer_w = "SELECT * FROM name_footer_widget";
								if(!$execute_query_footer_links = $connect->query($query_footer) OR !$execute_query_widget = $connect->query($query_footer_w))
								{
									throw new Exception($connect->error);
								}
								else
								{
									$how_many_links = $execute_query_footer_links->num_rows;
									$row_widget = mysqli_fetch_assoc($execute_query_widget);
									
									echo '
									<h4 class="my-4 mt-5"> Zmień tytuł sekcji #'.$d.': </h4>
									<input class="form-control" type="text" name="title_wid'.$d.'" value="'.$row_widget['wid'.$d].'">
									
									<button class="btn btn-outline-warning mt-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample'.$d.'" aria-expanded="false" aria-controls="collapseExample'.$d.'">
										Rozwiń i edytuj linki w tej sekcji
									</button>
  
									<div class="collapse" id="collapseExample'.$d.'">';
												
									for($j=1;$j<=$how_many_links;$j++)
										{
											$row = mysqli_fetch_assoc($execute_query_footer_links);
											
											if($row['nofollow'] == "dofollow")
											{												
												$selected1 = "selected";
												$selected2 = "";
											}
											else if($row['nofollow'] == "nofollow")
											{
												$selected1 = "";
												$selected2 = "selected";
											}
											
											echo '
											<div class="bg-success px-4 py-2 mt-5">
											<h5 class="my-4"> Zmień adres linku #'.$j.': </h5>
											<input class="form-control" type="text" name="adress'.$j.$d.'" value="'.$row['link'].'">
											
											<h6 class="my-4"> Zmień anchor link #'.$j.': </h6>
											<input class="form-control" type="text" name="anchor'.$j.$d.'" value="'.$row['anchor'].'">
											
											<h6 class="my-4"> Ustawienie atrybutu rel: </h6>
											<select name="follow'.$j.$d.'" class="form-control mb-4">
											<option '.$selected1.'>dofollow</optiom>
											<option '.$selected2.'>nofollow</optiom>
											</select>
											</div>
											';
										};
									echo '</div>';
								}		  
							}
							catch(Exception $errorf)
							{
								$_SESSION['error'] = $errorf;
								echo $_SESSION['error'];
							}
						}
						try
						{
							$query_footer_sm = "SELECT * FROM name_footer_widget";
							if(!$execute_query_sm = $connect->query($query_footer_sm))
							{
								throw new Exception($connect->error);
							}
							else
							{
								$row_sm = mysqli_fetch_assoc($execute_query_sm);
								
								echo '
									  <div class="bg-info px-4 py-2 mt-5">
										<h4 class="my-4 mt-2"> Dodaj link do Facebooka: </h4>
										<input class="form-control" type="text" name="fb" value="'.$row_sm['fb'].'">
										<h4 class="my-4"> Dodaj link do Pinteresta: </h4>
										<input class="form-control" type="mail" name="pin" value="'.$row_sm['pin'].'">
										<h4 class="my-4"> Dodaj link do Skype: </h4>
										<input class="form-control mb-4" type="tel" name="skype" value="'.$row_sm['skype'].'">
										<h4 class="my-4"> Dodaj link do Twittera: </h4>
										<input class="form-control mb-4" type="tel" name="tw" value="'.$row_sm['tw'].'">
									</div>
							';
							}		  
						}
						catch(Exception $errorf)
						{
							$_SESSION['error'] = $errorf;
							echo $_SESSION['error'];
						}
						$execute_query_footer_links->free_result();	
						$connect->close();
	
						?>
						<button class="btn btn-success mt-5 d-block mx-auto" type="submit">Zapisz zmiany</button>
				  </form>
				  
				</div>												
            </div>
		</div>          
	</div>     
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/3.6.0-jquery.min.js"></script>
<script src="../js/sticky-nav.js"></script>
</body>
</html>