<?php
/**
 * @version $Id: RefundDetails.php 9790 2018-03-12 14:53:26Z alatak $
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
 * Class Refund.
 *
 * @property int    $TransactionID      The ID of either the transaction to refund, or the authorisation to cancel.
 * @property int    $TotalAmount        The total amount to refund the card holder in this transaction in cents.
 *                                      e.g. 1000 = $10.00
 * @property string $InvoiceNumber      The merchant's invoice number
 * @property string $InvoiceDescription merchants invoice description
 * @property string $InvoiceReference   The merchant's invoice reference
 * @property string $CurrencyCode       The merchant's currency (e.g. AUD)
 */
class RefundDetails extends AbstractModel
{
    protected $fillable = [
        'TransactionID',
        'TotalAmount',
        'InvoiceNumber',
        'InvoiceDescription',
        'InvoiceReference',
        'CurrencyCode',
    ];
}
