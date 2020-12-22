<?php

namespace Tepuilabs\PaymentProcessors;

class PaymentProcessors
{
    /**
     * Undocumented function
     *
     * @param string $name
     * @todo hacer que funcione con psalm #YOLO
     */
    public function resolveService(string $name)
    {
        $service = config("payment-processors.{$name}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception('The selected payment platform is not in the configuration');
    }
}
