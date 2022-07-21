<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Form\ActeurType;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\getManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActeurController extends AbstractController
{
    //liste des acteurs
    public function action3(): Response
    {
    	$repo = $this->getDoctrine()
		->getRepository('App\Entity\Acteur');
		$acteurs = $repo->findAll();
		dump($acteurs);

		return $this->render('acteur/action3.html.twig', 
			array('acteurs' =>$acteurs));

    }
    //les détails d’un acteur donné
    function action4(Acteur $acteur){
    	$films = $acteur->getFilm();
    	dump($films);
    	return $this->render('Acteur/action4.html.twig', ['acteur' =>$acteur, 
            'films' =>$films,]);
	}

    //ajouter un acteur
	function action5(Request $request){
		$acteur = new Acteur;
		$formActeur = $this->createForm(ActeurType::class, $acteur);
							
		$formActeur->add('Ajouter', SubmitType::class, 
        			array('label' => 'Ajoutation d\'un acteur'));

        $formActeur->handleRequest($request);                 		
        if($formActeur->isSubmitted() && $formActeur->isValid() ){ 
        	$em=$this->getDoctrine()->getManager();
        	$em->persist($acteur); 
 			$em->flush();
 			dump($acteur);

 			return $this->redirectToRoute('action3');

 			//return $this->redirect($this->generateUrl('action1'));
 
         }

        return $this->render('Acteur/action5.html.twig',
				array('my_form'=>$formActeur->createView()));

	}

    //modifier un acteur
	function action6(Request $request, Acteur $acteur=null){
		$ajouter = $acteur->getId()!=null;
		$formActeur = $this->createForm(ActeurType::class, $acteur);
		$formActeur->add('Modier', SubmitType::class, 
        			array('label' => 'Modifier'));

        $formActeur->handleRequest($request);                 		
        if($formActeur->isSubmitted()){ 
        	$em=$this->getDoctrine()->getManager();
        	$em->persist($acteur); 
 			$em->flush();
 			dump($acteur);

 			return $this->redirectToRoute('action3');
 
         }

        return $this->render('Acteur/action6.html.twig',
				array('my_form'=>$formActeur->createView()));
	}


    //supprimer un acteur
    function action7(Request $request){

        $repo =$this->getDoctrine()->getRepository(Acteur::class);
        $em = $this->getDoctrine()->getManager();
        $acteur = new Acteur;

        $form = $this->createFormBuilder($acteur)
            ->add('nom_Prenom', TextType::class)
            ->add('supprimer', submitType::class)
            ->getform();

        $form->handleRequest($request);
        if($form->isSubmitted()){ 
            $nom_acteur = $form->get('nom_Prenom')->getData();
            $getacteur = $repo->findOneBy(array('nomPrenom'=>$nom_acteur));
            $em->remove($getacteur);
            $em->flush();
            //dump($getacteur);

            return $this->redirectToRoute('action3');
 
         }
        return $this->render('Acteur/action7.html.twig',
                array('my_form'=>$form->createView()));
    }

    //les acteurs ayant joué dans au moins 3 films différents
    public function action16(){
        $repo = $this->getDoctrine()->getManager()
                                    ->getRepository(Acteur::class);
        $acteurs = $repo->acteursPlusDeToisfilm();
        dump($acteurs);
        return $this->render('acteur/action16.html.twig', ['acteurs'=>$acteurs]);

    }

    //Les genres pour lesquels un acteur donné a joué au moins 2 films
    public function action18(Request $request){
        $acteur = $request->request->get('acteur');
        $genres=null;
        if($request->request->count()>0){
            $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
            $genres = $repo->acteursMinimimFilms($acteur);
            dump($genres);
            dump($acteur);
            return $this->render('genre/action1.html.twig', 
                ['genres' =>$genres]);
        }

        return $this->render('acteur/action18.html.twig');
    }

    //La durée en minutes de tous les films joués par un acteur donné
    public function action19(Request $request){
        $acteur = $request->request->get('acteur');
        $duree=null;
        if($request->request->count()>0){
            $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
            $res= $repo->dureeEnMinFilms($acteur);
            $duree = $res['0']['dureeTotal'];
            dump($acteur);
            dump($res);
            return $this->render('acteur/dureetotalFilm.html.twig', 
                ['duree' =>$duree]);
        }

        return $this->render('acteur/action19.html.twig');
    }

    //pour chaque acteur la liste des films dans lesquels il a joué dans l’ordre chronologique
    public function action20(Request $request){
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        $films = $repo->filmsPourActeurchro();
        dump($films);

        return $this->render('acteur/action20.html.twig', 
                ['films' =>$films]);
    }

    //Pour chaque acteur la liste des genres dans lesquels il a joué au moins un film
    public function action21(){
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        $genres = $repo->genresPourActeur();
        dump('genres');

        return $this->render('acteur/action21.html.twig', 
                ['genres' =>$genres]);   
    }

}