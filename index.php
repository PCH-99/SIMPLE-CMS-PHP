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
			$query_data_main_page = "SELECT * FROM main_page";
			if(!$execute_query = $connect->query($query_data_main_page))
			{
				throw new Exception($connect->error);
				$connect->close();
			}
			else
			{				
				$row = mysqli_fetch_assoc($execute_query);
				$canonical = $row['canonical'];
				$content = $row['content'];
				
				$chars = strlen(html_entity_decode(strip_tags($content)));
				
				$index = $row['meta_robots'];
				$m_title = $row['meta_title'];
				$m_desc = $row['meta_desc'];
				$h1 = $row['h1'];
				$lead = $row['lead'];
				
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
					$with_title = 0;
					$without_title = 0;
					
					$with_desc = 0;
					$without_desc = 0;
					
					$page_indexed = 0;
					$page_noindexed = 0;
					
					for($i=1;$i<=$how_many_pages;$i++)
					{
						$row = mysqli_fetch_assoc($execute_query_p);
						echo '<li class="nav-item">
						<a class="nav-link" href="http://chimiak.4suns.pl/page.php?id='.$row['id'].'">'.$row['name_page'].'</a></li>';
						$chars = $chars + strlen(html_entity_decode(strip_tags($row['content'])));
						if($row['meta_desc'] !== "") $with_desc += 1; else $without_desc += 1;
						if($row['meta_title'] !== "") $with_title += 1; else $without_title += 1;
						if($row['meta_robots'] !== "noindex") $page_indexed += 1; else $page_noindexed += 1;
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

                      <div class="mx-auto text-center mb-3 px-2">      						  
						  <div class="d-flex flex-column flex-lg-row align-items-md-stretch justify-content-md-center gap-3 mb-4">
							<div class="d-inline-block v-align-middle fs-5">
							  <div class="bd-code-snippet">   <div class="bd-clipboard"><img src="img/logo1.png" alt="logoCMS" width="450" height="auto" class="img-fluid">  </div><div class="highlight"><pre tabindex="0" class="chroma"><code class="language-sh" data-lang="sh"><span class="line"><span class="cl">Prosty system zarządzania treścią</span></span></code></pre></div></div>
							</div>						
						  </div>
						  <h1 class="text-center mt-3 mb-3"><?=$h1?></h1>
						  <p class="lead mb-4"><?=$lead?></p>
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
						</div>												

                      <!---------------------------------------------Banners------------------------------------------------------------------------------------------------>

					<?php
					for($i=1;$i<=$how_many_banners;$i++)
					{
						$row = mysqli_fetch_assoc($execute_query);
						echo '<div class="card text-bg-dark text-dark mx-auto my-3 banner-cms">
								<div class="card-body">
								  <h3 class="card-title mx-auto">'.$row['title'].'</h3>
								  <p class="card-text">'.$row['content'].'</p>
								  <button class="btn btn-success mx-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample1" aria-controls="offcanvasExample">'.$row['cta'].'</button>
								</div>
							</div>';
					}						
					?>

					  <div class="row">
						  <div class="col-12 col-md-6 col-lg-4">
							<div class="offcanvas-body">

                               <aside>

                              <div class="list-group mt-5 text-center border border-light">
                                <div class="list-group-item list-group-item-action active bg-danger text-light p-3 border-2 border-light">
                                    <h4>Statystyki:</h4>
                                </div>
                                <div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Ilość podstron
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                           <?=$how_many_pages;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Ilość podstron noindex
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          <?=$page_noindexed;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Ilość podstron index
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          <?=$page_indexed;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Ilość znaków contentu w serwisie
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                           <?=$chars;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Podstrony z title
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          <?=$with_title;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Podstrony bez title
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          <?=$without_title;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Podstrony z meta description
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          <?=$with_desc;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>
								
								<div class="list-group-item list-group-item-action bg-dark border border-light p-3">
                                    <button type="button" class="btn btn-primary position-relative">
                                        Podstrony bez meta description
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          <?=$without_desc;?>
                                          <span class="visually-hidden">unread messages</span>
                                        </span>
                                      </button>
                                </div>

                              </div>
							  </aside>

                            </div>
						  </div>

						  <div class="col-12 col-md-6 col-lg-8 px-4 px-md-3 px-lg-1">
							  <?=$content?>
						  </div>
						</div>
                </div>
            </div>
        </div>
	<?php 
	require_once 'footer.php';

	$execute_query->free_result();
	$execute_query_p->free_result();
	$connect->close();
	?> 
</body>
</html>