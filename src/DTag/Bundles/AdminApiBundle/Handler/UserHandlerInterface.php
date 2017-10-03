<?php

namespace DTag\Bundles\AdminApiBundle\Handler;

use DTag\Handler\HandlerInterface;

interface UserHandlerInterface extends HandlerInterface
{
    /**
     * @return array
     */
    public function allBrokers();
}