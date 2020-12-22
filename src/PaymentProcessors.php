<?php

namespace Tepuilabs\PaymentProcessors;

class PaymentProcessors
{
    /**
     * resolveService
     * @todo hacer que funcione con psalm #YOLO
     */
    public function resolveService($name)
    {
        $service = config("payment-processors.{$name}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception('The selected payment platform is not in the configuration');
    }
}
