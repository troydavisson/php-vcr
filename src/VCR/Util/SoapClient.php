<?php

namespace VCR\Util;

use VCR\LibraryHooks\Soap;
use VCR\VCRException;
use VCR\VCRFactory;

/**
 * SoapClient replaces PHPs \SoapClient to allow interception.
 */
class SoapClient extends \SoapClient
{
    /**
     * @var \VCR\LibraryHooks\Soap; SOAP library hook used to intercept SOAP requests.
     */
    protected $soapHook;

    /**
     * Performs (and may intercepts) SOAP request over HTTP.
     *
     * Requests will be intercepted if the library hook is enabled.
     *
     * @param  string  $request  The XML SOAP request.
     * @param  string  $location The URL to request.
     * @param  string  $action   The SOAP action.
     * @param  integer $version  The SOAP version.
     * @param  integer $one_way  If one_way is set to 1, this method returns nothing.
     *                           Use this where a response is not expected.
     * @return string The XML SOAP response.
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $soapHook = $this->getLibraryHook();

        if ($soapHook->isEnabled()) {
            $response = $soapHook->doRequest($request, $location, $action, $version, $one_way);
        } else {
            $response = $this->realDoRequest($request, $location, $action, $version, $one_way);
        }

        return $one_way ? null : $response;
    }

    /**
     * Sets the SOAP library hook which is used to intercept SOAP requests.
     *
     * @param Soap $hook SOAP library hook to use when intercepting SOAP requests.
     */
    public function setLibraryHook(Soap $hook)
    {
        $this->soapHook = $hook;
    }

    /**
     * Performs a real SOAP request over HTTP.
     *
     * @codeCoverageIgnore
     * @param  string  $request  The XML SOAP request.
     * @param  string  $location The URL to request.
     * @param  string  $action   The SOAP action.
     * @param  integer $version  The SOAP version.
     * @param  integer $one_way  If one_way is set to 1, this method returns nothing.
     *                           Use this where a response is not expected.
     * @return string The XML SOAP response.
     */
    protected function realDoRequest($request, $location, $action, $version, $one_way = 0)
    {
        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    /**
     * Returns currently used SOAP library hook.
     *
     * If no library hook is set, a new one is created.
     *
     * @return Soap SOAP library hook.
     */
    protected function getLibraryHook()
    {
        if (empty($this->soapHook)) {
            $this->soapHook = VCRFactory::get('VCR\LibraryHooks\Soap');
        }

        return $this->soapHook;
    }
}