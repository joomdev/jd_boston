<?php
/**
 * @version $Id: CardDetails.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Model;

/**
 * Class CardDetails.
 *
 * @property string $Name        Name on the card
 * @property string $Number      Credit card number (16-21 digits plaintext, Up to 512 chars for eCrypted values)
 * @property string $ExpiryMonth 2 Digits
 * @property string $ExpiryYear  2 or 4 digits e.g. "15" or "2015"
 * @property string $StartMonth  2 digits (required in some countries)
 * @property string $StartYear   2 or 4 digits (required in some countries)
 * @property string $IssueNumber Card issue number (required in some countries)
 * @property string $CVN         Required for transactions of type Purchase. Optional for other transaction types.
 *                                (3 or 4 digit number plaintext, up to 512 chars for eCrypted values)
 */
class CardDetails extends AbstractModel
{
    protected $fillable = [
        'Name',
        'Number',
        'ExpiryMonth',
        'ExpiryYear',
        'StartMonth',
        'StartYear',
        'IssueNumber',
        'CVN',
    ];
}
