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
			
			if(isset($_POST['content']))
			{	
				$_POST['name'] = mysqli_real_escape_string($connect,$_POST['name']);
				$_POST['meta_robots'] = mysqli_real_escape_string($connect,$_POST['meta_robots']);
				$_POST['meta_title'] = mysqli_real_escape_string($connect,$_POST['meta_title']);
				$_POST['meta_desc'] = mysqli_real_escape_string($connect,$_POST['meta_desc']);
				$_POST['canonical'] = mysqli_real_escape_string($connect,$_POST['canonical']);
				
				$_POST['h1'] = mysqli_real_escape_string($connect,$_POST['h1']);
				$_POST['lead'] = mysqli_real_escape_string($connect,$_POST['lead']);
				$_POST['cta'] = mysqli_real_escape_string($connect,$_POST['cta']);
				$_POST['cta_mail'] = mysqli_real_escape_string($connect,$_POST['cta_mail']);
				$_POST['cta_tel'] = mysqli_real_escape_string($connect,$_POST['cta_tel']);
				$_POST['content'] = mysqli_real_escape_string($connect,$_POST['content']);

				$query_data_page = "
					UPDATE pages
					SET 
					name_page = '".$_POST['name']."', meta_robots = '".$_POST['meta_robots']."', meta_title = '".$_POST['meta_title']."', 
					meta_desc = '".$_POST['meta_desc']."', canonical = '".$_POST['canonical']."', h1 = '".$_POST['h1']."', 
					lead = '".$_POST['lead']."', cta = '".$_POST['cta']."', cta_mail = '".$_POST['cta_mail']."', 
					cta_tel = '".$_POST['cta_tel']."', content = '".$_POST['content']."'
					WHERE id = ".$_POST['id'];
				if(!$execute_query = $connect->query($query_data_page))
				{
					throw new Exception($connect->error);
					$connect->close();
				}
				$success_changes = "Pomy??lnie zapisano zmiany :)";
				$id = $_POST['id'];
			}
			else $id = $_GET['id'];

			
			$query_data_page = "SELECT * FROM pages WHERE id = ".$id."";
			if(!$execute_query = $connect->query($query_data_page))
			{
				throw new Exception($connect->error);
				$connect->close();
			}
			else
			{				
				$row = mysqli_fetch_assoc($execute_query);
				$canonical = $row['canonical'];
				$content = $row['content'];
				$name = $row['name_page'];
				
				$index = $row['meta_robots'];
				$m_title = $row['meta_title'];
				$m_desc = $row['meta_desc'];
				$h1 = $row['h1'];
				$lead = $row['lead'];	
				$text = $row['content'];
				$cta = $row['cta'];
				$mail = $row['cta_mail'];
				$tel = $row['cta_tel'];
				
				$execute_query->free_result();
				$connect->close();
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
	<meta name="robots" content="noindex, nofollow">
    <title>Edytujesz: <?=$m_title?></title>
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
                  <button class="btn btn-outline-success" type="submit">Powr??t do panelu admina</button>
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
				  <h1 class="text-center mt-3 mb-3">Edytujesz: <?=$h1?></h1>
				  <form action="edit_page.php?id=<?=$id?>" method="POST">
						<div class="bg-danger px-4 py-2 mt-5">
						<h4 class="my-4"> Edycja meta tytu??u: </h4>
						<input class="form-control" type="text" name="meta_title" value="<?=$m_title?>">
						<h4 class="my-4"> Edycja meta opisu: </h4>
						<input class="form-control mb-4" type="text" name="meta_desc" value="<?=$m_desc?>">
						</div>
						<h4 class="my-4"> Edycja canonical: </h4>
						<input class="form-control" type="text" name="canonical" value="<?=$canonical?>">
						<h4 class="my-4"> Edycja nazwy podstrony (* w menu): </h4>
						<input class="form-control" type="text" name="name" value="<?=$name?>">
						
						<h4 class="my-4"> Edycja nag????wka H1: </h4>
						<input class="form-control" type="text" name="h1" value="<?=$h1?>">
						<h4 class="my-4"> Edycja leada: </h4>
						<input class="form-control" type="text" name="lead" value="<?=$lead?>">
						
						<div class="bg-success px-4 py-2 mt-5">
						<h4 class="my-4"> Edycja bannera CTA: </h4>
						<input class="form-control" type="text" name="cta" value="<?=$cta?>">
						<h4 class="my-4"> Edycja maila w bannerze CTA: </h4>
						<input class="form-control" type="mail" name="cta_mail" value="<?=$mail?>">
						<h4 class="my-4"> Edycja telefonu w bannerze CTA: </h4>
						<input class="form-control mb-4" type="tel" name="cta_tel" value="<?=$tel?>">
						</div>
						
						<h4 class="my-4"> Ustawienie znacznika meta-robots: </h4>
						<select name="meta_robots" class="form-control">
						<option <?php if($index == "index") echo 'selected'; ?>>index</optiom>
						<option <?php if($index == "noindex") echo 'selected'; ?>>noindex</optiom>
						</select>
						
						<h4 class="my-4"> Edycja tre??ci: </h4>
						<textarea class="form-control" name="content"><?=$text?></textarea>
						
						<input type="hidden" name="id" value="<?=$id?>">
						
						<button class="btn btn-success mt-5" type="submit">Zapisz zmiany</button>
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