<?php
/**
 *
 * Modify user form view, User info
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_shopper.php 10163 2019-10-09 07:09:10Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

echo $this->loadTemplate('address_userfields');

if ($this->userDetails->virtuemart_user_id ) {
  echo $this->loadTemplate('address_addshipto');
}

if(!empty($this->virtuemart_userinfo_id)){
	echo '<input type="hidden" name="virtuemart_userinfo_id" value="'.(int)$this->virtuemart_userinfo_id.'" />';
}
?>
<input type="hidden" name="task" value="saveUser" />
<input type="hidden" name="address_type" value="<?php echo $this->address_type; ?>"/>

