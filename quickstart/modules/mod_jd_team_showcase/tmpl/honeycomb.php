<?php
/**
 * @package     Joomla.Site
 * @subpackage  JD Team ShowCase
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

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

		<div class="hc sc-col2 honeycombs honeycombs-wrapper" id="sc_our_team">
                
				<div class="honeycombs-inner-wrapper" style="width: 735px; height: 486.314px;">
					<div class="honeycombs-inner-wrapper" style="width: 735px; height: 486.314px;">
						
					<?php if(!empty($teamMembers)){ ?>
					<?php foreach($teamMembers as $k=>$v){ ?>
					<div class="sc_team_member comb sc_team_single_panel"  itemscope="" style="width: 220px; height: 190.526px; left: 0px; top: 97.7628px;">
					<img height="300" width="300" alt="team1" class="attachment-medium size-medium wp-post-image" src="<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>" style="display: none;">
					<span style="display: none;">
                    <b>
						<div class="sc_team_member_name" itemprop="<?php echo (isset($v->member_name)) ? $v->member_name : ''; ?>">
                                <a rel="bookmark" href="javascript:void(0);"><?php echo (isset($v->member_name)) ? $v->member_name : ''; ?> </a>
                        </div>
						<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?> </div>
						<div class="sc_personal_quote">
							<span class="sc_team_icon-quote-left" style="display: none;"></span>
							<span class="sc_personal_quote_content" style="display: none;"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
						</div>
                        <div class="sc_team_content">
                           <?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?>
                        </div> 
                        <div class="icons ">    
                            <a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-facebook"></span></a>
							<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-twitter"></span></a>
							
							<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-google-plus"></span></a>
							
							<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
							
							<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
							
							<a href="tel:<?php echo (isset($v->telephone_number)) ? $v->telephone_number : ''; ?>"><span style="display: none;" class="sc_team_icon-phone"></span></a>
                        </div>
                    </b>
                </span>

            <div class="hex_l" style="width: 220px; height: 190.526px;">
				<div class="hex_r" style="width: 220px; height: 190.526px;">
					<div class="hex_inner" style="background-image: url('<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>'); width: 220px; height: 190.526px;">
						<div class="inner_span" style="display: block;"><div class="inner-text">						
							<span style="display: none;">
							<b>
									<div class="sc_team_member_name" itemprop="name">
										<a rel="bookmark" href="javascript:void(0);">                            
											<?php echo (isset($v->member_name)) ? $v->member_name : ''; ?>
										</a>
									</div>
									<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
									<div class="sc_personal_quote">
										<span class="sc_team_icon-quote-left" style="display: none;"></span>
										<span class="sc_personal_quote_content" style="display: none;">
											<?php echo (isset($v->department)) ? $v->department : ''; ?>
										</span>
									</div>                        
									<div class="sc_team_content"><?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?></div>  
								  <div class="icons ">    
									<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-facebook"></span></a>
									<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-twitter"></span></a>
									
									<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span style="display: none;"class="sc_team_icon-google-plus"></span></a>
									
									<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
									
									<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
									
									<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span style="display: none;" class="sc_team_icon-phone"></span></a>
								</div>
							</b>
							</span>
						</div>
					</div>
				
					<div class="inner_span" style="display: block;">
						<div class="inner-text">
							<img height="300" width="300" alt="team1" class="attachment-medium size-medium wp-post-image" src="<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>" style="display: none;">
							<b>
								<div class="sc_team_member_name" itemprop="name">
										<a rel="bookmark" href="javascript:void(0);">                            
										   <?php echo (isset($v->member_name)) ? $v->member_name : ''; ?>
										</a>
								</div>
								<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
								<div class="sc_personal_quote">
									<span class="sc_team_icon-quote-left" style="display: none;"></span>
									<span class="sc_personal_quote_content" style="display: none;">
										<?php echo (isset($v->department)) ? $v->department : ''; ?></span>
								</div> 
								<div class="sc_team_content"><?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?></div>                            
								<div class="icons ">    
									<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-facebook"></span></a>
									<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-twitter"></span></a>
									
									<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-google-plus"></span></a>
									
									<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
									
									<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
									
									<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span  style="display: none;" class="sc_team_icon-phone"></span></a>
								</div>
							</b>
						</div>
					</div>
				</div>
				

				<div class="hex_inner" style="background-image: url('<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>'); width: 220px; height: 190.526px;">
					<div class="inner_span" style="display: block;">
						<div class="inner-text">
							<b>
							<div class="sc_team_member_name" itemprop="name">
                                <a rel="bookmark" href="javascript:void(0);"> <?php echo (isset($v->member_name)) ? $v->member_name : ''; ?></a>
                            </div>
							<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
                            <div class="sc_personal_quote">
								<span class="sc_team_icon-quote-left" style="display: none;"></span>
								<span class="sc_personal_quote_content" style="display: none;"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
							</div>                       
							<div class="sc_team_content">
								<?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?>
							</div>                            
							<div class="icons ">    
									<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-facebook"></span></a>
									<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-twitter"></span></a>
									
									<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-google-plus"></span></a>
									
									<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
									
									<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
									
									<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span style="display: none;" class="sc_team_icon-phone"></span></a>
								</div>
							</b>
						</div>
					</div>
				</div>
				</div>				
				<div class="hex_r" style="width: 220px; height: 190.526px;">
					<div class="hex_inner" style="background-image: url('<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>'); width: 220px; height: 190.526px;">
						<div class="inner_span" style="display: block;">
							<div class="inner-text">
								<b>
									<div class="sc_team_member_name" itemprop="name">
										<a rel="bookmark" href="javascript:void(0);"> <?php echo (isset($v->member_name)) ? $v->member_name : ''; ?></a>
									</div>
									<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
									<div class="sc_personal_quote">
										<span class="sc_team_icon-quote-left" style="display: none;"></span>
										<span class="sc_personal_quote_content" style="display: none;"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
									</div>                       
									<div class="sc_team_content">
										<?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?>
									</div>                            
									<div class="icons ">    
											<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-facebook"></span></a>
											<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-twitter"></span></a>
											
											<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-google-plus"></span></a>
											
											<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
											
											<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
											
											<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span style="display: none;" class="sc_team_icon-phone"></span></a>
										</div>
									</b>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="hex_l" style="width: 220px; height: 190.526px;">
				<div class="hex_r" style="width: 220px; height: 190.526px;">
					<div class="hex_inner" style="background-image: url('<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>'); width: 220px; height: 190.526px;">
						<div class="inner_span" style="display: block;"><div class="inner-text">						
							<span style="display: none;">
							<b>
									<div class="sc_team_member_name" itemprop="name">
										<a rel="bookmark" href="javascript:void(0);">                            
											<?php echo (isset($v->member_name)) ? $v->member_name : ''; ?>
										</a>
									</div>
									<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
									<div class="sc_personal_quote">
										<span class="sc_team_icon-quote-left" style="display: none;"></span>
										<span class="sc_personal_quote_content" style="display: none;">
											<?php echo (isset($v->department)) ? $v->department : ''; ?>
										</span>
									</div>                        
									<div class="sc_team_content"><?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?></div>  
								  <div class="icons ">    
									<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-facebook"></span></a>
									<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-twitter"></span></a>
									
									<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span style="display: none;"class="sc_team_icon-google-plus"></span></a>
									
									<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
									
									<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
									
									<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span style="display: none;" class="sc_team_icon-phone"></span></a>
								</div>
							</b>
							</span>
						</div>
					</div>
				
					<div class="inner_span" style="display: block;">
						<div class="inner-text">
							<img height="300" width="300" alt="team1" class="attachment-medium size-medium wp-post-image" src="<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>" style="display: none;">
							<b>
								<div class="sc_team_member_name" itemprop="name">
										<a rel="bookmark" href="javascript:void(0);">                            
										   <?php echo (isset($v->member_name)) ? $v->member_name : ''; ?>
										</a>
								</div>
								<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
								<div class="sc_personal_quote">
									<span class="sc_team_icon-quote-left" style="display: none;"></span>
									<span class="sc_personal_quote_content" style="display: none;">
										<?php echo (isset($v->department)) ? $v->department : ''; ?></span>
								</div> 
								<div class="sc_team_content"><?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?></div>                            
								<div class="icons ">    
									<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-facebook"></span></a>
									<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-twitter"></span></a>
									
									<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-google-plus"></span></a>
									
									<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
									
									<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
									
									<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span  style="display: none;" class="sc_team_icon-phone"></span></a>
								</div>
							</b>
						</div>
					</div>
				</div>
				<div class="hex_inner" style="background-image: url('<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>'); width: 220px; height: 190.526px;">
					<div class="inner_span" style="display: block;">
						<div class="inner-text">
							<b>
							<div class="sc_team_member_name" itemprop="name">
                                <a rel="bookmark" href="javascript:void(0);"> <?php echo (isset($v->member_name)) ? $v->member_name : ''; ?></a>
                            </div>
							<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
                            <div class="sc_personal_quote">
								<span class="sc_team_icon-quote-left" style="display: none;"></span>
								<span class="sc_personal_quote_content" style="display: none;"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
							</div>                       
							<div class="sc_team_content">
								<?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?>
							</div>                            
							<div class="icons ">    
									<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-facebook"></span></a>
									<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-twitter"></span></a>
									
									<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-google-plus"></span></a>
									
									<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span style="display: none;" class="sc_team_icon-pinterest-p"></span></a>
									
									<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span style="display: none;" class="sc_team_icon-envelope-o"></span></a>
									
									<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span style="display: none;" class="sc_team_icon-phone"></span></a>
								</div>
							</b>
						</div>
					</div>
				</div>
				</div>				
				<div class="hex_r" style="width: 220px; height: 190.526px;">
					<div class="hex_inner" style="background-image: url('<?php echo JURI::root(); ?><?php echo (isset($v->member_image)) ? $v->member_image : '' ?>'); width: 220px; height: 190.526px;">
						<div class="inner_span" style="display: block;">
							<div class="inner-text">
								<b>
									<div class="sc_team_member_name" itemprop="name">
										<a rel="bookmark" href="javascript:void(0);"> <?php echo (isset($v->member_name)) ? $v->member_name : ''; ?></a>
									</div>
									<div class="sc_team_member_jobtitle" itemprop="jobtitle"><?php echo (isset($v->job_title)) ? $v->job_title : ''; ?></div>
									<div class="sc_personal_quote">
										<span class="sc_team_icon-quote-left" style="display: none;"></span>
										<span class="sc_personal_quote_content" style="display: none;"><?php echo (isset($v->department)) ? $v->department : ''; ?></span>
									</div>                       
									<div class="sc_team_content">
										<?php echo (isset($v->member_bio)) ? $v->member_bio : ''; ?>
									</div>                            
									<div class="icons ">    
											<a href="<?php echo (isset($v->facebook_url)) ? $v->facebook_url : ''; ?>" target="_BLANK"><span  s class="sc_team_icon-facebook"></span></a>
											<a href="<?php echo (isset($v->twitter_url)) ? $v->twitter_url : ''; ?>" target="_BLANK"><span  class="sc_team_icon-twitter"></span></a>
											
											<a href="<?php echo (isset($v->instagram_url)) ? $v->instagram_url : ''; ?>" target="_BLANK"><span  style="display: none;" class="sc_team_icon-google-plus"></span></a>
											
											<a href="<?php echo (isset($v->pintrest_url)) ? $v->pintrest_url : ''; ?>" target="_BLANK"><span   class="sc_team_icon-pinterest-p"></span></a>
											
											<a href="mailto:<?php echo (isset($v->email_address)) ? $v->email_address : ''; ?>"><span  class="sc_team_icon-envelope-o"></span></a>
											
											<a href="tel:<?php echo (isset($v->short_bio)) ? $v->short_bio : ''; ?>"><span s class="sc_team_icon-phone"></span></a>
									</div>
								</b>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
            	<?php } ?>            
		<?php } ?> 		
		<div class="sc_team_member comb sc_team_single_panel" itemscope="" style="width: 220px; height: 190.526px; left: 170px; top: 0px;">
                        
		</div>
			
<div class="honeycomb-lightbox">
<div class="scrollbar-macosx" id="sc_our_team_panel" style="display: none;"></div>
    <div class="sc_our_team_panel permanent">        
        <div class="sc-left-panel">
            <div class="sc-social ">
			 
			</div>

        </div>
        <div class="sc-right-panel">            
			<h2 class="sc-name">&nbsp;</h2>
            <span class="sc_team_icon-close"></span>
			<img class="sc-image square" src="">        
            <h3 class="sc-title">&nbsp;</h3>           
            <div class="sc_personal_quote"> 
				<span class="sc_team_icon-quote-left"></span>
                <span class="sc_personal_quote_content">&nbsp;</span>
			</div>
            <div class="sc-content">&nbsp;</div>
        </div>
    </div>
</div>