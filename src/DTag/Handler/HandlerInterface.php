<?php

namespace DTag\Handler;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DTag\Model\ModelInterface;

/**
 * This is the base API for all handlers in the system.
 * It includes the common rest methods for managing a resource
 */
interface HandlerInterface
{

    /**
     * @param EventDispatcherInterface $dispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher);


    /**
     * @param string $handlerEvent
     * @return $this
     */
    public function setEvent($handlerEvent);

    /**
     * @return string return handlerEvent (string)
     */
    public function getHandlerEvent();

    /**
     * Should take an object instance or string class name
     * Should return true if the supplied entity object or class is supported by this handler
     *
     * @param ModelInterface|string $entity
     * @return bool
     */
    public function supportsEntity($entity);

    /**
     * Get a Entity.
     *
     * @param int $id
     *
     * @return ModelInterface
     */
    public function get($id);

    /**
     * Delete an Entity.
     *
     * @param ModelInterface $entity
     *
     * @return void
     */
    public function delete(ModelInterface $entity);

    /**
     * Get a list of Entities.
     *
     * @param int|null $limit the limit of the result
     * @param int|null $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = null, $offset = null);

    /**
     * Create a new Entity.
     *
     * @param array $parameters
     *
     * @return ModelInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Entity.
     *
     * @param ModelInterface $entity
     * @param array $parameters
     *
     * @return ModelInterface
     */
    public function put(ModelInterface $entity, array $parameters);

    /**
     * Partially update a Entity.
     *
     * @param ModelInterface $entity
     * @param array $parameters
     *
     * @return ModelInterface
     */
    public function patch(ModelInterface $entity, array $parameters);

    /**
     * @param Event $event
     */
    public function dispatchEvent($event);
}