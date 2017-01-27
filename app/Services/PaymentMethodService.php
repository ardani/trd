<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/21/17
 * Time: 10:54 PM
 */

namespace App\Services;

use App\Models\PaymentMethod;
use Entrust;

class PaymentMethodService extends Service {

    protected $model;
    protected $name = 'payment_methods';

    public function __construct(PaymentMethod $model) {
        $this->model = $model;
    }
}