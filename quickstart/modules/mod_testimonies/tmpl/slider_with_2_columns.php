<?php 
/**
* @package 		OT Testimonies for Joomla! 3.3 
* @version 		$Id: mod_testimonies.php - Aug 2016  OmegaTheme 
* @author 		OmegaTheme Extensions (services@omegatheme.com) - http://omegatheme.com 
* @copyright 	Copyright(C) 2016 - OmegaTheme Extensions 
* @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/
// no direct access
defined('_JEXEC') or die;
?>
<div id="ot_testimonial_<?php echo $module->id; ?>" class="ot_testimonial<?php echo $moduleclass_sfx; ?>">
    <div id="testimonial_mod_<?php echo $module->id; ?>" class="carousel slide" data-interval="<?php echo $ot_speed; ?>" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php $count = count($lists);$count = $count/2; ?>
            <?php
            
            for($i=0;$i<$count;$i++) {
               
                ?>
                <?php if ($count > 1) { ?>
                    <?php if ($i == 0) { ?>
                        <li data-target="#testimonial_mod_<?php echo $module->id; ?>" data-slide-to="<?php echo $i; ?>" class="active"></li>
                    <?php } else { ?>
                        <li data-target="#testimonial_mod_<?php echo $module->id; ?>" data-slide-to="<?php echo $i; ?>"></li>
                    <?php } ?>
                <?php
                } else {
                    
                }
                ?>
        <?php } ?>
        </ol> 
            <div class="carousel-inner">
                <?php
                $i = -1;               
                foreach ($lists as $key=>$list) {
                $i++;                                      
                if($key %2 ==0) : 
                    if($i==0) :   ?>               
                    <div class="active item">
                   <?php else : ?>
                   <div class=" item">
                   <?php endif; ?>
                        <div class="ot_left"> 
                            <div class="ot_tcontent1">
                                <div class="ot_title">
                                <?php echo $lists[$key]->comment; ?>
                                </div>                     
                            </div>
                            <div class="arrow-down1"></div>
                            <div class="ot_info1">
                                    <?php if ((isset($lists[$key] ->avatar) && $lists[$key]->avatar != '') && $params->get('show_avatar')!=0) { ?>
                                    <div class="ot_image1">
                                        <img  src="<?php echo JUri::root() . 'images/testimonies/' . $lists[$key]->avatar; ?>">
                                    </div>
                                    <?php } ?>
                                    <div class="ot_aditional1">
                                        <?php if ((isset($lists[$key]->name) && $lists[$key]->name != '') && $params->get('show_name')!=0) { ?>
                                        <div class="ot_name">
                                        <?php echo $lists[$key]->name; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($lists[$key]->company_name) && $lists[$key]->company_name != '') && $params->get('show_company')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $lists[$key]->company_name; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($lists[$key]->phone) && $lists[$key]->phone != '') && $params->get('show_phone')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $lists[$key]->phone; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($lists[$key]->email) &&$lists[$key]->email != '') && $params->get('show_email')!=0) { ?>
                                        <div class="ot_email">
                                            <a href="mailto:<?php echo $lists[$key]->email; ?>" target="_top"><?php echo $lists[$key]->email; ?></a>                        
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($lists[$key]->website_url) && $lists[$key]->website_url != '') && $params->get('show_website_url')!=0) { ?>
                                        <div class="ot_website">
                                            <a href="<?php echo 'http://' . $lists[$key]->website_url; ?>"><?php echo $lists[$key]->website_url; ?></a>
                                        </div>
                                        <?php } ?>
										<?php if ((isset($lists[$key]->rating) && $lists[$key]->rating != '') && $params->get('show_rating')!=0) { ?>
                                        <div class="ot_ratting">
                                        <?php for ($j = 0; $j < $lists[$key]->rating; $j++) { ?>
                                          <i class="glyphicon glyphicon-star"></i> 
                                        <?php } ?>
                                        </div>    
										<?php } ?>										
                                </div>
                            </div>
                        </div>
                       <?php if(isset($lists[$key+1])) : ?>
                        <div class="ot_left"> 
                            <div class="ot_tcontent1">
                                <div class="ot_title">
                                <?php echo $lists[$key+1]->comment; ?>
                                </div>                     
                            </div>
                            <div class="arrow-down1"></div>
                            <div class="ot_info1">
                                    <?php if ((isset($lists[$key+1] ->avatar) && $lists[$key+1]->avatar != '') && $params->get('show_avatar')!=0) { ?>
                                    <div class="ot_image1">
                                        <img  src="<?php echo JUri::root() . 'images/testimonies/' . $lists[$key+1]->avatar; ?>">
                                    </div>
                                    <?php } ?>
                                    
                                        <div class="ot_aditional1">
                                            <?php if ((isset($lists[$key+1]->name) && $lists[$key+1]->name != '') && $params->get('show_name')!=0) { ?>
                                            <div class="ot_name">
                                            <?php echo $lists[$key+1]->name; ?>
                                            </div>
                                            <?php } ?>
											<?php if ((isset($lists[$key+1]->position) && $lists[$key+1]->position != '') && $params->get('show_position')!=0) { ?>
											<div class="ot_work">
											<?php echo $lists[$key+1]->position; ?>
											</div>
											<?php } ?>											
                                            <?php if ((isset($lists[$key+1]->company_name) && $lists[$key+1]->company_name != '') && $params->get('show_company')!=0) { ?>
                                            <div class="ot_work">
                                            <?php echo $lists[$key+1]->company_name; ?>
                                            </div>
                                            <?php } ?>
                                            <?php if ((isset($lists[$key+1]->phone) && $lists[$key+1]->phone != '') && $params->get('show_phone')!=0) { ?>
                                            <div class="ot_work">
                                            <?php echo $lists[$key+1]->phone; ?>
                                            </div>
                                            <?php } ?>
                                            <?php if ((isset($lists[$key+1]->email) &&$lists[$key+1]->email != '') && $params->get('show_email')!=0) { ?>
                                            <div class="ot_email">
                                                <a href="mailto:<?php echo $lists[$key+1]->email; ?>" target="_top"><?php echo $lists[$key+1]->email; ?></a>                        
                                            </div>
                                            <?php } ?>
                                            <?php if ((isset($lists[$key+1]->website_url) && $lists[$key+1]->website_url != '') && $params->get('show_website_url')!=0) { ?>
                                            <div class="ot_website">
                                                <a href="<?php echo 'http://' . $lists[$key+1]->website_url; ?>"><?php echo $lists[$key+1]->website_url; ?></a>
                                            </div>
                                            <?php } ?>
											<?php if ((isset($lists[$key+1]->rating) && $lists[$key+1]->rating != '') && $params->get('show_rating')!=0) { ?>
                                            <div class="ot_ratting">
                                            <?php for ($j = 0; $j < $lists[$key+1]->rating; $j++) { ?>
                                              <i class="glyphicon glyphicon-star"></i> 
                                            <?php } ?>
                                            </div> 
											<?php } ?>											
                                        </div>                                     
                            </div>
                        </div>
                   <?php endif; ?>  
                </div>
                <?php endif; ?>
            <?php } ?>    
            
        </div>
		<?php if($params->get('add_testimonies')!=0){?>   <a href="<?php echo JRoute::_('index.php?option=com_testimonies') ?>">Submit Testimonies</a><?php }?>
    </div>
	<?php /* REMOVING Copyright warning 
	The Joomla module: OT Testimonial is free for all websites. We're welcome any developer want to contributes the module. But you must keep our credits that is the very tiny image under the module. If you want to remove it, you may visit http://www.omegatheme.com/member/signup/additional to purchase the Removing copyrights, then you can free your self to remove it. Thank you very much. Omegatheme.com
	*/?>
	<a href="//www.omegatheme.com" class="omega-powered" >
	<img src="<?php echo '//www.omegatheme.com/credits.php?utm_source='.$_SERVER['SERVER_NAME']; ?>" title="Joomla Module OT Testimonial powered by OmegaTheme.com" alt="Joomla Module OT Testimonial powered by OmegaTheme.com">
	</a>
	<?php /*********/ ?>
</div>
