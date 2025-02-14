<?php

namespace App\Helpers;

use App\Cart\Cart;

/**
 * @see
 * @method static
 */
class CartHelper
{
    private static $instance = null;

    public function __construct()
    {
        self::init();
    }

    private static function init()
    {
        if (self::$instance === null) {
            self::$instance = new Cart([
                'cartMaxItem' => 0,
                'itemMaxQuantity' => 0,
                'useCookie' => false,
            ]);
        }
        return self::$instance;
    }

    /**
     * Get all items in  cart.
     *
     * @return array
     */
    public static function getItems()
    {
        return self::init()->getItems();
    }

    /**
     * Check if the cart is empty.
     *
     * @return bool
     */
    public static function isEmpty()
    {
        return self::init()->isEmpty();
    }

    /**
     * Get the total of item in cart.
     *
     * @return int
     */
    public static function getTotalItem()
    {
        return self::init()->getTotalItem();
    }

    /**
     * Get the total of item quantity in cart.
     *
     * @return int
     */
    public static function getTotalQuantity()
    {
        return self::init()->getTotalQuantity();
    }

    /**
     * Get the total quantity of an item in cart.
     *
     * @return int
     */
    public static function getItemQuantity($id)
    {
        return self::init()->getItemQuantity($id);
    }

    /**
     * Get the total price of an item in cart.
     *
     * @return int
     */
    public static function getItemPrice($id)
    {
        return self::init()->getItemPrice($id);
    }

    /**
     * Get the sum of a attribute from cart.
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
     * Check if a item exist in cart.
     *
     * @param string $id
     * @param array $attributes
     *
     * @return bool
     */
    public static function isItemExists($id, $attributes = [])
    {
        return self::init()->isItemExists($id, $attributes);
    }

    /**
     * Get one item from cart
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
     * Add item to cart.
     *
     * @param string $id
     * @param int $quantity
     * @param array $attributes
     *
     * @return bool
     */
    public static function add(int $id, $quantity, array $attributes = [])
    {
        if (!empty($attributes['price'])) {
            $attributes['price'] = floatval($attributes['price']);
        }
        return self::init()->add($id, $quantity, $attributes);
    }

    /**
     * Update item quantity.
     *
     * @param string $id
     * @param int $quantity
     * @param array $attributes
     *
     * @return bool
     */
    public static function update($id, int $quantity = 1, $attributes = [])
    {
        return self::init()->update($id, $quantity, $attributes);
    }

    /**
     * Remove item from cart.
     *
     * @param string $id
     * @param array $attributes
     *
     * @return bool
     */
    public static function remove($id, $attributes = [])
    {
        return self::init()->remove($id, $attributes);
    }

    /**
     * Remove all items from cart.
     */
    public static function clear()
    {
        return self::init()->clear();
    }

    /**
     * Destroy cart session.
     */
    public static function destroy()
    {
        return self::init()->destroy();
    }
}
