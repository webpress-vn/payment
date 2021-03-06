<?php

namespace VCComponent\Laravel\Payment\Contracts;

use VCComponent\Laravel\Payment\Entities\PaymentLog;

class PaymentResponse
{
    public function excute($response)
    {
        if (is_array($response)) {
            $data = $response;
        } else {
            $data = $response->toArray();
        }

        $cart_id  = $this->getCartId($data);
        $messages = $this->getAlert($data);

        $data_response = array_merge($cart_id, $messages);

        $payment_response = PaymentLog::create($data_response);

        $repo_noti = config('payment.vnpay.vnp_ReturnUrl') . 'payment-response';

        if (config('payment.url_response') !== '') {
            $repo_noti = config('payment.url_response');
        }

        return redirect($repo_noti)->with('payment_response', $payment_response);
    }

    public function getCartId($data)
    {
        return ['cart_id' => $data['cart_id']];
    }

    public function getAlert($data)
    {
        $status = [
            'status_code' => $data['messages']['status'],
            'message'     => $data['messages']['notifications'],
        ];
        return $status;
    }
}
