<?php
/**
 * @version $Id: ShippingAddress.php 9790 2018-03-12 14:53:26Z alatak $
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

use Eway\Rapid\Enum\ShippingMethod;

/**
 * Class ShippingAddress.
 *
 * @property string $City            The customer's shipping city / town / suburb.
 * @property string $Country         The customer's shipping country. This should be the
 *                                    two letter ISO 3166-1 alpha-2 code. This field must be lower
 *                                    case. e.g. Australia = au
 * @property string $Email           The customer's shipping email address
 * @property string $Fax             The fax number of the shipping location.
 * @property string $FirstName       The first name of the person the order is shipped to.
 * @property string $LastName        The last name of the person the order is shipped to.
 * @property string $Phone           The phone number of the person the order is shipped to.
 * @property string $PostalCode      The customer's shipping post / zip code.
 * @property string $ShippingMethod  ShippingMethod enum.
 * @property string $State           The customer's shipping state / county.
 * @property string $Street1         The street address the order is shipped to.
 * @property string $Street2         The street address of the shipping location.
 */
class ShippingAddress extends AbstractModel
{
    protected $fillable = [
        'FirstName',
        'LastName',
        'ShippingMethod',
        'Street1',
        'Street2',
        'City',
        'State',
        'Country',
        'PostalCode',
        'Email',
        'Phone',
        'Fax',
    ];

    /**
     * @param string $shippingMethod
     *
     * @return $this
     */
    public function setShippingMethodAttribute($shippingMethod)
    {
        if (null === $shippingMethod) {
            $this->attributes['ShippingMethod'] = ShippingMethod::UNKNOWN;
        } else {
            $this->validateEnum('Eway\Rapid\Enum\ShippingMethod', 'ShippingMethod', $shippingMethod);
        }

        return $this;
    }
}
