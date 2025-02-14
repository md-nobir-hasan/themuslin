<?php

namespace Modules\TaxModule\Base;

use Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress;

trait StaticInstance
{
    private $instance = null;

    public static function init()
    {
        $self = new self();

        if(!is_null($self->instance)){
            return $self->instance;
        }

        return $self;
    }
}