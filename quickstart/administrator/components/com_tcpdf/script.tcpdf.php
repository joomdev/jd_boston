<?php
defined ('_JEXEC') or die('Restricted access');

/**
 *
 * VirtueMart script file
 *
 * This file is executed during install/upgrade and uninstall
 *
 * @author Max Milbers, Valérie Isaksen
 * @package VirtueMart
 */

class com_tcpdfInstallerScript {

	public function preflight () {

		defined ('DS') or define('DS', DIRECTORY_SEPARATOR);

		$max_execution_time = ini_get ('max_execution_time');
		if ((int)$max_execution_time < 120) {
			@ini_set ('max_execution_time', '120');
		}

		$mL = ini_get('memory_limit');
		$mLimit = 0;
		if(!empty($mL)){
			$u = strtoupper(substr($mL,-1));
			$mLimit = (int)substr($mL,0,-1);
			if($mLimit>0){

				if($u == 'M'){
					//$mLimit = $mLimit * 1048576;
				} else if($u == 'G'){
					$mLimit = $mLimit * 1024;
				} else if($u == 'K'){
					$mLimit = $mLimit / 1024.0;
				} else {
					$mLimit = $mLimit / 1048576.0;
				}
				$mLimit = (int) $mLimit - 5; // 5 MB reserve
				if($mLimit<=0){
					$mLimit = 1;
					$m = 'Increase your php memory limit, which is must too low to run VM, your current memory limit is set as '.$mL.' ='.$mLimit.'MB';
					vmError($m,$m);
				}
			}
		}
		if ($mLimit < 128) {
			@ini_set ('memory_limit', '128M');
		}
	}

	public function install () {
		//$this->vmInstall();
	}

	public function discover_install () {
		$this->tcpdfInstall ();
	}

	public function postflight () {

		$this->tcpdfInstall ();
	}

	public function tcpdfInstall () {


		jimport ('joomla.filesystem.file');
		jimport ('joomla.installer.installer');

		$this->path =  dirname(__FILE__);

		// libraries auto move
		$src = $this->path . "/libraries";
		$dst = JPATH_ROOT .'/libraries';
		$this->recurse_copy ($src, $dst);

		if(JFolder::exists(JPATH_ROOT .'/administrator/components/com_tcpdf/libraries')){
			JFolder::delete(JPATH_ROOT .'/administrator/components/com_tcpdf/libraries');
		}

		if(JFolder::exists(JPATH_ROOT .'/libraries/joomla/pdf')){
			JFolder::delete(JPATH_ROOT .'/libraries/joomla/pdf');
		}
		if(JFolder::exists(JPATH_ROOT .'/libraries/tcpdf')){
			JFolder::delete(JPATH_ROOT .'/libraries/tcpdf');
		}
		$html = '<a
				href="http://virtuemart.net"
				target="_blank"> <img
					border="0"
					align="left" style="margin-right: 20px"
					src="components/com_virtuemart/assets/images/vm_menulogo.png"
					alt="Cart" /> </a>';
		$html .= '<h3 style="clear: both;">TcPdf moved to the joomla libraries folder</h3>';
		$html .= "<h3>Installation Successful.</h3>";

		echo $html;
		$_REQUEST['tcpdf_html'] = $html;

		return TRUE;

	}

	/**
	 * copy all $src to $dst folder and remove it
	 *
	 * @author Max Milbers
	 * @param String $src path
	 * @param String $dst path
	 * @param String $type modulesBE, modules, plugins, languageBE, languageFE
	 */
	private function recurse_copy ($src, $dst) {

		static $failed = false;
		$dir = opendir ($src);

		if (is_resource ($dir)) {
			while (FALSE !== ($file = readdir ($dir))) {
				if (($file != '.') && ($file != '..')) {
					if (is_dir ($src . DS . $file)) {
						if(!JFolder::create($dst . DS . $file)){
							$app = JFactory::getApplication ();
							$app->enqueueMessage ('Couldnt create folder ' . $dst . DS . $file);
						}
						$this->recurse_copy ($src . DS . $file, $dst . DS . $file);
					} else {
						if (JFile::exists ($dst . DS . $file)) {
							if (!JFile::delete ($dst . DS . $file)) {
								$app = JFactory::getApplication ();
								$app->enqueueMessage ('Couldnt delete ' . $dst . DS . $file);
								//return false;
							}
						}
						if (!JFile::move ($src . DS . $file, $dst . DS . $file)) {
							$app = JFactory::getApplication ();
							$app->enqueueMessage ('Couldnt move ' . $src . DS . $file . ' to ' . $dst . DS . $file);
							$failed = true;
							//return false;
						}
					}
				}
			}
			closedir ($dir);
			if (is_dir ($src) and !$failed) {
				JFolder::delete ($src);
			}
		} else {
			$app = JFactory::getApplication ();
			$app->enqueueMessage ('TcPdf Installer recurse_copy; Couldnt read source directory '.$dir);
			return false;
		}
		return true;
	}


	public function uninstall () {

		return TRUE;
	}

	/**
	 * creates a folder with empty html file
	 *
	 * @author Max Milbers
	 *
	 */
	public function createIndexFolder ($path) {

		if (JFolder::create ($path)) {
			/*if (!JFile::exists ($path . DS . 'index.html')) {
				JFile::copy (JPATH_ROOT . DS . 'components' . DS . 'index.html', $path . DS . 'index.html');
			}*/
			return TRUE;
		}
		return FALSE;
	}

}


// pure php no tag
