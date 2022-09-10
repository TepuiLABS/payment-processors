<?php

use function Pest\Laravel\artisan;

it('can publish config file', function (): void {
    artisan('vendor:publish --tag=payment-processors-config')
        ->assertExitCode(0);

    expect(file_exists(config_path('payment-processors.php')))
        ->toBeTrue()
        ->and(unlink(config_path('payment-processors.php')))
        ->toBe(true);
});
