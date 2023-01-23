

<footer class="footer_area section_padding_130_0">
  <div class="container">
	<div class="row">
	  <!-- Single Widget-->
	  <div class="col-12 col-sm-6 col-lg-4">
		<div class="single-footer-widget section_padding_0_130">
		  <!-- Footer Logo-->
		  <div class="footer-logo mb-3"></div>
		  <p>Projekt CMS w ramach doskonalenia</p>
		  <!-- Copywrite Text-->
		  <div class="copywrite-text mb-5">
			<p class="mb-0"> umiejętności z HTML, CSS, PHP i MySQL </p>
		  </div>
		  <!-- Footer Social Area-->
		  <div class="footer_social_area">
		  
		  <?php
		  
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
					
					echo '<a href="'.$row_sm['fb'].'" rel="nofollow" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facebook"><i class="fa fa-facebook"></i></a>
						  <a href="'.$row_sm['pin'].'" rel="nofollow" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pinterest"><i class="fa fa-pinterest"></i></a>
						  <a href="'.$row_sm['skype'].'" rel="nofollow" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype"><i class="fa fa-skype"></i></a>
						  <a href="'.$row_sm['tw'].'" rel="nofollow" data-toggle="tooltip" data-placement="top" title="" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></div>';
				}		  
			}
			catch(Exception $errorf)
			{
				$_SESSION['error'] = $errorf;
				echo $_SESSION['error'];
			}
			?>
			
			
		  
		</div>
	  </div>
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
					
					echo '<div class="col-12 col-sm-6 col-lg"><div class="single-footer-widget section_padding_0_130"><h5 class="widget-title">'.$row_widget['wid'.$d].'</h5><div class="footer_menu"><ul>';
								
					for($j=1;$j<=$how_many_links;$j++)
						{
							$row = mysqli_fetch_assoc($execute_query_footer_links);
							if($row['link'] !== "" OR $row['anchor'] !== "") echo '<li><a href="'.$row['link'].'" rel="'.$row['nofollow'].'">'.$row['anchor'].'</a></li>';
						};
					echo '</ul></div></div></div>';
				}		  
			}
			catch(Exception $errorf)
			{
				$_SESSION['error'] = $errorf;
				echo $_SESSION['error'];
			}
		}
	$execute_query_footer_links->free_result();	
	$execute_query_widget->free_result();	
	$execute_query_sm->free_result();	
	$connect->close();
	?>
	</div>
  </div>
</footer>

<script src="js/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/3.6.0-jquery.min.js"></script>
<script src="js/sticky-nav.js"></script>

</body>
</html>