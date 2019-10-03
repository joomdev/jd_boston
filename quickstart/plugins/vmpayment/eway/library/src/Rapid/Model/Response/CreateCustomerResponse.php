<?php
/**
 * @version $Id: CreateCustomerResponse.php 9790 2018-03-12 14:53:26Z alatak $
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
use Eway\Rapid\Model\Support\HasCustomerTrait;
use Eway\Rapid\Model\Support\HasPaymentTrait;
use Eway\Rapid\Model\Support\HasVerificationTrait;

/**
 * The response is returned from a CreateTransaction method call.
 * It will echo back the details of the Customer that has been, or will be, created.
 * Additional fields may also be set when the Create request has a PaymentMethod
 * of Responsive Shared or Transparent Redirect.
 *
 * @property array    Errors           List of all validation, or processing, during the method call.
 *                                      empty/null if no errors occured. This member combines all
 *                                      errors related to the request.
 * @property Customer Customer         The Customer created by the method call. This will echo back
 *                                      the properties of the Customer adding the TokenCustomerID
 *                                      for the created customer.
 * @property string   SharedPaymentUrl (Only for payment method of ResponsiveShared)
 *                                      URL to the Responsive Shared Page that the cardholder's
 *                                      browser should be redirected to to capture the card to save
 *                                      with the new customer.
 * @property string   FormActionUrl    (Only for payment method of TransparentRedirect)
 *                                      URL That the merchant's credit card collection form should
 *                                      post to to capture the card to be saved with the new customer.
 * @property string   AccessCode       The AccessCode for this transaction (can be used with the
 *                                      customer query method call for searching before and after
 *                                      the card capture is completed)
 */
class CreateCustomerResponse extends AbstractResponse
{
    protected $fillable = [
        'SharedPaymentUrl',
        'FormActionUrl',
        'AccessCode',
        'AuthorisationCode',
        'BeagleScore',
        'Customer',
        'Errors',
        'Payment',
        'ResponseCode',
        'ResponseMessage',
        'TransactionID',
        'TransactionStatus',
        'TransactionType',
        'Verification',
        'FormActionURL',
        'CompleteCheckoutURL',
    ];

    use HasCustomerTrait, HasVerificationTrait, HasPaymentTrait;
}
