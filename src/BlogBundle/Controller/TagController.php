<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class TagController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @Route("/tags/add", name="tagsAdd")
     */
    public function addAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $status = "La etiqueta se ha creado correctamente";
            }
            else
            {
                $status = "La etiqueta no se ha creado porque el formulario no es válido";
            }

            $this->session->getFlashBag()->add('status', $status);
        }

        return $this->render('BlogBundle:Tag:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
