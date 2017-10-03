<?php

namespace DTag\Model\User;

use DTag\Model\ModelInterface;

interface UserEntityInterface extends ModelInterface
{
    public function getId();

    public function getUsername();

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * @return []
     */
    public function getRoles();

    public function hasRole($role);

    /**
     * Adds a role to the user.
     *
     * @param string $role
     *
     * @return self
     */
    public function addRole($role);

    /**
     * @param array $modules
     * @return void
     */
    public function setEnabledModules(array $modules);

    /**
     * @param array $roles
     * @return void
     */
    public function setUserRoles(array $roles);

    /**
     * @return array
     */
    public function getEnabledModules();

    /**
     * @return bool;
     */
    public function hasDisplayModule();

    /**
     * @return bool
     */
    public function hasAnalyticsModule();

    /**
     * @return bool
     */
    public function hasVideoModule();

    /**
     * @return array
     */
    public function getUserRoles();

    public function getType();

    public function setType($type);
}