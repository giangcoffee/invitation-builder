<?php

namespace DTag\Model\User\Role;

interface ModeratorInterface extends UserRoleInterface
{
    public function getFirstName();

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return mixed
     */
    public function getLastName();

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName);

    /**
     * @return mixed
     */
    public function getCompany();

    /**
     * @param mixed $company
     */
    public function setCompany($company);

    /**
     * @return mixed
     */
    public function getPhone();

    /**
     * @param mixed $phone
     */
    public function setPhone($phone);

    /**
     * @return mixed
     */
    public function getCity();

    /**
     * @param mixed $city
     */
    public function setCity($city);

    /**
     * @return mixed
     */
    public function getState();

    /**
     * @param mixed $state
     */
    public function setState($state);

    /**
     * @return mixed
     */
    public function getAddress();

    /**
     * @param mixed $address
     */
    public function setAddress($address);

    /**
     * @return mixed
     */
    public function getPostalCode();

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode);

    /**
     * @return mixed
     */
    public function getCountry();

    /**
     * @param mixed $country
     */
    public function setCountry($country);

    /**
     * @return bool $enableSourceReport
     */
    public function getEnabledModules();

    /**
     * @return bool
     */
    public function hasAnalyticsModule();

    public function getEmail();

    /**
     * @return mixed
     */
    public function getSettings();

    /**
     * @param mixed $settings
     */
    public function setSettings($settings);
}