<?php
/**
 * @version $Id: Customer.php 9790 2018-03-12 14:53:26Z alatak $
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

use Eway\Rapid\Model\Support\HasCardDetailTrait;

/**
 * Class Customer.
 *
 * @property string      $TokenCustomerID An eWAY-issued ID that represents the Token customer
 * @property string      $Reference       Merchant's own reference ID for the customer
 * @property string      $Title           The customer's title, one of: Mr., Ms., Mrs., Miss, Dr., Sir., Prof.
 * @property string      $FirstName       Customer's First Name
 * @property string      $LastName        Customer's Last name
 * @property string      $CompanyName     Customer's company name
 * @property string      $JobDescription  Role or job description
 * @property string      $Street1         First line of the street address. e.g. "Unit 1"
 * @property string      $Street2         Second line of the street address. e.g. "6 Coonabmble st"
 * @property string      $City            City for the address, e.g. "Gulargambone"
 * @property string      $State           State or province code. e.g. 'NSW"
 * @property string      $Country         The customer's country. This should be the two letter ISO 3166-1 alpha-2
 *                                        code. This field must be lower case. e.g. Australia = au
 * @property string      $PostalCode      e.g. 2828
 * @property string      $Phone           Customer's Phone
 * @property string      $Mobile          Customer's Mobile Phone
 * @property string      $Fax             Customer's Fax number
 * @property string      $Url             URL for customer's site
 * @property string      $Comments        Comments attached to this customer.
 * @property string      $RedirectUrl     URL to redirect after transaction compelte
 * @property string      $CancelUrl       URL to use if a Shared Page transaction is cancelled
 * @property string      $SecuredCardData Card data ID, used for Secure Fields, Visa Checkout, AMEX Express Checkout
 *                                        and Android Pay
 * @property string      $CustomView      Set the theme of the Responsive Shared Page
 * @property string      $LogoUrl         The URL of the merchant’s logo to display on the shared page
 * @property string      $HeaderText      Short text description to be placed under the logo on the shared page
 * @property string      $Language        Language code determines the language that the shared page will be
 *                                        displayed in
 * @property string      $CustomerReadOnly When set to false, cardholders will be able to edit the information on the
 *                                         shared page, even if it’s sent through in the server side request.
 * @property boolean     $VerifyCustomerPhone Set whether the customer’s phone number should be confirmed using
 *                                           Beagle Verify
 * @property boolean     $VerifyCustomerEmail Set whether the customer’s email should be confirmed using
 *                                            Beagle Verify
 * @property CardDetails $CardDetails     Contains card detials for a Direct transaction
 * @property string      $CardName        Response field only
 * @property string      $CardNumber      Response field only
 * @property string      $CardStartMonth  Response field only
 * @property string      $CardStartYear   Response field only
 * @property string      $CardExpiryMonth Response field only
 * @property string      $CardExpiryYear  Response field only
 * @property string      $CardIssueNumber Response field only
 */
class Customer extends AbstractModel
{
    use HasCardDetailTrait;

    protected $fillable = [
        'TokenCustomerID',
        'Reference',
        'Title',
        'FirstName',
        'LastName',
        'CompanyName',
        'JobDescription',
        // Address
        'Street1',
        'Street2',
        'City',
        'State',
        'Country',
        'PostalCode',
        'IsActive',
        'Phone',
        'Mobile',
        'Fax',
        'Email',
        'Url',
        'Comments',
        'CardDetails',
        // CardDetails again (used for response)
        'CardName',
        'CardNumber',
        'CardStartMonth',
        'CardStartYear',
        'CardExpiryMonth',
        'CardExpiryYear',
        'CardIssueNumber',
        // Other
        'RedirectUrl',
        'CancelUrl',
        // For Secure Fields
        'SecuredCardData',
        // For changing theme in Responsive Shared Page
        'CustomView',
        'LogoUrl',
        'HeaderText',
        'Language',
        'CustomerReadOnly',
        'VerifyCustomerPhone',
        'VerifyCustomerEmail',
    ];
}
