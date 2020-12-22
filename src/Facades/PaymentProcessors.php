<?php

namespace Tepuilabs\PaymentProcessors\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tepuilabs\PaymentProcessors\PaymentProcessors
 */
class PaymentProcessors extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Tepuilabs\PaymentProcessors\PaymentProcessors::class;
    }
}
