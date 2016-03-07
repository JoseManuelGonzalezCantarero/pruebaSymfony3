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
                $em = $this->getDoctrine()->getManager();
                $category_repo = $em->getRepository('BlogBundle:Category');

                $entry = new Entry();
                $entry->setTitle($form->get('title')->getData());
                $entry->setContent($form->get('content')->getData());
                $entry->setStatus($form->get('status')->getData());
                //upload image
                $file = $form['image']->getData();
                $ext = $file->guessExtension();
                $file_name = time().".".$ext;
                $file->move("uploads", $file_name);

                $entry->setImage($file_name);
                $category = $category_repo->find($form->get('category')->getData());
                $entry->setCategory($category);
                $user = $this->getUser();
                $entry->setUser($user);

                $em->persist($entry);
                $flush = $em->flush();
                if($flush == null)
                {
                    $status = "La entrada se ha creado correctamente";
                }
                else
                {
                    $status = "Error al añadir la entrada";
                }
            }
            else
            {
                $status = "La entrada no se ha creado porque el formulario no es válido";
            }

            $this->session->getFlashBag()->add('status', $status);
            //return $this->redirectToRoute('categoriesIndex');
        }

        return $this->render('BlogBundle:Entry:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
