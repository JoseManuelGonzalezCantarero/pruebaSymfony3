<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Entry;
use BlogBundle\Form\EntryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class EntryController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @Route("/entries/add", name="entriesAdd")
     */
    public function addAction(Request $request)
    {
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
//                $entry = new Entry();
//                $entry->setName($form->get('name')->getData());
//                $entry->setDescription($form->get('description')->getData());
//
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($category);
//                $flush = $em->flush();
//
//                if($flush == null)
//                {
//                    $status = "La categoría se ha creado correctamente";
//                }
//                else
//                {
//                    $status = "Error al añadir la categoría";
//                }
            }
            else
            {
                $status = "La categoría no se ha creado porque el formulario no es válido";
            }

            //$this->session->getFlashBag()->add('status', $status);
            //return $this->redirectToRoute('categoriesIndex');
        }

        return $this->render('BlogBundle:Entry:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
