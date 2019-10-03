<?php
/**
 * @version $Id: Address.php 9790 2018-03-12 14:53:26Z alatak $
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
 * Class Address.
 *
 * @property string $Street1    First line of the street address. e.g. "Unit 1"
 * @property string $Street2    Second line of the street address. e.g. "6 Coonabmble st"
 * @property string $City       City for the address, e.g. "Gulargambone"
 * @property string $State      State or province code. e.g. 'NSW"
 * @property string $Country    Two digit Country Code. e.g. "AU"
 * @property string $PostalCode e.g. 2828
 */
class Address extends AbstractModel
{
    protected $fillable = [
        'Street1',
        'Street2',
        'City',
        'State',
        'Country',
        'PostalCode',
    ];
}
