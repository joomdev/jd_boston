<?php
/**
 * @version $Id: QueryAccessCodeResponse.php 9790 2018-03-12 14:53:26Z alatak $
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

use Eway\Rapid\Model\Support\HasBeagleVerificationTrait;
use Eway\Rapid\Model\Support\HasVerificationTrait;
use Eway\Rapid\Model\Verification;

/**
 * Class QueryAccessCodeResponse.
 *
 * @property string       $AccessCode         An echo of the access code used in the request
 * @property string       $AuthorisationCode  The authorisation code for this transaction as returned by the bank
 * @property string       $BeagleScore        Fraud score representing the estimated probability
 *                                            that the order is fraud. A value between 0.01 to 100.00
 *                                            representing the % risk the transaction is fraudulent.
 * @property Verification $BeagleVerification Results of the Beagle Verification identification
 * @property string       $Errors             A comma separated list of any error encountered, these can be
 *                                            looked up using Rapid::getMessage().
 * @property string       $InvoiceNumber      An echo of the merchant's invoice number for this transaction
 * @property string       $InvoiceReference   An echo of the merchant's reference number for this transaction
 * @property array        $Options
 * @property string       $Message
 * @property string       $ResponseCode       The two digit response code returned from the bank
 * @property string       $ResponseMessage    One or more Response Codes that describes the result of the
 *                                            action performed. If a Beagle Alert is triggered, this
 *                                            may contain multiple codes: e.g. D4405, F7003
 * @property int          $TokenCustomerID    An eWAY-issued ID that represents the Token customer that was
 *                                            loaded or created for this transaction (if applicable)
 * @property int          $TotalAmount        The amount that was authorised for this transaction
 * @property int          $TransactionID      A unique identifier that represents the transaction in eWAY's system
 * @property boolean      $TransactionStatus  Indicates whether the transaction was successful or not
 * @property Verification $Verification       Currently unused
 */
class QueryAccessCodeResponse extends AbstractResponse
{
    use HasBeagleVerificationTrait, HasVerificationTrait;

    protected $fillable = [
        'AccessCode',
        'AuthorisationCode',
        'BeagleScore',
        'BeagleVerification',
        'Errors',
        'InvoiceNumber',
        'InvoiceReference',
        'Message',
        'Options',
        'ResponseCode',
        'ResponseMessage',
        'TokenCustomerID',
        'TotalAmount',
        'TransactionID',
        'TransactionStatus',
        'Verification',
    ];
}
