<?php
/**
 * @version $Id: HasItemsTrait.php 9790 2018-03-12 14:53:26Z alatak $
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
namespace Eway\Rapid\Model\Support;

use Eway\Rapid\Model\Item;

/**
 * Trait HasItemsTrait.
 */
trait HasItemsTrait
{
    /**
     * @param array $items
     *
     * @return $this
     */
    public function setItemsAttribute($items)
    {
        if (!is_array($items)) {
            throw new \InvalidArgumentException('Items must be an array');
        }

        foreach ($items as $key => $item) {
            if (!($item instanceof Item)) {
                $items[$key] = new Item($item);
            }
        }

        $this->attributes['Items'] = $items;

        return $this;
    }
}
