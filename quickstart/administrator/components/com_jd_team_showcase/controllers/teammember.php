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

jimport('joomla.application.component.controllerform');

/**
 * Teammember controller class.
 *
 * @since  1.6
 */
class Jd_team_showcaseControllerTeammember extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'teammembers';
		parent::__construct();
	}
}
