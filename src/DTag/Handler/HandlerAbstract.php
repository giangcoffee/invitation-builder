<?php

namespace DTag\Handler;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use DTag\Bundles\AdminApiBundle\Event\HandlerEventLog;
use DTag\DomainManager\ManagerInterface as DummyManagerInterface;
use DTag\Exception\InvalidArgumentException;
use DTag\Exception\InvalidFormException;
use DTag\Exception\LogicException;
use DTag\Model\ModelInterface;

abstract class HandlerAbstract implements HandlerInterface
{
    protected $formFactory;

    protected $formType;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var DummyManagerInterface
     *
     * We are using the dummy class above only for IDE completion.
     *
     * We had to do this because PHP does not support generics and we wanted to type hint
     * our domain managers/models.
     *
     * At a minimum it should support the methods in the dummy class above.
     *
     * The existence of the required methods will be checked in setDomainManager of this class
     */
    protected $domainManager;

    /**
     * Dispatched on user action such as add, remove, delete items
     * @var string
     */
    protected $handlerEvent;

    public function __construct(FormFactoryInterface $formFactory, FormTypeInterface $formType, $domainManager)
    {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->setDomainManager($domainManager);
    }

    /**
     * @inheritdoc
     */
    public function supportsEntity($entity)
    {
        return $this->domainManager->supportsEntity($entity);
    }

    /**
     * @inheritdoc
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setEvent($handlerEvent)
    {
        $this->handlerEvent = $handlerEvent;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getHandlerEvent()
    {
        return $this->handlerEvent;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        return $this->domainManager->find($id);
    }

    /**
     * @inheritdoc
     */
    public function delete(ModelInterface $entity)
    {
        $this->domainManager->delete($entity);

        // now dispatch a HandlerEventLog for handling event, for example ActionLog handler...
        if ($this->eventDispatcher !== null && $this->handlerEvent != null) {
            $event = new HandlerEventLog('DELETE', $entity);
            $this->dispatchEvent($event);
        }
    }

    /**
     * @inheritdoc
     */
    public function all($limit = null, $offset = null)
    {
        return $this->domainManager->all($limit, $offset);
    }

    /**
     * @inheritdoc
     */
    public function post(array $parameters)
    {
        $entity = $this->domainManager->createNew();

        return $this->processForm($entity, $parameters, 'POST');
    }

    /**
     * @inheritdoc
     */
    public function put(ModelInterface $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * @inheritdoc
     */
    public function patch(ModelInterface $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param ModelInterface $entity
     * @param array $parameters
     * @param String $method
     *
     * @return ModelInterface
     *
     * @throws InvalidFormException
     */
    protected function processForm(ModelInterface $entity, array $parameters, $method = 'PUT')
    {
        if (!$this->supportsEntity($entity)) {
            throw new LogicException(sprintf('%s is not supported by this handler', get_class($entity)));
        }

        $formOptions = [
            'method' => $method,
        ];

        // backup entity as oldEntity before submit form
        $oldEntity = clone $entity;

        $form = $this->formFactory->create($this->getFormType(), $entity, $formOptions);

        $formConfig = $form->getConfig();

        if (!is_a($entity, $formConfig->getDataClass())) {
            throw new LogicException(sprintf('Form data class does not match entity returned from domain manager'));
        }

        // on any request except for PATCH, set any missing fields to null if they are missing from the request
        // this means that in a PUT request, if fields are missing, they are set to null overwriting the old values!
        $initializeMissingFields = 'PATCH' !== $method;

        $form->submit($parameters, $initializeMissingFields);

        if ($form->isValid()) {
            $entity = $form->getData();

            $this->domainManager->save($entity);

            // now dispatch a HandlerEventLog for handling event, for example ActionLog handler...
            if ($this->eventDispatcher !== null && $this->handlerEvent != null) {
                $event = new HandlerEventLog($method, $oldEntity, $entity);
                $this->dispatchEvent($event);
            }

            return $entity;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * @return FormTypeInterface
     */
    protected function getFormType()
    {
        return $this->formType;
    }

    /**
     * Returns the domain manager object
     * The return type doc is usually overwritten in sub classes for IDE completion
     * PHP's lack of generics support requires this, see comments at top of class
     *
     * @return DummyManagerInterface
     */
    protected function getDomainManager()
    {
        return $this->domainManager;
    }

    private function setDomainManager($domainManager)
    {
        if (!is_object($domainManager)) {
            throw new InvalidArgumentException('domainManager should be an object instance');
        }

        $getMethods = function ($class) {
            // an array of objects of type ReflectionMethod
            $methods = (new ReflectionClass($class))->getMethods();

            // filter the ReflectionMethod to just the method name string
            $callback = function (ReflectionMethod $method) {
                return $method->name;
            };

            return array_map($callback, $methods);
        };

        // get all of the interface methods from the manager interface
        // doing this because PHP doesn't support generics
        $requiredMethods = $getMethods(DummyManagerInterface::class);

        // check the methods from the current domain manager object
        $domainManagerMethods = $getMethods($domainManager);

        $missingMethods = array_diff($requiredMethods, $domainManagerMethods);

        if (count($missingMethods) > 0) {
            throw new InvalidArgumentException(sprintf('domainManager is missing required methods: %s', join(',', $missingMethods)));
        }

        $this->domainManager = $domainManager;
    }

    /**
     * @inheritdoc
     */
    public function dispatchEvent($event)
    {
        $this->eventDispatcher->dispatch($this->handlerEvent, $event);
    }
}