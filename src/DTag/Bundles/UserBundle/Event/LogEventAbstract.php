<?php

namespace DTag\Bundles\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DTag\Exception\InvalidArgumentException;

abstract class LogEventAbstract extends Event implements LogEventInterface
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_PATCH = 'PATCH';
    const HTTP_DELETE = 'DELETE';

    const CREATE_OR_UPDATE = 'CREATE/UPDATE';
    const UPDATE = 'UPDATE';
    const DELETE = 'DELETE';

    protected $allowedHttpMethods = [
        self::HTTP_POST,
        self::HTTP_PUT,
        self::HTTP_PATCH,
        self::HTTP_DELETE,
    ];

    /**
     * Maps http methods to user actions such as 'adding' or 'deleting'
     *
     * @var array
     */
    protected $actionMap = [
        self::HTTP_POST => self::CREATE_OR_UPDATE,
        self::HTTP_PUT => self::CREATE_OR_UPDATE,
        self::HTTP_PATCH => self::UPDATE,
        self::HTTP_DELETE => self::DELETE,
    ];

    /** @var  string $action the action related event, such as GET, POST, PUT, PATCH, DELETE */
    protected $action;

    /**
     * @param $httpMethod
     */
    public function __construct($httpMethod)
    {
        if (!in_array($httpMethod, $this->allowedHttpMethods)) {
            throw new InvalidArgumentException('that method is not defined');
        }

        if (!array_key_exists($httpMethod, $this->actionMap)) {
            throw new InvalidArgumentException('that method is not supported');
        }

        $this->action = $this->actionMap[$httpMethod];
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
    abstract public function getData();
}
