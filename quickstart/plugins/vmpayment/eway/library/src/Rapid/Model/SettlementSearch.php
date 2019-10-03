<?php
/**
 * @version $Id: SettlementSearch.php 9790 2018-03-12 14:53:26Z alatak $
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
 * Class SettlementSearch.
 *
 * @property string $ReportMode      One of Both, SummaryOnly or TransactionOnly
 * @property string $SettlementDate  A settlement date will need to be entered to query.
 *                                   This should be formatted as YYYY-MM-DD. Use this or StartDate & EndDate
 * @property string $StartDate       This parameter set the start of a filtered date range.
 *                                   This should be formatted as YYYY-MM-DD. Use this or SettlementDate
 * @property string $EndDate         This parameter set the end of a filtered date range.
 *                                   This should be formatted as YYYY-MM-DD. Use this or SettlementDate
 * @property string $CardType        The code for the card type to filter by. One of: ALL, VI, MC,
 *                                   AX, DC, JC, MD, MI, SO, LA, DS
 * @property string $Currency        The currency to filter the report by. The three digit ISO 4217
 *                                   currency code should be used or ALL for all currencies.
 * @property int    $Page            The page number to retrieve
 * @property int    $PageSize        The number of records to retrieve per page
 */
class SettlementSearch extends AbstractModel
{
    protected $fillable = [
        'ReportMode',
        'SettlementDate',
        'StartDate',
        'EndDate',
        'CardType',
        'Currency',
        'Page',
        'PageSize',
    ];
}
