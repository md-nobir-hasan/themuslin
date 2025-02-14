<?php

namespace App\Helpers;

use App\Wishlist\Wishlist;


class WishlistHelper
{
    private static $instance = null;

    public function __construct()
    {
        self::init();
    }

    private static function init()
    {
        if (self::$instance === null) {
            self::$instance = new Wishlist([
                'wishlistMaxItem' => 0,
                'itemMaxQuantity' => 0,
                'useCookie' => false,
            ]);
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
	 * Check if the wishlist is empty.
	 *
	 * @return bool
	 */
    public static function isEmpty()
    {
        return self::init()->isEmpty();
    }

    /**
	 * Get the total of item in wishlist.
	 *
	 * @return int
	 */
    public static function getTotalItem()
    {
        return self::init()->getTotalItem();
    }

    /**
	 * Get the total of item quantity in wishlist.
	 *
	 * @return int
	 */
    public static function getTotalQuantity()
    {
        return self::init()->getTotalQuantity();
    }

    /**
	 * Get the sum of a attribute from wishlist.
	 *
	 * @param string $attribute
	 *
	 * @return int
	 */
    public static function getAttributeTotal($attribute = 'price')
    {
        return self::init()->getAttributeTotal($attribute);
    }

    /**
	 * Check if a item exist in wishlist.
	 *
	 * @param string $id
	 * @param array  $attributes
	 *
	 * @return bool
	 */
    public static function isItemExists($id, $attributes = [])
    {
        return self::init()->isItemExists($id, $attributes);
    }

    /**
	 * Get one item from wishlist
	 *
	 * @param string $id
	 * @param string $hash
	 *
	 * @return array
	 */
    public static function getItem($id, string $hash = null)
    {
        return self::init()->getItem($id, $hash);
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
    public static function add(int $id, $quantity, array $attributes = []){
        return self::init()->add($id, $quantity, $attributes);
    }

    /**
	 * Update item quantity.
	 *
	 * @param string $id
	 * @param int    $quantity
	 * @param array  $attributes
	 *
	 * @return bool
	 */
    public static function update($id, int $quantity = 1, $attributes = [])
    {
        return self::init()->update($id, $quantity, $attributes);
    }

    /**
	 * Remove item from wishlist.
	 *
	 * @param string $id
	 * @param array  $attributes
	 *
	 * @return bool
	 */
    public static function remove($id, $attributes = [])
    {
        return self::init()->remove($id, $attributes);
    }

    /**
	 * Remove all items from wishlist.
	 */
    public static function clear()
    {
        return self::init()->clear();
    }

    /**
	 * Destroy wishlist session.
	 */
    public static function destroy()
    {
        return self::init()->destroy();
    }
}
