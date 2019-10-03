<?php
/**
 * @version $Id: AbstractResponse.php 9790 2018-03-12 14:53:26Z alatak $
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

use Eway\Rapid\Model\AbstractModel;

/**
 * Class AbstractResponse.
 *
 * @property string $Errors A comma separated list of any error encountered, these can be looked up in the Response
 *     Codes section.
 */
abstract class AbstractResponse extends AbstractModel
{
    protected $errors = [];

    /**
     * @return array
     */
    public function getErrors()
    {
        $errors = array_key_exists('Errors', $this->attributes) ? $this->attributes['Errors'] : '';
        if (!is_string($errors) || strlen(trim($errors)) === 0) {
            $errors = [];
        } else {
            $errors = explode(',', $errors);
        }

        return array_merge($this->errors, $errors);
    }

    /**
     * @param $errorCode
     *
     * @return $this
     */
    public function addError($errorCode)
    {
        $this->errors[] = $errorCode;
        if (!array_key_exists('Errors', $this->attributes)) {
            $this->attributes['Errors'] = '';
        }

        return $this;
    }
}
