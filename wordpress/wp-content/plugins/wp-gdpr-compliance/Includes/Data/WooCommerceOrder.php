<?php

namespace WPGDPRC\Includes\Data;

/**
 * Class WooCommerceOrder
 * @package WPGDPRC\Includes\Data
 */
class WooCommerceOrder {
    /** @var null */
    private static $instance = null;
    /** @var int */
    protected $orderId = 0;
    /** @var string */
    protected $billingEmailAddress = '';
    /** @var string */
    protected $billingFirstName = '';
    /** @var string */
    protected $billingLastName = '';
    /** @var string */
    protected $billingCompany = '';
    /** @var string */
    protected $billingAddressOne = '';
    /** @var string */
    protected $billingAddressTwo = '';
    /** @var string */
    protected $billingCity = '';
    /** @var string */
    protected $billingState = '';
    /** @var string */
    protected $billingPostCode = '';
    /** @var string */
    protected $billingCountry = '';
    /** @var string */
    protected $billingPhone = '';
    /** @var string */
    protected $shippingFirstName = '';
    /** @var string */
    protected $shippingLastName = '';
    /** @var string */
    protected $shippingCompany = '';
    /** @var string */
    protected $shippingAddressOne = '';
    /** @var string */
    protected $shippingAddressTwo = '';
    /** @var string */
    protected $shippingCity = '';
    /** @var string */
    protected $shippingState = '';
    /** @var string */
    protected $shippingPostCode = '';
    /** @var string */
    protected $shippingCountry = '';

    /**
     * User constructor.
     * @param int $orderId
     */
    public function __construct($orderId = 0) {
        if ((int)$orderId > 0) {
            $this->setOrderId($orderId);
            $this->load();
        }
    }

    public function load() {
        $this->setBillingEmailAddress(get_post_meta($this->getOrderId(), '_billing_email', true));
        $this->setBillingFirstName(get_post_meta($this->getOrderId(), '_billing_first_name', true));
        $this->setBillingLastName(get_post_meta($this->getOrderId(), '_billing_last_name', true));
        $this->setBillingCompany(get_post_meta($this->getOrderId(), '_billing_company', true));
        $this->setBillingAddressOne(get_post_meta($this->getOrderId(), '_billing_address_1', true));
        $this->setBillingAddressTwo(get_post_meta($this->getOrderId(), '_billing_address_2', true));
        $this->setBillingCity(get_post_meta($this->getOrderId(), '_billing_city', true));
        $this->setBillingState(get_post_meta($this->getOrderId(), '_billing_state', true));
        $this->setBillingPostCode(get_post_meta($this->getOrderId(), '_billing_postcode', true));
        $this->setBillingCountry(get_post_meta($this->getOrderId(), '_billing_country', true));
        $this->setBillingPhone(get_post_meta($this->getOrderId(), '_billing_phone', true));
        $this->setShippingFirstName(get_post_meta($this->getOrderId(), '_shipping_first_name', true));
        $this->setShippingLastName(get_post_meta($this->getOrderId(), '_shipping_last_name', true));
        $this->setShippingCompany(get_post_meta($this->getOrderId(), '_shipping_company', true));
        $this->setShippingAddressOne(get_post_meta($this->getOrderId(), '_shipping_address_1', true));
        $this->setShippingAddressTwo(get_post_meta($this->getOrderId(), '_shipping_address_2', true));
        $this->setShippingCity(get_post_meta($this->getOrderId(), '_shipping_city', true));
        $this->setShippingState(get_post_meta($this->getOrderId(), '_shipping_state', true));
        $this->setShippingPostCode(get_post_meta($this->getOrderId(), '_shipping_postcode', true));
        $this->setShippingCountry(get_post_meta($this->getOrderId(), '_shipping_country', true));
    }

    /**
     * @return null|WooCommerceOrder
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return int
     */
    public function getOrderId() {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getBillingEmailAddress() {
        return $this->billingEmailAddress;
    }

    /**
     * @param string $billingEmailAddress
     */
    public function setBillingEmailAddress($billingEmailAddress) {
        $this->billingEmailAddress = $billingEmailAddress;
    }

    /**
     * @return string
     */
    public function getBillingFirstName() {
        return $this->billingFirstName;
    }

    /**
     * @param string $billingFirstName
     */
    public function setBillingFirstName($billingFirstName) {
        $this->billingFirstName = $billingFirstName;
    }

    /**
     * @return string
     */
    public function getBillingLastName() {
        return $this->billingLastName;
    }

    /**
     * @param string $billingLastName
     */
    public function setBillingLastName($billingLastName) {
        $this->billingLastName = $billingLastName;
    }

    /**
     * @return string
     */
    public function getBillingCompany() {
        return $this->billingCompany;
    }

    /**
     * @param string $billingCompany
     */
    public function setBillingCompany($billingCompany) {
        $this->billingCompany = $billingCompany;
    }

    /**
     * @return string
     */
    public function getBillingAddressOne() {
        return $this->billingAddressOne;
    }

    /**
     * @param string $billingAddressOne
     */
    public function setBillingAddressOne($billingAddressOne) {
        $this->billingAddressOne = $billingAddressOne;
    }

    /**
     * @return string
     */
    public function getBillingAddressTwo() {
        return $this->billingAddressTwo;
    }

    /**
     * @param string $billingAddressTwo
     */
    public function setBillingAddressTwo($billingAddressTwo) {
        $this->billingAddressTwo = $billingAddressTwo;
    }

    /**
     * @return string
     */
    public function getBillingCity() {
        return $this->billingCity;
    }

    /**
     * @param string $billingCity
     */
    public function setBillingCity($billingCity) {
        $this->billingCity = $billingCity;
    }

    /**
     * @return string
     */
    public function getBillingState() {
        return $this->billingState;
    }

    /**
     * @param string $billingState
     */
    public function setBillingState($billingState) {
        $this->billingState = $billingState;
    }

    /**
     * @return string
     */
    public function getBillingPostCode() {
        return $this->billingPostCode;
    }

    /**
     * @param string $billingPostCode
     */
    public function setBillingPostCode($billingPostCode) {
        $this->billingPostCode = $billingPostCode;
    }

    /**
     * @return string
     */
    public function getBillingCountry() {
        return $this->billingCountry;
    }

    /**
     * @param string $billingCountry
     */
    public function setBillingCountry($billingCountry) {
        $this->billingCountry = $billingCountry;
    }

    /**
     * @return string
     */
    public function getBillingPhone() {
        return $this->billingPhone;
    }

    /**
     * @param string $billingPhone
     */
    public function setBillingPhone($billingPhone) {
        $this->billingPhone = $billingPhone;
    }

    /**
     * @return string
     */
    public function getShippingFirstName() {
        return $this->shippingFirstName;
    }

    /**
     * @param string $shippingFirstName
     */
    public function setShippingFirstName($shippingFirstName) {
        $this->shippingFirstName = $shippingFirstName;
    }

    /**
     * @return string
     */
    public function getShippingLastName() {
        return $this->shippingLastName;
    }

    /**
     * @param string $shippingLastName
     */
    public function setShippingLastName($shippingLastName) {
        $this->shippingLastName = $shippingLastName;
    }

    /**
     * @return string
     */
    public function getShippingCompany() {
        return $this->shippingCompany;
    }

    /**
     * @param string $shippingCompany
     */
    public function setShippingCompany($shippingCompany) {
        $this->shippingCompany = $shippingCompany;
    }

    /**
     * @return string
     */
    public function getShippingAddressOne() {
        return $this->shippingAddressOne;
    }

    /**
     * @param string $shippingAddressOne
     */
    public function setShippingAddressOne($shippingAddressOne) {
        $this->shippingAddressOne = $shippingAddressOne;
    }

    /**
     * @return string
     */
    public function getShippingAddressTwo() {
        return $this->shippingAddressTwo;
    }

    /**
     * @param string $shippingAddressTwo
     */
    public function setShippingAddressTwo($shippingAddressTwo) {
        $this->shippingAddressTwo = $shippingAddressTwo;
    }

    /**
     * @return string
     */
    public function getShippingCity() {
        return $this->shippingCity;
    }

    /**
     * @param string $shippingCity
     */
    public function setShippingCity($shippingCity) {
        $this->shippingCity = $shippingCity;
    }

    /**
     * @return string
     */
    public function getShippingState() {
        return $this->shippingState;
    }

    /**
     * @param string $shippingState
     */
    public function setShippingState($shippingState) {
        $this->shippingState = $shippingState;
    }

    /**
     * @return string
     */
    public function getShippingPostCode() {
        return $this->shippingPostCode;
    }

    /**
     * @param string $shippingPostCode
     */
    public function setShippingPostCode($shippingPostCode) {
        $this->shippingPostCode = $shippingPostCode;
    }

    /**
     * @return string
     */
    public function getShippingCountry() {
        return $this->shippingCountry;
    }

    /**
     * @param string $shippingCountry
     */
    public function setShippingCountry($shippingCountry) {
        $this->shippingCountry = $shippingCountry;
    }
}