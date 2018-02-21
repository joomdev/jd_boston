<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Jd_team_showcase
 * @author     Suraj Sharma <surajmehta871@gmail.com>
 * @copyright  2016 Suraj Sharma
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_jd_team_showcase/css/form.css');


?>
<style>
	#jform_member_bio_ifr{height:150px!important;}
</style>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'teammember.cancel') {
			Joomla.submitform(task, document.getElementById('teammember-form'));
		}
		else {
			
			if (task != 'teammember.cancel' && document.formvalidator.isValid(document.id('teammember-form'))) {
				
				Joomla.submitform(task, document.getElementById('teammember-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_jd_team_showcase&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="teammember-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JD_TEAM_SHOWCASE_TITLE_TEAMMEMBER', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
				<?php if(empty($this->item->modified_by)){ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />

				<?php } ?>				<?php echo $this->form->renderField('member_name'); ?>
				<?php echo $this->form->renderField('member_image'); ?>
				<?php echo $this->form->renderField('member_bio'); ?>
				<?php echo $this->form->renderField('department'); ?>
				<?php echo $this->form->renderField('job_title'); ?>
				<?php echo $this->form->renderField('is_email'); ?>
				<?php echo $this->form->renderField('email_address'); ?>
				<?php echo $this->form->renderField('is_facebook'); ?>
				<?php echo $this->form->renderField('facebook_url'); ?>
				<?php echo $this->form->renderField('is_twitter'); ?>
				<?php echo $this->form->renderField('twitter_url'); ?>
				
				<?php echo $this->form->renderField('is_linkedin'); ?>
				<?php echo $this->form->renderField('linkedin_url'); ?>
				
				<?php echo $this->form->renderField('is_googlepluse'); ?>
				<?php echo $this->form->renderField('googlepluse_url'); ?>
				
				<?php echo $this->form->renderField('is_instagram'); ?>
				<?php echo $this->form->renderField('instagram_url'); ?>
				<?php echo $this->form->renderField('is_pintrest'); ?>
				<?php echo $this->form->renderField('pintrest_url'); ?>
				<?php echo $this->form->renderField('is_telephone'); ?>
				<?php echo $this->form->renderField('telephone_number'); ?>


					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
