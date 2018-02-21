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
?>


<script type="text/javascript">
/* <![CDATA[ */
var edd_scripts = {};
/* ]]> */
</script>
<script type="text/javascript" src="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets'; ?>/js/hc.js"></script>
<script type="text/javascript" src="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets'; ?>/js/sc_our_team.js"></script>
<link rel="stylesheet" id="smartcat_team_default_style-css" href="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets' ?>/css/sc_our_team.css" type="text/css" media="all">
<link rel="stylesheet" id="gcc-style-css" href="<?php echo JURI::root().'modules/mod_jd_team_showcase/assets' ?>/css/style.css" type="text/css" media="all">

<?php
if($link_color){ ?>
<style>	

 #sc_our_team.grid .sc_team_member .sc_team_member_name, #sc_our_team.grid .sc_team_member .sc_team_member_jobtitle, #sc_our_team.grid_circles .sc_team_member .sc_team_member_jobtitle, #sc_our_team.grid_circles .sc_team_member .sc_team_member_name, #sc_our_team_lightbox .progress, .sc_our_team_panel .sc-right-panel .sc-name, #sc_our_team .sc_team_member .icons span, .sc_our_team_panel .sc-right-panel .sc-skills .progress, #sc_our_team_lightbox .sc_our_team_lightbox .social span, .sc_team_single_member .sc_team_single_skills .progress, .sc-tags .sc-single-tag{background:<?php echo $link_color; ?>!important;}
 
 .sc_team_icon-facebook, .sc_team_icon-twitter, .sc_team_icon-twitter, .sc_team_icon-google-plus, .sc_team_icon-pinterest-p, .sc_team_icon-envelope-o, .sc_team_icon-phone{background:<?php echo $link_color; ?>!important;}
 
</style>
<?php } ?>
<?php
if($text_color){ ?>
<style>	
 .sc_team_member_jobtitle, .sc_personal_quote_content, .shot_content{ color: <?php echo $text_color; ?>!important;}
  #sc_our_team a, .sc_our_team_lightbox .name, .sc_personal_quote span.sc_team_icon-quote-left, .sc-team-member-posts a{ color: <?php echo $text_color; ?>!important;}
</style>
<?php } ?>
<?php if($team_member_in_row == 3){ ?>
<style>	
 #sc_our_team.sc-col2 .sc_team_member{
        width:30%!important;
        
    }
</style>	
<?php }else if($team_member_in_row == 2){ ?>
<style>	
 #sc_our_team.sc-col2 .sc_team_member{
        width:48%!important;
        
    }
</style>	
<?php }else if($team_member_in_row == 1){ ?>
<style>	
 #sc_our_team.sc-col2 .sc_team_member{
        width:100%!important;
        
    }
</style>	
<?php }else if($team_member_in_row == 4){ ?>
<style>	
 #sc_our_team.sc-col2 .sc_team_member{
        width:23%!important;
        
    }
</style>	
<?php } ?>
<?php  if($socail_color){ ?>
<style>	
 #sc_our_team .sc_team_member .icons span{background:<?php echo $socail_color; ?>!important;}
 #sc_our_team .sc_team_member .icons span:hover{background:<?php echo $socail_color_hove; ?>!important;}
</style>
<?php } ?>
<div class="grid_circles2 sc-col2" id="sc_our_team">
				<?php if(!empty($teamMembers)){ ?>
		<?php foreach($teamMembers as $k=>$v){ ?>
                <div class="sc_team_member" itemscope="" style="height: 365px;">
                <div class="sc_team_member_inner">
                   <img height="300" width="300" alt="team1" class="attachment-medium size-medium wp-post-image" src="<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>">
				    <div class="sc_team_member_name" itemprop="name">
						<a  class="sc_team_single_panel"  rel="bookmark" href="javascript:void(0);"><?php echo (isset($v->member_name)) ? $v->member_name : ''; ?></a>
					</div> 
					<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div> <div class="sc_personal_quote">
							<span class="sc_team_icon-quote-left"></span>
							<span class="sc_personal_quote_content"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
						</div> 
						<div class="sc_team_content"><?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?></div>  
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
                    <div class="sc_team_member_overlay" style="display: none; opacity: 1;"></div>
					</div>
				</div>
             <?php } ?>            
		<?php } ?> 
				<div class="clear"></div>
</div>


<div class="honeycomb-lightbox">
<div class="scrollbar-macosx" id="sc_our_team_panel" style="display: none;"></div>
    <div class="sc_our_team_panel permanent">        
        <div class="sc-left-panel">
            <div class="sc-social "></div>
        </div>
        <div class="sc-right-panel">            
            <span class="sc_team_icon-close"></span>
			 <img class="sc-image square" src="">        
            <h2 class="sc-name">&nbsp;</h2>  
            <h3 class="sc-title">&nbsp;</h3>           
            <div class="sc_personal_quote"> 
				<span class="sc_team_icon-quote-left"></span>
                <span class="sc_personal_quote_content">&nbsp;</span>
			</div>
            <div class="sc-content">&nbsp;</div>
        </div>
    </div>
</div>