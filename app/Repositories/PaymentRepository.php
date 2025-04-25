<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository extends ResourceRepository
{

    public function __construct(Payment $payment)
    {
        $this->model = $payment;
    }
}
