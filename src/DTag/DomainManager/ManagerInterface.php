<?php

namespace DTag\DomainManager;

/**
 * A dummy class since PHP does not support generics
 */

use DTag\Model\ModelInterface;

interface ManagerInterface
{
    /**
     * Should take an object instance or string class name
     * Should return true if the supplied entity object or class is supported by this manager
     *
     * @param ModelInterface|string $entity
     * @return bool
     */
    public function supportsEntity($entity);

    /**
     * @param ModelInterface $entity
     * @return void
     */
    public function save(ModelInterface $entity);

    /**
     * @param ModelInterface $entity
     * @return void
     */
    public function delete(ModelInterface $entity);

    /**
     * @return ModelInterface
     */
    public function createNew();

    /**
     * @param int $id
     * @return ModelInterface|null
     */
    public function find($id);

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return ModelInterface[]
     */
    public function all($limit = null, $offset = null);
}