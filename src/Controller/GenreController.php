<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    //lister les genres 
    public function action1(): Response
    {
    	$repo = $this->getDoctrine()
		->getRepository('App\Entity\Genre');
		$genres = $repo->findAll();
		dump($genres);
        return $this->render('genre/action1.html.twig', [
            'genres' =>$genres]);
    }

    //ajouter un genre
    function action2(Request $request)
	{
		$genre = new Genre();
		$formGenre = $this->createForm(GenreType::class, $genre);
							
		$formGenre->add('Ajouter', SubmitType::class, 
        			array('label' => 'Ajoutation d\'un genre'));
        $formGenre->handleRequest($request);                 		
        if($formGenre->isSubmitted() && $formGenre->isValid() ){ 
        	$em=$this->getDoctrine()->getManager();
        	$em->persist($genre); 
 			$em->flush();
 			dump($genre);

 			return $this->redirectToRoute('action1');

 			//return $this->redirect($this->generateUrl('action1'));
 
        }
        return $this->render('genre/action2.html.twig',
				array('my_form'=>$formGenre->createView()));
     }
}
