<?php

namespace DTag\Bundles\AdminApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DTagAdminApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
