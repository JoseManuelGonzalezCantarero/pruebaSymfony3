<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Curso;
use AppBundle\Form\CursoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

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
        $curso_ochenta = $cursos_repo->findOneByPrecio(80);

        echo $curso_ochenta->getTitulo();

//        foreach($cursos as $curso)
//        {
//            echo $curso->getTitulo()."<br>";
//            echo $curso->getDescripcion()."<br>";
//            echo $curso->getPrecio()."<br><hr>";
//        }

        die();
    }

    /**
     * @Route("/pruebas/update/{id}/{titulo}/{descripcion}/{precio}", name="pruebasUpdate")
     */
    public function updateAction($id, $titulo, $descripcion, $precio)
    {
        $em = $this->get('doctrine')->getManager();
        $cursos_repo = $em->getRepository('AppBundle:Curso');
        $curso = $cursos_repo->find($id);

        $curso->setTitulo($titulo);
        $curso->setDescripcion($descripcion);
        $curso->setPrecio($precio);

        $em->persist($curso);
        $flush = $em->flush();

        if($flush != null)
        {
            echo 'El curso no se ha actualizado';
        }
        else
        {
            echo 'El curso  se ha actualizado';
        }

        die();
    }

    /**
     * @Route("/pruebas/delete/{id}", name="pruebasDelete")
     */
    public function delateAction($id)
    {
        $em = $this->get('doctrine')->getManager();
        $cursos_repo = $em->getRepository('AppBundle:Curso');
        $curso = $cursos_repo->find($id);
        $em->remove($curso);
        $flush = $em->flush();

        if($flush != null)
        {
            echo 'EL curso no se ha borrado correctamente';
        }
        else
        {
            echo 'El curso se ha borrado correctamente';
        }

        die();
    }

    /**
     * @Route("/pruebas/native", name="pruebasNative")
     */
    public function nativeSqlAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cursos_repo = $em->getRepository('AppBundle:Curso');
//        $db = $em->getConnection();
//        $query = "SELECT * FROM cursos";
//        $stmt = $db->prepare($query);
//        $params = array();
//        $stmt->execute($params);
        //$cursos = $stmt->fetchAll();
//        $query = $em->createQuery("SELECT c FROM AppBundle:Curso c WHERE c.precio > :precio")->setParameter("precio", 51);
//        $cursos = $query->getResult();

        $cursos = $cursos_repo->getCursos();
        foreach($cursos as $curso)
        {
            echo $curso->getTitulo().'<br>';
        }

        die();
    }

    /**
     * @Route("/pruebas/form", name="pruebasForm")
     */
    public function formAction(Request $request)
    {
        $curso = new Curso();
        $form = $this->createForm(CursoType::class, $curso);

        $form->handleRequest($request);

        if($form->isValid())
        {
            $status = "Formulario válido";
            $data = array(
                "titulo" => $form->get("titulo")->getData(),
                "descripcion" => $form->get("descripcion")->getData(),
                "precio" => $form->get("precio")->getData()
            );
        }
        else
        {
            $status = null;
            $data = null;
        }

        return $this->render('pruebas/form.html.twig', [
            'form' => $form->createView(),
            'status' => $status,
            'data' => $data
        ]);
    }

    /**
     * @Route("/pruebas/validar-email/{email}", name="pruebasValidarEmail")
     */
    public function validarEmailAction($email)
    {
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = "Pasame un buen correo";

        $error = $this->get("validator")->validate($email, $emailConstraint);

        if(count($error) == 0)
        {
            echo "<h1>Correo válido</h1>";
        }
        else
        {
            echo $error[0]->getMessage();
        }

        die();
    }
}
