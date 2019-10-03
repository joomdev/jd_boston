<?php
/**
 * @version $Id: Verification.php 9790 2018-03-12 14:53:26Z alatak $
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
 * Class Verification.
 *
 * @property string $CVN         Result of CVN Verification by card processor
 * @property string $Address     Result of Address Verification by card processor
 * @property string $Email       Result of email verification by card processor
 * @property string $Mobile      Result of Mobile verification by card processor
 * @property string $Phone       Result of phone verification by card processor
 * @property string $BeagleEmail Result of email verification from responsive shared page
 * @property string $BeaglePhone Result of phone number verification from responsive shared page
 */
class Verification extends AbstractModel
{
    protected $fillable = [
        'CVN',
        'Address',
        'Email',
        'Mobile',
        'Phone',
    ];

    /**
     * @param string $cvn
     *
     * @return $this
     */
    public function setCVNAttribute($cvn)
    {
        $this->validateEnum('Eway\Rapid\Enum\VerifyStatus', 'CVN', $cvn);

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddressAttribute($address)
    {
        $this->validateEnum('Eway\Rapid\Enum\VerifyStatus', 'Address', $address);

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmailAttribute($email)
    {
        $this->validateEnum('Eway\Rapid\Enum\VerifyStatus', 'Email', $email);

        return $this;
    }

    /**
     * @param string $mobile
     *
     * @return $this
     */
    public function setMobileAttribute($mobile)
    {
        $this->validateEnum('Eway\Rapid\Enum\VerifyStatus', 'Mobile', $mobile);

        return $this;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhoneAttribute($phone)
    {
        $this->validateEnum('Eway\Rapid\Enum\VerifyStatus', 'Phone', $phone);

        return $this;
    }
}
