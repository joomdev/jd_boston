<?php
defined ('_JEXEC') or die('Restricted access');

/**
 *
 * VirtueMart script file
 *
 * This file is executed during install/upgrade and uninstall
 *
 * @author Max Milbers
 * @package VirtueMart
 */

class pkg_virtuemart_pkgInstallerScript {

	public function install () {
		//$this->vmInstall();
	}

	public function discover_install () {
		//$this->vmInstall();
	}

	public function postflight () {

		include(VMPATH_ADMIN.'/views/updatesmigration/tmpl/insfinished.php');

		echo vRequest::get('aio_html','something went wrong installing the AIO');
		echo vRequest::get('tcpdf_html','something went wrong installing the TcPdf');
	}

}