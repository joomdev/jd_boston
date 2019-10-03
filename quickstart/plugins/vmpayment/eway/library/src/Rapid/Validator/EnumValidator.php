<?php
/**
 * @version $Id: EnumValidator.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Validator;

use InvalidArgumentException;

/**
 * Class EnumValidator.
 */
abstract class EnumValidator
{
    /**
     * @param $class
     * @param $field
     * @param $value
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public static function validate($class, $field, $value)
    {
        $abstractEnum = 'Eway\Rapid\Enum\AbstractEnum';
        if (!is_subclass_of($class, $abstractEnum)) {
            throw new InvalidArgumentException(sprintf('%s must extends %s', $class, $abstractEnum));
        }

        if (!call_user_func($class.'::isValidValue', $value, true)) {
            throw new InvalidArgumentException(call_user_func($class.'::getValidationMessage', $field));
        }

        return $value;
    }
}
