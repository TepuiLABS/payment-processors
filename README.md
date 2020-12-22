<p align="center">
	<img src="payment-processors.png" width="1028">
</p>

# Payment processors

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tepuilabs/payment-processors.svg?style=flat-square)](https://packagist.org/packages/tepuilabs/payment-processors)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/tepuilabs/payment-processors/run-tests?label=tests)](https://github.com/tepuilabs/payment-processors/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/tepuilabs/payment-processors.svg?style=flat-square)](https://packagist.org/packages/tepuilabs/payment-processors)


Paquete para laravel que se encarga de gestionar diferentes pasarelas de pago:

- Mercadopago de Mercadolibre
- PayPal (por implementar)


Esto es idea original de @JuanDMeGon, tomada de su curso en Udemy de [Procesa pagos con Laravel y las mejores plataformas de pagos](https://www.udemy.com/course/procesa-pagos-en-linea-con-laravel-y-pasarelas-de-pagos-paypal-stripe/?referralCode=23F6FEDB611DEF416097).

Muchas gracias Juan por tu trabajo y esfuerzo!!


## Installation

You can install the package via composer:

```bash
composer require tepuilabs/payment-processors
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Tepuilabs\PaymentProcessors\PaymentProcessorsServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    'mercadopago' => [
        'base_uri'      => env('MERCADOPAGO_BASE_URI'),
        'key'           => env('MERCADOPAGO_KEY'),
        'secret'        => env('MERCADOPAGO_SECRET'),
        'base_currency' => env('MERCADOPAGO_BASE_CURRENCY'),
        'class'         => Tepuilabs\PaymentProcessors\Services\MercadoPagoService::class,
    ],
];
```

### como usar

Primero debes seguir las indicaciones de mercado libre para hacer la integración de [cliente](https://www.mercadopago.com.uy/developers/es/guides/online-payments/checkout-api/receiving-payment-by-card/) luego de eso, sigue los pasos abajo descritos:

> NOTA: esta implementación no está pensada para cobros en cuotas


```php
// usa el facade
use Tepuilabs\PaymentProcessors\Facades\PaymentProcessors;

// luego crea la instancia de la clase a usar, en este caso la de mercado libre
// en el futuro se van a realizar otras integraciones (paypal)
$mercadopago = PaymentProcessors::resolveService('mercadopago');

// necesitamos:
// $cardNetwork: visa / mastercard
// $cardToken: token de la tarjeta
// $amount: monto a cobrar
// $userEmail: correo del usuario

$mercadopago->handlePayment('visa', 'ff8080814c11e237014c1ff593b57b4d', 177, 'test@test.com');
```

### respuesta

```yml
{
    "status": "approved",
    "status_detail": "accredited",
    "id": 3055677,
    "date_approved": "2019-02-23T00:01:10.000-04:00",
    "payer": {
        ...
    },
    "payment_method_id": "visa",
    "payment_type_id": "credit_card",
    "refunds": [],
    ...
}
```





## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.


## Credits

- [angel cruz](https://github.com/abr4xas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
