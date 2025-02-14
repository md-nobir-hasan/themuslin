<?php

namespace App\Compare;

class Compare {
    /**
	 * An unique ID for the compare.
	 *
	 * @var string
	 */
	protected $compareId;

    /**
	 * A collection of compare items.
	 *
	 * @var array
	 */
	private $items = [];

    /**
	 * Initialize compare.
	 *
	 * @param array $options
	 */
	public function __construct()
	{
		if (!session_id()) session_start();

		$this->compareId = md5((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'SimpleCompare') . '_compare';

		$this->read();
	}

    /**
	 * Get items in  compare.
	 *
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
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
	public function add($id)
	{
        if (empty($this->items[$id])) {
            $this->items[$id] = $id;
        }

		$this->write();

		return true;
	}

    /**
	 * Remove item from compare.
	 *
	 * @param string $id
	 * @param array  $attributes
	 *
	 * @return bool
	 */
	public function remove($id)
	{
		if (!isset($this->items[$id])) {
			return false;
		}
        unset($this->items[$id]);
        $this->write();
        return true;
	}

    /**
	 * Remove all items from compare.
	 */
	public function clear()
	{
		$this->items = [];
		$this->write();
	}

    /**
	 * Read items from compare session.
	 */
	private function read()
	{
		$this->items = json_decode((isset($_SESSION[$this->compareId])) ? $_SESSION[$this->compareId] : '[]', true);
	}

    /**
	 * Write changes into compare session.
	 */
    private function write()
    {
        $_SESSION[$this->compareId] = json_encode(array_filter($this->items));
    }
}
