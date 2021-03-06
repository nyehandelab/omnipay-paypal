<?php

namespace Nyehandel\Omnipay\Paypal\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\PayPal\Message\RestAuthorizeRequest as RestV1AuthorizeRequest ;

class RestAuthorizeRequest extends RestV1AuthorizeRequest {
    const API_VERSION = 'v2';

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data): PayPalV2RestResponse {
        $original = parent::sendData($data);
        return new PayPalV2RestResponse($original->getRequest(), $original->getData(), $original->getCode());
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return array
     * @throws InvalidRequestException
     */
    public function getData() {
        $this->validate('transactionReference', 'payerId');
        return ['payer_id' => $this->getPayerId()];
    }

    public function getPayerId() {
        return $this->getParameter('payerId');
    }

    public function getEndpoint(): string {
        $base = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        $base .= '/' . self::API_VERSION;
        return $base . '/checkout/orders/' . $this->getTransactionReference() . '/authorize';
    }
}
