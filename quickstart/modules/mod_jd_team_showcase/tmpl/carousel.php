<?php
/**
 * @package     Joomla.Site
 * @subpackage  JD Team ShowCase
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$team_member_in_row = $params->get('team_member_in_row',2);
$text_color = $params->get('text_color');
$link_color = $params->get('link_color');
$socail_color = $params->get('socail_color');
$socail_color_hove = $params->get('socail_color_hove');

//echo "<pre>";print_r($teamMembers);exit;


?>
<style>	
 #sc_our_team .sc_team_member_inner {text-align: center;}
 #sc_our_team a, .sc_our_team_lightbox .name, .sc_personal_quote span.sc_team_icon-quote-left, .sc-team-member-posts a
</style>

<script type="text/javascript">
/* <![CDATA[ */
var edd_scripts = {};
/* ]]> */
</script>
<script type="text/javascript" src="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets'; ?>/js/hc.js"></script>
<script type="text/javascript" src="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets'; ?>/js/sc_our_team.js"></script>
<script type="text/javascript" src="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets'; ?>/js/owl.carousel.js"></script>
<link rel="stylesheet"  href="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets' ?>/css/sc_our_team.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets' ?>/css/style.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets' ?>/css/owl.carousel.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets' ?>/css/owl.theme.css" type="text/css" media="all">
<style>
	.sc_team_member_name{margin-top:6px!important;margin-bottom:6px!important;}
	.sc_team_member_jobtitle,.sc_personal_quote_content,.shot_content{margin-bottom:6px!important;color:#737373!important;line-height: 25px;font-size: 14px;}
</style>
<script type="text/javascript">
jQuery(document).ready( function($){
	jQuery('#sc_our_team').owlCarousel({
		 items : <?php echo $team_member_in_row; ?>,
		 autoPlay : 3000,
	});
});
</script>
<?php
if($link_color){ ?>
<style>	
 #sc_our_team a, .sc_our_team_lightbox .name, .sc_personal_quote span.sc_team_icon-quote-left, .sc-team-member-posts a{ color: <?php echo $link_color; ?>!important;}
 #sc_our_team.grid .sc_team_member .sc_team_member_name, #sc_our_team.grid .sc_team_member .sc_team_member_jobtitle, #sc_our_team.grid_circles .sc_team_member .sc_team_member_jobtitle, #sc_our_team.grid_circles .sc_team_member .sc_team_member_name, #sc_our_team_lightbox .progress, .sc_our_team_panel .sc-right-panel .sc-name, #sc_our_team .sc_team_member .icons span, .sc_our_team_panel .sc-right-panel .sc-skills .progress, #sc_our_team_lightbox .sc_our_team_lightbox .social span, .sc_team_single_member .sc_team_single_skills .progress, .sc-tags .sc-single-tag{background:<?php echo $link_color; ?>!important;}
 
 .sc_team_icon-facebook, .sc_team_icon-twitter, .sc_team_icon-twitter, .sc_team_icon-google-plus, .sc_team_icon-pinterest-p, .sc_team_icon-envelope-o, .sc_team_icon-phone{background:<?php echo $link_color; ?>!important;}
</style>
<?php } ?>
<?php
if($text_color){ ?>
<style>	
 .sc_team_member_jobtitle, .sc_personal_quote_content, .shot_content{ color: <?php echo $text_color; ?>!important;}
</style>
<?php } ?>
<?php
if($socail_color){ ?>
<style>	
 #sc_our_team .sc_team_member .icons span{background:<?php echo $socail_color; ?>!important;}
 #sc_our_team .sc_team_member .icons span:hover{background:<?php echo $socail_color_hove; ?>!important;}
</style>
<?php } ?>
<div id="sc_our_team" class="owl-carousel">
	<?php if(!empty($teamMembers)){ ?>
		<?php foreach($teamMembers as $k=>$v){
				
			?>	
				<div class="item">
					<div itemscope="" class="sc_team_member">
						<div class="sc_team_member_inner">
							<a href="javascript:void(0)" rel="bookmark" class="sc_team_single_panel"> 
								<img height="300" width="300" src="<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>" class="attachment-medium size-medium wp-post-image" alt="<?php echo (isset($v->member_image)) ? $v->member_image : '' ?>"> 
							</a>
							<div itemprop="name" class="sc_team_member_name">
								<a href="javascript:void(0)" rel="bookmark" class="sc_team_single_panel">
									<?php echo (isset($v->member_name)) ? $v->member_name : ''; ?>
								</a>
							</div>
							<div itemprop="jobtitle" class="sc_team_member_jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
							<div class="shot_content" ><?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?></div>
							<div class="sc_personal_quote">
								<span class="sc_team_icon-quote-left"></span>
								<span class="sc_personal_quote_content"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
							</div>                    
											
						<div class="sc_team_content" style="display:none;">
							<?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?>
						</div>
						<div class="icons ">
							<?php if($v->is_facebook){ ?>
								<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span class="sc_team_icon-facebook"></span></a>
							<?php } ?>
							<?php if($v->is_twitter){ ?>
							<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span class="sc_team_icon-twitter"></span></a>
							<?php } ?>
							<?php if($v->is_linkedin){ ?>
							<a href="<?php echo (isset($v->linkedin_url)) ? $v->linkedin_url : ''; ?>" target="_BLANK"><span class="sc_team_icon-linkedin"></span></a>
							<?php } ?>
							<?php if($v->is_googlepluse){ ?>
							<a href="<?php echo (isset($v->googlepluse_url)) ? $v->googlepluse_url : ''; ?>" target="_BLANK"><span class="sc_team_icon-google-plus"></span></a>
							<?php } ?>
							<?php if($v->is_instagram){ ?>
							<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span class="sc_team_icon-instagram"></span></a>
							<?php } ?>
							<?php if($v->is_pintrest){ ?>
							<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span class="sc_team_icon-pinterest-p"></span></a>
							<?php } ?>
							<?php if($v->is_email){ ?>
							<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span class="sc_team_icon-envelope-o"></span></a>
							<?php } ?>
							<?php if($v->is_telephone){ ?>
							<a href="tel:<?php echo (isset($v->telephone_number)) ? $v->telephone_number : ''; ?>"><span class="sc_team_icon-phone"></span></a>
							<?php } ?>
						</div>                    
					</div>
				</div>
			</div>
			
		<?php } ?>            
	<?php } ?> 
</div>	
			
	
<!-- light box -->
<div style="display: none;" id="sc_our_team_panel" class="scrollbar-macosx"></div>
<div class="sc_our_team_panel permanent">
<div class="sc-left-panel">
	<div class="sc-social ">&nbsp;
	</div>
</div>
<div class="sc-right-panel">
	<span class="sc_team_icon-close"></span>
	
	<h2 class="sc-name">&bnsp;</h2>            
	<img src="" class="sc-image square">   
	<h3 class="sc-title">&nbsp;</h3>
	<div class="sc_personal_quote">
		<span class="sc_team_icon-quote-left"></span>
		<span class="sc_personal_quote_content">&nbsp;</span>
	</div>
	
	<div class="sc-content">&nbsp;</div>          
</div>
</div>