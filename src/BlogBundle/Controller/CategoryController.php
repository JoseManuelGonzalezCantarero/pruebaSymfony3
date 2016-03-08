<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Category;
use BlogBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CategoryController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @Route("/categories/index", name="categoriesIndex")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories_repo = $em->getRepository('BlogBundle:Category');

        $categories = $categories_repo->findAll();

        return $this->render('BlogBundle:Category:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * @Route("/categories/add", name="categoriesAdd")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $category = new Category();
                $category->setName($form->get('name')->getData());
                $category->setDescription($form->get('description')->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $flush = $em->flush();

                if($flush == null)
                {
                    $status = "La categoría se ha creado correctamente";
                }
                else
                {
                    $status = "Error al añadir la categoría";
                }
            }
            else
            {
                $status = "La categoría no se ha creado porque el formulario no es válido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute('categoriesIndex');
        }

        return $this->render('BlogBundle:Category:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/categories/delete/{id}", name="categoriesDelete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category_repo = $em->getRepository('BlogBundle:Category');
        $category= $category_repo->find($id);
        if(count($category->getEntries()) == 0)
        {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('categoriesIndex');
    }

    /**
     * @Route("/categories/update/{id}", name="categoriesUpdate")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category_repo = $em->getRepository('BlogBundle:Category');
        $category= $category_repo->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            if($form->isValid())
            {
                $category->setName($form->get('name')->getData());
                $category->setDescription($form->get('description')->getData());

                $em->persist($category);
                $flush = $em->flush();

                if($flush == null)
                {
                    $status = "La categoría se ha editado correctamente";
                }
                else
                {
                    $status = "Error al editar la categoría";
                }
            }
            else
            {
                $status = "La categoría no se ha editado porque el formulario no es válido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute('categoriesIndex');
        }
        return $this->render('BlogBundle:Category:update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("category/{id}/{page}", name="categoryRead", defaults={"page" = 1})
     */
    public function categoryAction($id, $page)
    {
        $pageSize = 5;
        $em = $this->getDoctrine()->getManager();
        $category_repo = $em->getRepository('BlogBundle:Category');
        $category = $category_repo->find($id);

        $entry_repo = $em->getRepository('BlogBundle:Entry');
        $entries = $entry_repo->getCategoryEntries($category, $pageSize, $page);

        $totalItems = count($entries);
        $pagesCount = ceil($totalItems / $pageSize);
        return $this->render('BlogBundle:Category:category.html.twig', array(
            'category' => $category,
            'categories' => $category_repo->findAll(),
            'entries' => $entries,
            'pagesCount' => $pagesCount,
            'currentPage' => $page
        ));
    }
}
