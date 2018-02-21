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

jimport('joomla.application.component.view');

/**
 * View class for a list of Jd_team_showcase.
 *
 * @since  1.6
 */
class Jd_team_showcaseViewTeammembers extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		Jd_team_showcaseHelpersJd_team_showcase::addSubmenu('teammembers');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = Jd_team_showcaseHelpersJd_team_showcase::getActions();

		JToolBarHelper::title(JText::_('COM_JD_TEAM_SHOWCASE_TITLE_TEAMMEMBERS'), 'teammembers.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/teammember';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('teammember.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('teammembers.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('teammember.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('teammembers.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('teammembers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'teammembers.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('teammembers.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('teammembers.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'teammembers.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('teammembers.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_jd_team_showcase');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_jd_team_showcase&view=teammembers');

		$this->extra_sidebar = '';
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`created_by`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_CREATED_BY'),
			'a.`modified_by`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_MODIFIED_BY'),
			'a.`member_name`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_MEMBER_NAME'),
			'a.`member_image`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_MEMBER_IMAGE'),
			'a.`member_bio`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_MEMBER_BIO'),
			'a.`department`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_DEPARTMENT'),
			'a.`job_title`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_JOB_TITLE'),
			'a.`email_address`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_EMAIL_ADDRESS'),
			'a.`social_icons`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_SOCIAL_ICONS'),
			'a.`facebook_url`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_FACEBOOK_URL'),
			'a.`twitter_url`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_TWITTER_URL'),
			'a.`instagram_url`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_INSTAGRAM_URL'),
			'a.`pintrest_url`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_PINTREST_URL'),
			'a.`telephone_number`' => JText::_('COM_JD_TEAM_SHOWCASE_TEAMMEMBERS_TELEPHONE_NUMBER'),
		);
	}
}
