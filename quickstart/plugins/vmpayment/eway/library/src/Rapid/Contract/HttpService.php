<?php
/**
 * @version $Id: HttpService.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Contract;

use Eway\Rapid\Contract\Http\ResponseInterface;

/**
 * Interface HttpService.
 */
interface HttpService
{
    /*
     * API endpoints
     */

    /**
     * API Transaction Endpoint.
     */
    const API_TRANSACTION = 'Transaction';

    /**
     * API Transaction Endpoint with param Reference (AccessCode or TransactionID).
     */
    const API_TRANSACTION_QUERY = 'Transaction/{Reference}';

    /**
     * API Transaction Invoice Number Endpoint with param InvoiceNumber
     */
    const API_TRANSACTION_INVOICE_NUMBER_QUERY = '/Transaction/InvoiceNumber/{InvoiceNumber}';

    /**
     * API Transaction Invoice Reference Endpoint with param Reference
     */
    const API_TRANSACTION_INVOICE_REFERENCE_QUERY = '/Transaction/InvoiceRef/{InvoiceReference}';

    /**
     * API AccessCode Endpoint.
     */
    const API_ACCESS_CODE = 'AccessCodes';

    /**
     * API AccessCode Endpoint with param AccessCode.
     */
    const API_ACCESS_CODE_QUERY = 'AccessCode/{AccessCode}';

    /**
     * API AccessCodeShared Endpoint.
     */
    const API_ACCESS_CODE_SHARED = 'AccessCodesShared';

    /**
     * API Customer Endpoint with param TokenCustomerID
     */
    const API_CUSTOMER_QUERY = 'Customer/{TokenCustomerID}';

    /**
     * API Transaction Refund Endpoint with param TransactionID
     */
    const API_TRANSACTION_REFUND = 'Transaction/{TransactionID}/Refund';

    /**
     * API Capture Payment Endpoint
     */
    const API_CAPTURE_PAYMENT = 'CapturePayment';

    /**
     * API Cancel Authorisation Endpoint
     */
    const API_CANCEL_AUTHORISATION = 'CancelAuthorisation';

    /**
     * API Settlement Search Endpoint
     */
    const API_SETTLEMENT_SEARCH = 'Search/Settlement';

    /**
     * cURL hex representation of version 7.30.0
     */
    const CURL_NO_QUIRK_VERSION = 0x071E00;

    /**
     * @param $reference
     *
     * @return ResponseInterface
     */
    public function getTransaction($reference);

    /**
     * @param $invoiceNumber
     *
     * @return ResponseInterface
     */
    public function getTransactionInvoiceNumber($invoiceNumber);

    /**
     * @param $invoiceReference
     *
     * @return ResponseInterface
     */
    public function getTransactionInvoiceReference($invoiceReference);


    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function postTransaction($data);


    /**
     * @param $transactionId
     * @param $data
     *
     * @return ResponseInterface
     */
    public function postTransactionRefund($transactionId, $data);


    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function postAccessCodeShared($data);


    /**
     * @param $accessCode
     *
     * @return ResponseInterface
     */
    public function getAccessCode($accessCode);


    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function postAccessCode($data);


    /**
     * @param $tokenCustomerId
     *
     * @return ResponseInterface
     */
    public function getCustomer($tokenCustomerId);


    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function postCapturePayment($data);


    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function postCancelAuthorisation($data);

    /**
     * @param $query
     *
     * @return ResponseInterface
     */
    public function getSettlementSearch($query);

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl);

    /**
     * @return string
     */
    public function getBaseUrl();

    /**
     * @param int $version
     */
    public function setVersion($version);

    /**
     * @return int
     */
    public function getVersion();
}
