<?php

namespace Tepuilabs\PaymentProcessors;

class PaymentProcessors
{
    /**
     * Undocumented function
     *
     * @param  string  $name
     * @param  array  $params
     * @return mixed
     *
     * @throws \Exception
     *
     * @todo hacer que funcione con psalm #YOLO
     *
     * @psalm-suppress MissingReturnType
     * @psalm-suppress PossiblyInvalidCast
     * @psalm-suppress PossiblyInvalidArgument
     * @psalm-suppress UndefinedMethod
     */
    public function resolveService(string $name, array $params): mixed
    {
        $service = config("payment-processors.{$name}.class");

        if ($service) {
            return $service::paymentService($params);
        }

        throw new \Exception('The selected payment platform is not in the configuration');
    }
}
