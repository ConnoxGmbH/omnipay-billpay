<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Message\RequestData\CancelTrait;
use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

/**
 * Message RefundRequest
 * Example xml:
 * <code>
 * <?xml version="1.0" encoding="UTF-8"?>
 * <data tcaccepted="1" expecteddaystillshipping="0" capturerequestnecessary="0" paymenttype="1" api_version="1.5.11">
 *   <default_params mid="4441" pid="6021" bpsecure="25d55ad283aa400af464c76d713c07ad"/>
 *   <cancel_params carttotalgross="3390" currency="EUR" reference="Testbestellung123"/>
 * </data>
 * </code>
 *
 * @link      https://techdocs.billpay.de/de/An_Entwickler/XML_API/Cancel.html
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
class RefundRequest extends AbstractRequest
{
    use CancelTrait;

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return SimpleXMLElement
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $this->appendCancel($data);

        return $data;
    }

    /**
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    protected function createResponse($response)
    {
        return $this->response = new RefundResponse($this, $response);
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/cancel';
    }
}
