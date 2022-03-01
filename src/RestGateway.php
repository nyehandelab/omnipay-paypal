<?php

namespace Nyehandel\Omnipay\Paypal;

use Omnipay\Common\AbstractGateway;
use Omnipay\PayPal\Message\AbstractRestRequest;
use Omnipay\PayPal\RestGateway as RestV1Gateway;

class RestGateway extends RestV1Gateway {

    public function getName(): string {
        return "PayPal Checkouts V2";
    }

    /**
     * @param array $parameters
     * @return AbstractRestRequest
     */
    public function purchase(array $parameters = array()): AbstractRestRequest {
        return $this->createRequest('\Nyehandel\Omnipay\Paypal\Message\RestCreateOrderRequest', $parameters);
    }

    public function authorize(array $parameters = array()): AbstractRestRequest {
        return $this->createRequest('\Nyehandel\Omnipay\Paypal\Message\RestAuthorizeRequest', $parameters);
    }

    public function completePurchase(array $parameters = array()): AbstractRestRequest {
        return $this->createRequest('\Nyehandel\Omnipay\Paypal\Message\RestCaptureRequest', $parameters);
    }

    public function refund(array $parameters = array()): AbstractRestRequest {
        return $this->createRequest('\Nyehandel\Omnipay\Paypal\Message\RestRefundRequest', $parameters);
    }

}
