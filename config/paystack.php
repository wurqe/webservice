<?php

declare(strict_types=1);

return [
    /**
     * Paystack WebHook URL
     */
    'webhookUrl' => '/paystack/hook',

    /**
     * Public Key From Paystack Dashboard
     *
     */
    'publicKey' => $publicKey = env('PAYSTACK_PUBLIC_KEY', 'publicKey'),

    /**
     * Secret Key From Paystack Dashboard
     *
     */
    'secretKey' => $secretKey = env('PAYSTACK_SECRET_KEY', 'secretKey'),

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => $paymentUrl = env('PAYSTACK_PAYMENT_URL'),

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => $merchantEmail = env('MERCHANT_EMAIL'),

    /**
     * Default connection that will be used to connect to the API
     */
    'default' => 'test',

    /**
     * Here you can specify different Paystack connection.
     */
    'connections' => [
        'test' => [
            'publicKey'     => $publicKey,
            'secretKey'     => $secretKey,
            'paymentUrl'    => $paymentUrl,
            'cache'         => false,
        ],
        'live' => [
            'publicKey'     => $publicKey,
            'secretKey'     => $secretKey,
            'paymentUrl'    => $paymentUrl,
            'cache'         => false,
        ],
        // You can add More connection as you want
    ],
];
