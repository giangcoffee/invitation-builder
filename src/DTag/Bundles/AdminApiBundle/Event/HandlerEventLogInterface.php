<?php

namespace DTag\Bundles\AdminApiBundle\Event;


use DTag\Bundles\UserBundle\Event\LogEventInterface;
use DTag\Model\ModelInterface;

interface HandlerEventLogInterface extends LogEventInterface
{
    /**
     * get old Entity before changing
     *
     * @return ModelInterface
     */
    public function getOldEntity();

    /**
     * get new Entity after changing
     *
     * @return ModelInterface
     */
    public function getNewEntity();

    /**
     * get name (name value) of entity as User name or Site name or AdTag name or etc...
     * @param ModelInterface $entity
     * @return string
     */
    public function getNameForEntity($entity);

    /**
     * set entity => className
     *
     * @param string $entityClassName
     */
    public function setEntityClassName($entityClassName);

    /**
     * set entity => id
     *
     * @param int $entityId
     */
    public function setEntityId($entityId);

    /**
     * set entity => name
     *
     * @param string $entityName
     */
    public function setEntityName($entityName);

    /**
     * set ChangedFields, will override current changedFields
     *
     * @param array $changedFields
     */
    public function setChangedFields(array $changedFields);

    /**
     * add an AffectedEntity
     *
     * @param string $entityClassName
     * @param int $entityId
     * @param string $entityName
     */
    public function addAffectedEntity($entityClassName, $entityId, $entityName);

    /**
     * add a ChangedField
     *
     * @param string $name
     * @param string $oldVal
     * @param string $newVal
     * @param string $startDate
     * @param string $endDate
     */
    public function addChangedFields($name, $oldVal, $newVal, $startDate = null, $endDate = null);
}