<?php
/**
 * @version $Id: include_eway.php 9790 2018-03-12 14:53:26Z alatak $
 * @package    VirtueMart
 * @subpackage Plugins  - Eway
 * @package VirtueMart
 * @subpackage Payment
 * @link https://virtuemart.net
 *
 * @copyright Copyright (c) 2015 Web Active Corporation Pty Ltd
 *
 * @license MIT License GNU/GPL, see LICENSE.php
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 */
// Check a compatible PHP version is being used (5.4+)
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
	throw new Exception('Minimum PHP version of 5.4.0 required');
}

/**
 * Basic autoloader using the PSR-4 standard - for use when a proper, global
 * autoloader isn't being used (such as Composer's).
 *
 * Based on the example in
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {

	$prefix = 'Eway\\';
	$base_dir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

	// Check if the class being loaded is eWAY's
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}

	// Get the relative class name
	$relative_class = substr($class, $len);

	// Replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators and append with .php
	$file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

	// If the file exists, require it
	if (is_file($file)) {
		require_once $file;
	}
});
