<?php
defined('_JEXEC') or die;
$items = $params->get('items', []);
$arrow = $params->get('arrow');
$bullets = $params->get('bullets');
$NormalColor1 = $params->get('NormalColor1');
$NormalColor = $params->get('NormalColor');
$activeColor = $params->get('activeColor');
$hoverColor = $params->get('hoverColor');
$items = (array) $items;
$active = TRUE;
?>

<style type="text/css">
	.testimonial-container-<?php echo $module->id; ?> .slick-dots li button:before {
		color: <?php if($NormalColor1=="defualt") { echo '#1c60ff'; }else {echo $NormalColor1; } ?> !important;
	}
	.testimonial-container-<?php echo $module->id; ?> .slick-dots li.slick-active button:before, 
	.testimonial-container-<?php echo $module->id; ?> .slick-dots li button:hover:before {
		color: <?php echo $activeColor; ?> !important;
	}
	.testimonial-container-<?php echo $module->id; ?> .slick-prev:before,
	.testimonial-container-<?php echo $module->id; ?> .slick-next:before {
		color:<?php echo $NormalColor; ?>
	}
	.testimonial-container-<?php echo $module->id; ?> .slick-prev:hover:before, 
	.testimonial-container-<?php echo $module->id; ?> .slick-next:hover:before {
		color:<?php echo $hoverColor; ?>
	}
	<?php if($params->get('customstyle')=="1") {
			$params->get('customstyle');
			$nameColor = $params->get('nameColor');
			$designationColor = $params->get('designationColor');
			$reviewColor = $params->get('reviewColor');
			$nameSize = $params->get('nameSize');
			$designationSize = $params->get('designationSize');
			$reviewSize = $params->get('reviewSize');
		?>
		.testimonial-container-<?php echo $module->id; ?> .slide-content .author .author-info h5 {
			color: <?php  echo $nameColor?>;
			font-size: <?php  echo $nameSize?>px;
		}	
		.testimonial-container-<?php echo $module->id; ?> .author-info a {	
			color: <?php  echo $designationColor?>;
			font-size: <?php  echo $designationSize?>px;
		}
		.testimonial-container-<?php echo $module->id; ?> .author-info {	
			color: <?php  echo $designationColor?>;
			font-size: <?php  echo $designationSize?>px;
		}
		.testimonial-container-<?php echo $module->id; ?> .jd-testimonial-review {
			color: <?php echo $reviewColor?>;
			font-size: <?php echo $reviewSize?>px;
		}
	<?php } ?>
</style>
<div class="jd-testimonial">
	<div class="testimonial-container-<?php echo $module->id; ?> testimonial-wrapper">
	<?php foreach($items as $item)  { ?>
		<div class="main-slide-content">
		<div class="slide-content">
			<div class="jd-testimonial-author-info author">
				<?php if(!empty($item->author_thumbnail)) { ?>
					<div class="jd-testimonial-author-img author-img">
						<img src="<?php echo $item->author_thumbnail; ?>" alt="<?php echo $item->author_name; ?>" class="img-fluid">
					</div>
				<?php } ?>
			<?php if(!empty($item->author_name) or !empty($item->author_depart)) { ?>
				<div class="jd-testimonial-author-name author-info">
					<?php if(!empty($item->author_name)) { ?>
						<h5 class="jd-testimonial-author-name name"><?php echo $item->author_name; ?></h5>
					<?php } ?> 
					<?php if(!empty($item->author_companyName)) { ?>
						<div class="jd-testimonial-companyname author-text">
							<?php if(!empty($item->author_companyLink)) { ?>
							<a href="<?php echo $item->author_companyLink; ?>" target="_blank" rel="nofollow" ><?php } ?><?php echo $item->author_companyName; ?><?php if(!empty($item->author_companyLink)) { ?></a><?php } ?>
						</div>
					<?php } ?>
					<?php if(($item->rating !="none")) { ?>
						<div class="jd-testimonial-rating rating">
							<?php for($i=1; $i<=5; $i++) {
								if($i <=  $item->rating ){
									echo '<span class="fa fa-star text-primary"></span>';
								}else{
									echo '<span class="fa fa-star"></span>';
								}
							 } ?>
						</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<?php if(!empty($item->author_companyReview)) { ?>
				<div class="jd-testimonial-review text">
					<?php echo $item->author_companyReview; ?>
				</div>
			<?php } ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
 <script>
	  (function($){
	  $(function(){
		  $('.testimonial-container-<?php echo $module->id; ?>').slick({
				  arrows: <?php echo ($arrow==1) ? 'true' : 'false'; ?>,
				  dots: <?php  echo ($bullets==1) ? 'true' : 'false'; ?>,
				  infinite: true,
				  speed: 300,
				  slidesToShow: 1,
				  adaptiveHeight: true
			  });
	  });
	})(jQuery);
</script>