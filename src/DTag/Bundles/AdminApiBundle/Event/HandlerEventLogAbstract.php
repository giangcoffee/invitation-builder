<?php

namespace DTag\Bundles\AdminApiBundle\Event;

use DateTime;
use DTag\Bundles\UserBundle\Event\LogEventAbstract;

abstract class HandlerEventLogAbstract extends LogEventAbstract
{
    const ENTITY_CLASSNAME = 'className';
    const ENTITY_ID = 'id';
    const ENTITY_NAME = 'name';
    const AFFECTEDENTITY_CLASSNAME = 'className';
    const AFFECTEDENTITY_ID = 'id';
    const AFFECTEDENTITY_NAME = 'name';
    const CHANGEDFIELD_NAME = 'name';
    const CHANGEDFIELD_OLDVAL = 'oldVal';
    const CHANGEDFIELD_NEWVAL = 'newVal';
    const CHANGEDFIELD_STARTDATE = 'startDate';
    const CHANGEDFIELD_ENDDATE = 'endDate';

    protected $entityClassName;
    protected $entityId;
    protected $entityName;
    protected $affectedEntities = [];
    protected $changedFields = [];

    /**
     * @inheritdoc
     * The template of data is:
     * $data = [
     *     'action' => $this->action,
     *     'entity' => [
     *         'className' => '',
     *         'id' => '',
     *         'name' => ''
     *     ],
     *     'affectedEntities' => [
     *         [
     *             'className' => '',
     *             'id' => '',
     *             'name' => ''
     *         ]
     *     ],
     *     'changedFields' => [
     *         [
     *             'name' => '',
     *             'oldVal' => '',
     *             'newVal' => '',
     *             'startDate' => '',
     *             'endDate' => ''
     *         ]
     *     ]
     * ];
     */
    public function getData()
    {
        $data = [
            'action' => $this->action,
            'entity' => [
                self::ENTITY_CLASSNAME => $this->entityClassName,
                self::ENTITY_ID => $this->entityId,
                self::ENTITY_NAME => $this->entityName
            ],
            'affectedEntities' => $this->affectedEntities,
            'changedFields' => $this->changedFields
        ];

        return $data;
    }

    /**
     * set entity => className
     *
     * @param string $entityClassName
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;
    }

    /**
     * set entity => id
     *
     * @param int $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * set entity => name
     *
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * set ChangedFields, will override current changedFields
     *
     * 'changedFields' => [
     *     [
     *          'name' => '',
     *          'oldVal' => '',
     *          'newVal' => '',
     *          'startDate' => '',
     *          'endDate' => ''
     *     ]
     * ]
     *
     * @param array $changedFields
     */
    public function setChangedFields(array $changedFields)
    {
        $this->changedFields = array_map(
            function (array $field) {
                $myField = array();
                foreach ($field as $fieldName => $fieldVal) {
                    $myField[$fieldName] = $this->getStringMessage($fieldVal);
                }

                return $myField;
            },
            $changedFields
        );
    }

    /**
     * add an AffectedEntity
     *
     * @param string $entityClassName
     * @param int $entityId
     * @param string $entityName
     */
    public function addAffectedEntity($entityClassName, $entityId, $entityName)
    {
        $affectedEntity = [
            self::AFFECTEDENTITY_CLASSNAME => $entityClassName,
            self::AFFECTEDENTITY_ID => $entityId,
            self::AFFECTEDENTITY_NAME => $entityName
        ];
        $this->affectedEntities[] = $affectedEntity;
    }

    /**
     * add ChangedFields
     *
     * @param string $name
     * @param mixed $oldVal
     * @param mixed $newVal
     * @param DateTime $startDate
     * @param DateTime $endDate
     */
    public function addChangedFields($name, $oldVal, $newVal, $startDate = null, $endDate = null)
    {


        $changedField = [
            self::CHANGEDFIELD_NAME => $name,
            self::CHANGEDFIELD_OLDVAL => $this->getStringMessage($oldVal),
            self::CHANGEDFIELD_NEWVAL => $this->getStringMessage($newVal),
            self::CHANGEDFIELD_STARTDATE => $startDate,
            self::CHANGEDFIELD_ENDDATE => $endDate
        ];
        $this->changedFields[] = $changedField;
    }

    protected function getStringMessage($object) {
        if (null === $object || is_string($object) || is_numeric($object) ) {
            return $object;
        }

        if ($object instanceof DateTime) {
            return $object->format('Y-m-d h:i:s');
        }

        if (is_bool($object)) {
            return $object ? "true" : "false";
        }

        return serialize($object);
    }
}
