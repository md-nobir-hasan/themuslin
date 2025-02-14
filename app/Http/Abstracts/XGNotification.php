<?php

namespace App\Http\Abstracts;

abstract class XGNotification
{
    protected static $instance;
    protected $model = null;
    protected $data = [];
    protected string $type;
    protected ?int $vendor_id = null;
    protected ?int $user_id = null;
    protected ?int $delivery_man_id = null;


    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setDeliveryManId(int $deliveryManId): static
    {
        $this->delivery_man_id = $deliveryManId;

        return $this;
    }


    public function setVendor(int $vendorId): static
    {
        $this->vendor_id = $vendorId;

        return $this;
    }


    public function setUser(string $userId): static
    {
        $this->user_id = $userId;

        return $this;
    }

    public static function init($model = null)
    {
        if(is_null(static::$instance)){
            static::$instance = new static();
        }

        // check model is not null then initialize into model property
        if(!is_null($model))
            (static::$instance)->model = $model;

        return static::$instance;
    }
}