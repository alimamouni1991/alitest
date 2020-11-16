<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

/**
* @Route("/hello/{prenom}/age/{age}" , name="hello")
* @Route("/hello" , name="hello_base")
* @Route("/hello/{prenom}" , name="hello_prenom")
* Montre la page qui dit salam
* 
*@return void
*/

public function hello($prenom = "anonyme", $age= 0 ){
    return $this->render(
        'hello.html.twig',
        [
            'prenom'=>$prenom,
            'age'=>$age
        ]

    );
}

/**
* @Route("/" , name="homepage")
*/
public function home(){

    $prenom = ["ali" =>28, "montassir" =>19, "yazid" =>17];
    return $this->render(
        'home.html.twig',
        [
            'title'=>"Aurevoir tout le monde",
            'age' =>85,
            'tableau' =>$prenom
        
        ]
    );
}

}
?>