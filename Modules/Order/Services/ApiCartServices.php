<?php

namespace Modules\Order\Services;

use Illuminate\Support\Collection;
use RuntimeException;

class ApiCartServices
{
    private function prepareRequestDataForCartContent($request): Collection
    {
        return collect(json_decode(json_encode($request['cart_items'])));
    }

    public function __call(string $name, array $arguments)
    {
        if(method_exists($this, $name)){
            call_user_func_array([$this,$name], $arguments);
        }
    }

    public static function __callStatic($method, $args)
    {
        $instance = new static();

        if (!method_exists($instance, $method)) {
            throw new RuntimeException('This method is not defined.');
        }

        return call_user_func_array([$instance, $method], $args);
    }
}