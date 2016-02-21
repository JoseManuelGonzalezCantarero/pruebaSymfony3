<?php

namespace MiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/mi-bundle")
     */
    public function indexAction()
    {
        return $this->render('MiBundle:Default:index.html.twig');
    }
}
