<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{

    protected $secretKey;
    protected $publicKey;

    public function __construct(string $secretKey, string $publicKey)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPublicKey() : string {
        return $this->publicKey;
    }


    public function getPaymentIntent(Purchase $purchase)
    {
        // \Stripe\Stripe::setApiKey('sk_test_51IE8KAI8TVU6byzqd2pLm8F99wK7d0wUhjpfQCBfsP3Ci3EMvmlqSd1qL762QITnpyi4OUlsQyXeOkxdsimWM0a000rxEgjSbD');
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}
