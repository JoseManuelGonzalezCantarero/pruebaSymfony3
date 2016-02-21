<?php

namespace AppBundle\Controller;

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
}
