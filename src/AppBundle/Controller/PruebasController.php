<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Curso;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controlador de prueba del curso Symfony3
 * @package AppBundle\Controller
 * @author Manu <sefirot_cloud@hotmail.com>
 */
class PruebasController extends Controller
{
    /**
     * @Route("/pruebas/{lang}/{name}/{page}", name="pruebasIndex", defaults={"lang" = "es", "name" = "Pepe", "page" = 1},
     * methods={"GET", "POST"}, requirements={"name" = "[a-zA-Z]*", "page"="\d+", "lang" = "es|pt|en"})
     * @param $name string
     * @param $page integer
     * @return array
     */
    public function indexAction(Request $request, $name, $page)
    {
        $productos = array
        (
            array("producto" => "Consola", "precio" => 2),
            array("producto" => "Consola 2", "precio" => 3),
            array("producto" => "Consola 3", "precio" => 4),
            array("producto" => "Consola 4", "precio" => 5),
            array("producto" => "Consola 5", "precio" => 6)
        );

        $fruta = array("manzana" => "golden", "pera" => "rica");

        return $this->render('pruebas/index.html.twig', [
            'texto' => $name . " - " . $page,
            'productos' => $productos,
            'fruta' => $fruta
        ]);
    }

    /**
     * @Route("/pruebas/create", name="pruebasCreate")
     */
    public function createAction()
    {
        $curso = new Curso();
        $curso->setTitulo('Curso de Symfony 3 de Victor Robles');
        $curso->setDescripcion('Curso completo de Symfony 3');
        $curso->setPrecio(80);

        $em = $this->get('doctrine')->getManager();
        $em->persist($curso);
        $flush = $em->flush();
        if($flush != null)
        {
            echo 'El curso no se ha creado bien!!';
        }
        else
        {
            echo 'El curso se ha creado correctamente';
        }

        die();
    }

    /**
     * @Route("/pruebas/read", name="pruebasRead")
     */
    public function readAction()
    {
        $em = $this->get('doctrine')->getManager();
        $cursos_repo = $em->getRepository('AppBundle:Curso');
        $cursos = $cursos_repo->findAll();
        foreach($cursos as $curso)
        {
            echo $curso->getTitulo()."<br>";
            echo $curso->getDescripcion()."<br>";
            echo $curso->getPrecio()."<br><hr>";
        }

        die();
    }
}
