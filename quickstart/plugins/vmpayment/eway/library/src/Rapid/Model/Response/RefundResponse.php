<?php
/**
 * @version $Id: RefundResponse.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Model\Response;

use Eway\Rapid\Model\Customer;
use Eway\Rapid\Model\Refund;
use Eway\Rapid\Model\Support\HasCustomerTrait;
use Eway\Rapid\Model\Support\HasRefundTrait;
use Eway\Rapid\Model\Support\HasVerificationTrait;
use Eway\Rapid\Model\Verification;

/**
 * This Response is returned by the Refund Method. It wraps the TransactionStatus and
 * the Echoed back Refund Type with the standard error fields required by an API return type.
 *
 * @property string       AuthorisationCode
 * @property Customer     Customer
 * @property array        Errors
 * @property Refund       Refund
 * @property string       ResponseCode
 * @property string       ResponseMessage
 * @property string       TransactionID
 * @property string       TransactionStatus
 * @property Verification Verification
 */
class RefundResponse extends AbstractResponse
{
    use HasCustomerTrait, HasVerificationTrait, HasRefundTrait;

    protected $fillable = [
        'AuthorisationCode',
        'Customer',
        'Errors',
        'Refund',
        'ResponseCode',
        'ResponseMessage',
        'TransactionID',
        'TransactionStatus',
        'Verification',
    ];
}
