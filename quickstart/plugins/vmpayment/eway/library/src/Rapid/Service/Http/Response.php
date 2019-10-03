<?php
/**
 * @version $Id: Response.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Service\Http;

use Eway\Rapid\Contract\Http\ResponseInterface;
use Eway\Rapid\Model\Support\CanGetClassTrait;

/**
 * Class Response.
 */
class Response implements ResponseInterface
{
    use CanGetClassTrait;

    /** @var int */
    private $statusCode = 200;

    /**
     * @param int    $status Status code for the response, if any.
     * @param string $body   Response body.
     */
    public function __construct($status = 200, $body = null, $error = null)
    {
        $this->statusCode = (int)$status;
        $this->body = $body;
        $this->error = $error;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getError()
    {
        return $this->error;
    }
}
