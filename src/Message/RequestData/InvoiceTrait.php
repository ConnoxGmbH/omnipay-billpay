<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class InvoiceTrait.
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
trait InvoiceTrait
{
    /**
     * Validates and returns the formated amount.
     *
     * @throws InvalidRequestException on any validation failure.
     *
     * @return string The amount formatted to the correct number of decimal places for the selected currency.
     *
     * @codeCoverageIgnore
     */
    abstract public function getAmount();

    /**
     * Get the payment currency code.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    abstract public function getCurrency();

    /**
     * Gets amount of days that will be added to the payment due date (e.g. in case of delayed shipping).
     *
     * @return int Days
     */
    public function getDelayInDays()
    {
        return $this->getParameter('delayInDays') ? : 5;
    }

    /**
     * Get the transaction ID.
     *
     * The transaction ID is the identifier generated by the merchant website.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    abstract public function getTransactionId();

    /**
     * Amount of days that will be added to the payment due date (e.g. in case of delayed shipping).
     *
     * @param int $value
     *
     * @return AuthorizeRequest
     */
    public function setDelayInDays($value)
    {
        return $this->setParameter('delayInDays', $value);
    }

    /**
     * Appends the rate request node to the SimpleXMLElement.
     *
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendInvoice(SimpleXMLElement $data)
    {
        $data->addChild('invoice_params');
        $data->invoice_params[0]['carttotalgross'] = round(bcmul($this->getAmount(), 100, 8));
        $data->invoice_params[0]['currency'] = $this->getCurrency();
        $data->invoice_params[0]['reference'] = $this->getTransactionId();
        $data->invoice_params[0]['delayindays'] = $this->getDelayInDays();
    }

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    abstract protected function getParameter($key);

    /**
     * Set a single parameter.
     *
     * @param string $key   The parameter key
     * @param mixed  $value The value to set
     *
     * @return AbstractRequest Provides a fluent interface
     *
     * @codeCoverageIgnore
     */
    abstract protected function setParameter($key, $value);
}
