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

        if($this->getEmailAddress())
            $body['payer']['email_address'] = $this->getEmailAddress();
            
        if($this->getSurname()&&$this->getGivenName())
            $body['payer']['name'] = [
                'given_name' => $this->getGivenName(),
                'surname' => $this->getSurname(),
            ];
        
        if($this->getPhoneNumber())
            $body['payer']['phone'] = [
                'phone_number' => [
                    'national_number' => $this->getPhoneNumber(),
                ],
            ];
        
        if($this->getAddress()&&$this->getCity()&&$this->getPostcode()&&$this->getCountryCode())
            $body['payer']['address'] = [
                'address_line_1' => $this->getAddress(),
                'admin_area_2' => $this->getCity(),
                'postal_code' => $this->getPostcode(),
                'country_code' => $this->getCountryCode(),
            ];

        return $body;
    }

    /**
     * Get the email
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->getParameter('email_address');
    }

    /**
     * Set the email address
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setEmailAddress($value)
    {
        return $this->setParameter('email_address', $value);
    }

    /**
     * Get the given name
     *
     * @return string
     */
    public function getGivenName()
    {
        return $this->getParameter('given_name');
    }

    /**
     * Set the given name
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setGivenName($value)
    {
        return $this->setParameter('given_name', $value);
    }

    /**
     * Get the surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->getParameter('surname');
    }

    /**
     * Set the surname
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setSurname($value)
    {
        return $this->setParameter('surname', $value);
    }

    /**
     * Get the phone number
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->getParameter('phone_number');
    }

    /**
     * Set the phone number
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setPhoneNumber($value)
    {
        return $this->setParameter('phone_number', $value);
    }

    /**
     * Get the address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getParameter('address');
    }

    /**
     * Set the address
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setAddress($value)
    {
        return $this->setParameter('address', $value);
    }

    /**
     * Get the postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('postcode');
    }

    /**
     * Set the postcode
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setPostcode($value)
    {
        return $this->setParameter('postcode', $value);
    }

    /**
     * Get the city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getParameter('city');
    }

    /**
     * Set the city
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setCity($value)
    {
        return $this->setParameter('city', $value);
    }

    /**
     * Get the country code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getParameter('country_code');
    }

    /**
     * Set the country code
     *
     * @param string $value
     * @return RestCreateOrderRequest provides a fluent interface.
     */
    public function setCountryCode($value)
    {
        return $this->setParameter('country_code', $value);
    }

    protected function createResponse($data, $statusCode): RestCreateOrderResponse {
        return $this->response = new RestCreateOrderResponse($this, $data, $statusCode);
    }
}
