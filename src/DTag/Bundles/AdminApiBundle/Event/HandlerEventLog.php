<?php

namespace DTag\Bundles\AdminApiBundle\Event;

use ReflectionClass;
use DTag\Model\ModelInterface;
use DTag\Model\User\Role\AdminInterface;
use DTag\Model\User\Role\BrokerInterface;
use DTag\Model\User\UserEntityInterface;

class HandlerEventLog extends HandlerEventLogAbstract implements HandlerEventLogInterface
{
    protected $action;
    protected $oldEntity;
    protected $newEntity;

    /**
     * @param string $httpMethod method GET/PUT/POST/PATCH/DELETE
     * @param ModelInterface $oldEntity the old entity before changing
     * @param ModelInterface $newEntity the new entity after changing. $oldEntity & $newEntity are used for tracking all changed fields.
     * If already known changing reasons, set $newEntity = null &  $changedFields = your-known-changing-reasons
     */
    public function __construct($httpMethod, ModelInterface $oldEntity, ModelInterface $newEntity = null)
    {
        parent::__construct($httpMethod);

        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        //set className
        $this->setEntityClassName($this->getClassNameForEntity($this->oldEntity));

        //set id
        $this->setEntityId($this->getOldEntity()->getId());

        //set name
        $name = $this->getNameForEntity($this->oldEntity);
        if(null === $name || 1 > sizeof($name)){
            //in case of create-delete entity: oldEntity has had no value yet
            $name = $this->getNameForEntity($this->newEntity);
        }
        $this->setEntityName($name);

        return parent::getData();
    }

    /**
     * @inheritdoc
     */
    public function getOldEntity()
    {
        return $this->oldEntity;
    }

    /**
     * @inheritdoc
     */
    public function getNewEntity()
    {
        return $this->newEntity;
    }

    /**
     * get ClassName For Entity
     *
     * @param object $entity
     * @return string className className of $entity, if not object => return null
     */
    public function getClassNameForEntity($entity)
    {
        if (!is_object($entity)) {
            return null;
        }

        //detect manually because multi user
        if ($entity instanceof BrokerInterface) {
            return 'Broker';
        } elseif ($entity instanceof AdminInterface) {
            return 'Admin';
        } else {
            return (new ReflectionClass($entity))->getShortName();
        }
    }

    /**
     * get name of Entity. If User => return getUserName(), else return getName(), etc...
     *
     * @param object $entity
     * @return null|string name of entity
     */
    public function getNameForEntity($entity)
    {
        if(null === $entity || !is_object($entity)){
            return null;
        }

        if ($entity instanceof UserEntityInterface) {
            return $entity->getUsername();
        }

        if ($entity instanceof ModelInterface) {
            //if using $entity's Id instead of $entity's Name
            return $entity->getId();
        }

        //else: unknown entity's name
        return null;
    }

    /**
     * add an AffectedEntity by entity
     *
     * @param ModelInterface $entity
     */
    public function addAffectedEntityByObject($entity)
    {
        $affectedEntities = [
            self::AFFECTEDENTITY_CLASSNAME => $this->getClassNameForEntity($entity),
            self::AFFECTEDENTITY_ID => $entity->getId(),
            self::AFFECTEDENTITY_NAME => $this->getNameForEntity($entity)
        ];
        $this->affectedEntities[] = $affectedEntities;
    }
}
