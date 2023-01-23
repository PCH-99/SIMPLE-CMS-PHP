<?php
session_start();
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
			$query_data_pages = "SELECT * FROM pages";
			if(!$execute_query_p = $connect->query($query_data_pages))
			{
				throw new Exception($connect->error);
				$connect->close();
			}
			else
			{				
				$how_many_pages = $execute_query_p->num_rows;
				
				$query_data = "SELECT * FROM contact";
				if(!$execute_query = $connect->query($query_data))
				{
					throw new Exception($connect->error);
					$connect->close();
				}
				else
				{
					$row = mysqli_fetch_assoc($execute_query);
					$canonical = $row['canonical'];
					$content = $row['content'];
					
					$index = $row['meta_robots'];
					$m_title = $row['meta_title'];
					$m_desc = $row['meta_desc'];
					$h1 = $row['h1'];
					$adres = $row['adres'];	
					$iframe = $row['iframe'];
					$cta = $row['cta'];
					$mail = $row['cta_mail'];
					$tel = $row['cta_tel'];
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
    <meta name="description" content="<?=$m_desc?>">
	<meta name="robots" content="<?=$index?>">
    <title><?=$m_title?></title>
    <link href="style/bootstrap.min.css" rel="stylesheet">
	<link rel="canonical" href="<?=$canonical?>"/>
    <link href="style/font-awesome.min.css" rel="stylesheet" />
    <link href="style/style.css" rel="stylesheet" />
    <link rel="icon" href="img/toast.png" type="image/x-icon" />
</head>
<body>
    
    <div class="mybox">

        <nav id="navbar_top" class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
			<a href="/"><img src="img/logo1.png" alt="logoCMS" width="150" height="auto"/></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse me-3" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
					<?php
					for($i=1;$i<=$how_many_pages;$i++)
					{
						$row = mysqli_fetch_assoc($execute_query_p);
						echo '<li class="nav-item">
						<a class="nav-link" href="http://chimiak.4suns.pl/page.php?id='.$row['id'].'">'.$row['name_page'].'</a></li>';
					}						
					?>
				  <li class="nav-item">
                    <a class="nav-link" href="contact.php">Kontakt</a>
                  </li>
                </ul>
                <form class="d-flex" action="admin.php" method="GET">
                  <button class="btn btn-outline-success" type="submit">Panel admina</button>
                </form>
              </div>
            </div>
          </nav>
    
        <div class="container mt-5">

            <div class="row bg-dark text-light rounded-3">               
                <!---------------------------------------------------------------------------------------------------------------------------------------------->

                      <div class="mx-auto text-center mb-3 px-5">      						  
						  <div class="d-flex flex-column flex-lg-row align-items-md-stretch justify-content-md-center gap-3 mb-4">
							<div class="d-inline-block v-align-middle fs-5">
							  <div class="bd-code-snippet">   <div class="bd-clipboard">     <img src="img/logo1.png" alt="logoCMS" width="450" height="auto" class="img-fluid">  </div><div class="highlight"><pre tabindex="0" class="chroma"><code class="language-sh" data-lang="sh"><span class="line"><span class="cl">Prosty system zarządzania treścią</span></span></code></pre></div></div>
							</div>						
						  </div>
						  <h1 class="text-center mt-3 mb-3"><?=$h1?></h1>
						  <p class="mb-0">
							Wersja <strong>v1.0.0</strong>
							<span class="px-1">·</span>
							<span class="link-secondary">PHP</span>
							<span class="px-1">·</span>
							<span class="link-secondary">HTML</span>
							<span class="px-1">·</span>
							<span class="link-secondary">JAVASCRIPT</span>
							<span class="px-1">·</span>
							<span class="link-secondary">CSS</span>
							<span class="px-1">·</span>
							<span class="link-secondary">MySQL</span>
							<span class="px-1">·</span>
							<span class="link-secondary">Bootstrap</span>
						  </p>
						  
						  <div class="fs-3 mt-4">
							<span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
							<span><?=$adres?></span>
						</div> 
						</div>											
					  <!------------------------------------------------------------------------------------------------------------------------------------------>
					  
					  <div class="row mx-auto p-5">
						 
						 <?=$iframe?>
							
						  </div>
						

						 <div class="row">  
						  
							<div>
							 <div class="mx-auto my-4 shadow-lg p-5 bg-light col-12 col-md-6 col-lg-4">
                               <h3 class="h2 text-dark"><?=$cta?></h3>
								<a href="mailto:<?=$mail?>" class="d-block mt-4 btn btn-success"><?=$mail?></a>
                                <a href="tel:<?=$tel?>" class="mt-4 btn btn-success d-block"><?=$tel?></a><form>                            
							</div>
							  
							</div>
						
						</div>
                </div>
            </div>          
        </div>     
        
<?php 
	require_once 'footer.php';
	$connect->close();
	$execute_query->free_result();
	$execute_query_p->free_result();
?> 
</body>
</html>