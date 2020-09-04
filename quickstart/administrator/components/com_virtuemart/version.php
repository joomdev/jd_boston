<?php
if( !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: version.php 9821 2018-04-16 18:04:39Z Milbo $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2005-2011 VirtueMart Team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.org
*/



	/** Version information */
	class vmVersion {
		/** @var string Product */
		static $PRODUCT = 'VirtueMart';
		/** @var int Release Number */
		static $RELEASE = '3.8.4';
		/** @var string Development Status */
		static $DEV_STATUS = 'BUGFIX';
		/** @var string Codename */
		static $CODENAME = 'Eagle owl';
		/** @var string Date */
		static $RELDATE = 'June 18 2020';
		/** @var string Time */
		static $RELTIME = '1122';
		/** @var string Timezone */
		static $RELTZ = 'GMT';
		/** @var string Revision */
		static $REVISION = '10335';
		/** @var string Copyright Text */
		static $COPYRIGHT = 'Copyright (C) 2004 - 2020 Virtuemart Team. All rights reserved.';
		/** @var string URL */
		static $URL = '<a href="http://virtuemart.net">VirtueMart</a> is a Free ecommerce framework released under the GNU/GPL2 License.';

		static $shortversion = '';
		static $myVersion = '';

		public function __construct() {

			self::$shortversion = vmVersion::$PRODUCT . " " . vmVersion::$RELEASE . " " . vmVersion::$DEV_STATUS. " ";

			self::$myVersion = self::$shortversion .' Revision: '.vmVersion::$REVISION. " [".vmVersion::$CODENAME ."] <br />" . vmVersion::$RELDATE . " "
				. vmVersion::$RELTIME . " " . vmVersion::$RELTZ;
		}
	}





// pure php no closing tag