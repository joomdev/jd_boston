<?php
/**
 * @version $Id: CreateTransactionResponse.php 9790 2018-03-12 14:53:26Z alatak $
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
use Eway\Rapid\Model\Payment;
use Eway\Rapid\Model\Support\HasCustomerTrait;
use Eway\Rapid\Model\Support\HasPaymentTrait;
use Eway\Rapid\Model\Support\HasTransactionTypeTrait;
use Eway\Rapid\Model\Support\HasVerificationTrait;
use Eway\Rapid\Model\Verification;

/**
 * The response is returned from a CreateTransaction method call.
 * This will echo back the details of the Transaction (Customer, Payment, Items, options etc).
 * Additional fields may also be set when the Create request has a PaymentMethod of
 * Responsive Shared or Transparent Redirect. If the Transaction is processed,
 * then the Status member will be populated with the results of the transaction.
 *
 * @property string       AccessCode
 * @property string       AuthorisationCode The authorisation code for this transaction as
 *                                          returned by the bank
 * @property string       BeagleScore       Fraud score representing the estimated probability
 *                                          that the order is fraud. A value between 0.01 to 100.00
 *                                          representing the % risk the transaction is fraudulent.
 * @property string       CompleteCheckoutURL
 * @property Customer     Customer
 * @property string       Errors            A comma separated list of any error encountered, these
 *                                          can be looked up using Rapid::getMessage().
 * @property string       FormActionURL     (Only for payment methods of TransparentRedirect)
 *                                          URL that the merchant's credit card collection form
 *                                          should post to to complete the transaction.
 * @property Payment      Payment
 * @property string       ResponseCode      The two digit response code returned from the bank
 * @property string       ResponseMessage   A code that describes the result of the action performed
 * @property string       SharedPaymentUrl  Only for payment methods of ResponsiveShared)
 *                                          URL to the Responsive Shared Page that the cardholder's
 *                                          browser should be redirected to to complete payment
 * @property int          TotalAmount       The amount that was authorised for this transaction
 * @property int          TransactionID     A unique identifier that represents the transaction in eWAY's system
 * @property boolean      TransactionStatus A Boolean value that indicates whether the transaction was successful or not
 * @property string       TransactionType   The transaction type that this transaction was processed under.
 *                                          One of: Purchase, MOTO, Recurring
 * @property Verification Verification
 * @property string       AmexECEncryptedData (v40+ only) A token used to configure AMEX Express Checkout
 */
class CreateTransactionResponse extends AbstractResponse
{
    use HasTransactionTypeTrait, HasCustomerTrait, HasPaymentTrait, HasVerificationTrait;

    protected $fillable = [
        'AccessCode',
        'AuthorisationCode',
        'BeagleScore',
        'CompleteCheckoutURL',
        'Customer',
        'Errors',
        'FormActionURL',
        'Payment',
        'ResponseCode',
        'ResponseMessage',
        'SharedPaymentUrl',
        'TotalAmount',
        'TransactionID',
        'TransactionStatus',
        'TransactionType',
        'Verification',
        'AmexECEncryptedData',
    ];
}
