<?php
/**
 * @version $Id: Item.php 9790 2018-03-12 14:53:26Z alatak $
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
 * Class Item.
 *
 * @property string $SKU         ID of the Line Item's product
 * @property string $Description Product description of the item
 * @property int    $Quantity    The number of items
 * @property int    $UnitCost    Price (in cents) of each item
 * @property int    $UnitTax     Unit Tax for each item
 * @property int    $Tax         Combined tax (in cents) for all the items
 * @property int    $Total       Total (including Tax) in cents for all the items.
 */
class Item extends AbstractModel
{
    protected $fillable = [
        'SKU',
        'Description',
        'Quantity',
        'UnitCost',
        'Tax',
        'Total',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->calculateTotal();
    }


    /**
     * Used to set the line item's values so that the total and tax add up correctly.
     *
     * @param int $price
     * @param int $unitTax
     * @param int $quantity
     *
     * @return $this
     */
    public function calculate($price, $unitTax, $quantity = 0)
    {
        $this->Tax = $unitTax * $quantity;
        $this->Total = $this->Tax + ($quantity * $price);

        return $this;
    }

    /**
     * @return $this
     */
    private function calculateTotal()
    {
        if (isset($this->Quantity) && isset($this->UnitCost)) {
            if (isset($this->Tax)) {
                $this->Total = $this->Tax + ($this->Quantity * $this->UnitCost);
            } else {
                $this->Total = $this->Quantity * $this->UnitCost;
            }
        }

        return $this;
    }
}
