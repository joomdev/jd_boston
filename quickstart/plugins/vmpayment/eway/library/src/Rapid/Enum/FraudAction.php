<?php
/**
 * @version $Id: FraudAction.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Enum;

/**
 * Defines the possible actions that may have been taken when/if an anti-fraud rule on the account has been triggered.
 */
abstract class FraudAction extends AbstractEnum
{
    const NOT_CHALLENGED = 0;
    const ALLOW = 1;
    const REVIEW = 2;
    const PRE_AUTH = 3;
    const PROCESSED = 4;
    const APPROVED = 5;
    const BLOCK = 6;
}
