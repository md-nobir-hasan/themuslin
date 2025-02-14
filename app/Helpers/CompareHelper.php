<?php

namespace App\Helpers;

use App\Compare\Compare;

class CompareHelper
{
    private static $instance = null;

    public function __construct()
    {
        self::init();
    }

    private static function init()
    {
        if (self::$instance === null) {
            self::$instance = new Compare();
        }
        return self::$instance;
    }

    /**
     * Get all items in  wishlist.
     *
     * @return array
     */
    public static function getItems()
    {
        return self::init()->getItems();
    }

    /**
     * Add item to wishlist.
     *
     * @param string $id
     * @param int    $quantity
     * @param array  $attributes
     *
     * @return bool
     */
    public static function add(int $id)
    {
        return self::init()->add($id);
    }

    /**
     * Remove item from wishlist.
     *
     * @param string $id
     * @param array  $attributes
     *
     * @return bool
     */
    public static function remove($id)
    {
        return self::init()->remove($id);
    }

    /**
     * Remove all items from wishlist.
     */
    public static function clear()
    {
        return self::init()->clear();
    }
}
