<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo = $em->getRepository("BlogBundle:Entry");
        $entries = $entry_repo->findAll();

        foreach($entries as $entry)
        {
            echo $entry->getTitle().'<br>';
            echo $entry->getCategory()->getName().'<br>';
            echo $entry->getUser()->getName().'<br><hr>';
        }

        die();

        return $this->render('BlogBundle:Default:index.html.twig');
    }
}
