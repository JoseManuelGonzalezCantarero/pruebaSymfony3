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
     * @Route("/tags/index", name="tagsIndex")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tags_repo = $em->getRepository('BlogBundle:Tag');

        $tags = $tags_repo->findAll();

        return $this->render('BlogBundle:Tag:index.html.twig', array(
            'tags' => $tags
        ));
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
                $tag = new Tag();
                $tag->setName($form->get('name')->getData());
                $tag->setDescription($form->get('description')->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $flush = $em->flush();

                if($flush == null)
                {
                    $status = "La etiqueta se ha creado correctamente";
                }
                else
                {
                    $status = "Error al añadir la etiqueta";
                }
            }
            else
            {
                $status = "La etiqueta no se ha creado porque el formulario no es válido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute('tagsIndex');
        }

        return $this->render('BlogBundle:Tag:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
