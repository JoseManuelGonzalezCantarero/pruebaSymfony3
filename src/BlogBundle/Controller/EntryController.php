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
     * @Route("/", name="entriesIndex")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo = $em->getRepository('BlogBundle:Entry');
        $category_repo = $em->getRepository('BlogBundle:Category');
        $entries = $entry_repo->findAll();
        $categories = $category_repo->findAll();
        return $this->render('BlogBundle:Default:index.html.twig', array('entries' => $entries, 'categories' => $categories));
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
                $entry_repo = $em->getRepository('BlogBundle:Entry');

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

                $entry_repo->saveEntryTags($form['tags']->getData(), $form['title']->getData(), $category, $user);

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
            return $this->redirectToRoute('entriesIndex');
        }

        return $this->render('BlogBundle:Entry:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/entries/delete/{id}", name="entriesDelete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo = $em->getRepository('BlogBundle:Entry');
        $entry_tag_repo = $em->getRepository('BlogBundle:EntryTag');

        $entry = $entry_repo->find($id);
        $entryTags = $entry_tag_repo->findBy(array("entry" => $entry));
        foreach($entryTags as $et)
        {
            if(is_object($et))
            {
                $em->remove($et);
                $em->flush();
            }
        }
        if(is_object($entry))
        {
            $em->remove($entry);
            $em->flush();
        }

        return $this->redirectToRoute('entriesIndex');
    }
}
