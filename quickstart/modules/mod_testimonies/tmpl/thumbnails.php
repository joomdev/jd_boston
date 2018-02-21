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
<script src="<?php echo JUri::root() ?>modules/mod_testimonies/assets/js/jquery.carouFredSel-6.0.4-packed.js" type="text/javascript"></script>
<div id="ot_testimonial_<?php echo $module->id; ?>" class="ot_testimonial<?php echo $moduleclass_sfx; ?>">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
			<div id="carousel-wrapper">
				<div id="carousel">
					<?php $i=-1; foreach ($lists as $list) : $i++; $star = $list->rating;?>						
						<span id="<?php echo $i; ?>">
							<div class="ot_tcontent">
                                <div class="ot_title">
                                <?php echo $list->comment; ?>
                                </div>												
                            </div>
							<div class="ot_aditional">
                                        <?php if ((isset($list->name) && $list->name != '') && $params->get('show_name')!=0) { ?>
                                        <div class="ot_name">
                                        <?php echo $list->name; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($list->position) && $list->position != '') && $params->get('show_position')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $list->position; ?>
                                        </div>
                                        <?php } ?>										
                                        <?php if ((isset($list->company_name) && $list->company_name != '') && $params->get('show_company')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $list->company_name; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($list->phone) && $list->phone != '') && $params->get('show_phone')!=0) { ?>
                                        <div class="ot_work">
                                        <?php echo $list->phone; ?>
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($list->email) && $list->email != '') && $params->get('show_email')!=0) { ?>
                                        <div class="ot_email">
                                            <?php echo $list->email; ?>                        
                                        </div>
                                        <?php } ?>
                                        <?php if ((isset($list->website_url) && $list->website_url != '') && $params->get('show_website_url')!=0) { ?>
                                        <div class="ot_website">
                                            <a href="<?php echo 'http://' . $list->website_url; ?>"><?php echo $list->website_url; ?></a>
                                        </div>
                                        <?php } ?>
										<?php if ((isset($list->rating) && $list->rating != '') && $params->get('show_rating')!=0) { ?>
                                        <div class="ot_ratting">
                                        <?php for ($j = 0; $j < $star; $j++) { ?>
                                        <i class="glyphicon glyphicon-star"></i> 
                                        <?php } ?>
                                        </div>
										<?php } ?>										
                                </div>				
						</span>
					<?php endforeach; ?>
				</div>
			</div>
			<div id="thumbs-wrapper">
				<div id="thumbs">
					<?php $i=-1; foreach ($lists as $list) : $i++;?> 
						<?php if($i == 0) : ?>			
							<a href="#<?php echo $i; ?>"  class="selected"><img src="<?php echo JUri::root() . 'images/testimonies/' . $list->avatar; ?>"></a>
						<?php else : ?>
							<a href="#<?php echo $i; ?>" ><img src="<?php echo JUri::root() . 'images/testimonies/' . $list->avatar; ?>"></a>
						<?php endif ?>
					<?php endforeach; ?>
				</div>
				<a id="prev" href="#"></a>
				<a id="next" href="#"></a>
			</div>
		<script type="text/javascript">
			jQuery(function ($) {

				$('#carousel').carouFredSel({
					responsive: true,
					circular: false,
					auto: false,
					items: {
						visible: 1,
					},
					scroll: {
						fx: 'directscroll'
					}
				});

				$('#thumbs').carouFredSel({
					responsive: true,
					circular: false,
					infinite: false,
					auto: false,
					prev: '#prev',
					next: '#next',
					items: {
						visible: {
							min: 1,
							max: 5
						},
						width: 150,
						height: '90%'
					}
				});

				$('#thumbs a').click(function() {
					$('#carousel').trigger('slideTo', '#' + this.href.split('#').pop() );
					$('#thumbs a').removeClass('selected');
					$(this).addClass('selected');
					return false;
				});

			});
		</script> 
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
