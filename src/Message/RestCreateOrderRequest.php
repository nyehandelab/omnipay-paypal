<?php

namespace Nyehandel\Omnipay\Paypal\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Item;
use Omnipay\PayPal\Message\RestAuthorizeRequest;

class RestCreateOrderRequest extends RestAuthorizeRequest {
    const API_VERSION = 'v2';

    public function getEndpoint(): string {
        $base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        $base .= '/' . self::API_VERSION;
        return $base . '/checkout/orders';
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array {
        $body = [];
        $body['intent'] = "CAPTURE";
        $body['application_context'] = [
            "return_url" => $this->getReturnUrl(),
            "cancel_url" => $this->getCancelUrl(),
        ];
        $body['purchase_units'] = [];
        $body['purchase_units'][] = [
            "amount" => [
                "currency_code" => $this->getCurrency(),
                "value" => $this->getAmount(),
            ]
        ];
        if(!empty($this->getItems())) {
            $body['purchase_units'][0]["amount"]["breakdown"] = [
                "item_total" => [
                    "value" => $this->getAmount(),
                    "currency_code" => $this->getCurrency()
                ]
            ];

            $body['purchase_units'][0]['items'] = [];

            /** @var Item $item */
            foreach ($this->getItems() as $item) {
                $body['purchase_units'][0]['items'][] = [
                    "name" => $item->getName(),
                    "description" => $item->getDescription(),
                    "quantity" => $item->getQuantity(),
                    "unit_amount" => [
                        "value" => $item->getPrice(),
                        "currency_code" => $this->getCurrency()
                    ]
                ];
            }
        }
        return $body;
    }

    protected function createResponse($data, $statusCode): RestCreateOrderResponse {
        return $this->response = new RestCreateOrderResponse($this, $data, $statusCode);
    }
}
