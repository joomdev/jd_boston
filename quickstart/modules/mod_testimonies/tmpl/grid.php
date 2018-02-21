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
    <div id="myCarousel" class="carousel slide"  data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($lists as $key=>$item) : ?>
                    <div class="ot_left"> 
                            <div class="ot_tcontent1">
                                <div class="ot_title">
                                <?php echo $item->comment; ?>
                                </div>                     
                            </div>
                            <div class="arrow-down1"></div>
                            <div class="ot_info1">
                                    <?php if ((isset($item->avatar) && $item->avatar != '') && $params->get('show_avatar')!=0) { ?>
                                    <div class="ot_image1">
                                        <img src="<?php echo JUri::root() . 'images/testimonies/' . $item->avatar; ?>">
                                    </div>
                                    <?php } ?>
                                    <div class="ot_aditional1">
                                        <?php if ((isset($item->name) && $item->name != '') && $params->get('show_name')!=0) { ?>
                                        <div class="ot_name">
                                        <?php echo $item->name; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($item->position) && $item->position != '') && $params->get('show_position')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $item->position; ?>
                                        </div>
                                        <?php } ?>										
                                        <?php if ((isset($item->company_name) && $item->company_name != '') && $params->get('show_company')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $item->company_name; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($item->phone) && $item->phone != '') && $params->get('show_phone')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $item->phone; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($item->email) && $item->email != '') && $params->get('show_email')!=0) { ?>
                                        <div class="ot_email">
                                            <a href="mailto:<?php echo $item->email; ?>" target="_top"><?php echo $item->email; ?></a>                        
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($item->website_url) && $item->website_url != '') && $params->get('show_website_url')!=0) { ?>
                                        <div class="ot_website">
                                            <a href="<?php echo 'http://' . $item->website_url; ?>"><?php echo $item->website_url; ?></a>
                                        </div>
                                        <?php } ?>
										<?php if ((isset($item->rating) && $item->rating != '') && $params->get('show_rating')!=0) { ?>
                                        <div class="ot_ratting">
											<?php for ($j = 0; $j < $item->rating; $j++) { ?>
											  <i class="glyphicon glyphicon-star"></i> 
											<?php } ?>
										<?php } ?>
                                        </div>       
                                    </div>
                            </div>
                    </div>
                <?php endforeach; ?>
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
