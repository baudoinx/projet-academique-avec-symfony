<?php

namespace App\Controller;


use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;
use App\Form\FilmType;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\getManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    //list des films
    public function action8(): Response
    {
    	$repo = $this->getDoctrine()
		->getRepository('App\Entity\Film');
		$films = $repo->findAll();
		dump($films);

        return $this->render('film/action8.html.twig', 
			array('films' =>$films));
    }

    //afficher les étails d'un film donné avec les diff acteurs 
	function action9(Film $film){
    	$Acteurs = $film->getActeurs();
    	dump($Acteurs);
    	return $this->render('Film/action9.html.twig', ['film' =>$film, 
            'Acteurs' =>$Acteurs,]);
	}

    //ajouter un film
    public function action10(Request $request){
    	$film = new Film;
		$formFilm = $this->createForm(FilmType::class, $film);
							
		$formFilm->add('Ajouter', SubmitType::class, 
        			array('label' => 'Ajoutation d\'un film'));

        $formFilm->handleRequest($request);                 		
        if($formFilm->isSubmitted() && $formFilm->isValid() ){ 
        	$em=$this->getDoctrine()->getManager();
        	$em->persist($film); 
 			$em->flush();
 			dump($film);

 			return $this->redirectToRoute('action8');

 			//return $this->redirect($this->generateUrl('action1'));
 
         }

        return $this->render('Film/action10.html.twig',
				array('my_form'=>$formFilm->createView()));
    }

    //modifier un film on passe l'id du film a modifier en argument 
    public function action11(Request $request, Film $Film=null){
    	$modifier = $Film->getId()!=null;
		$formFilm = $this->createForm(FilmType::class, $Film);
		$formFilm->add('modifier', SubmitType::class, 
        			array('label' => 'Modification d\'un film'));

        $formFilm->handleRequest($request);                 		
        if($formFilm->isSubmitted()){ 
        	$em=$this->getDoctrine()->getManager();
        	$em->persist($Film); 
 			$em->flush();
 			dump($Film);

 			return $this->redirectToRoute('action8');
 
         }

        return $this->render('Film/action11.html.twig',
				array('my_form'=>$formFilm->createView()));
    }

    //suprimer un film
    function action12(Request $request){

        $repo =$this->getDoctrine()->getRepository(Film::class);
        $em = $this->getDoctrine()->getManager();
        $film = new Film;

        $form = $this->createFormBuilder($film)
            ->add('titre', TextType::class)
            ->add('supprimer', submitType::class)
            ->getform();

        $form->handleRequest($request);
        if($form->isSubmitted()){ 
            $nom_film = $form->get('titre')->getData();
            $getfilm = $repo->findOneBy(array('titre'=>$nom_film));
            $em->remove($getfilm);
            $em->flush();

            return $this->redirectToRoute('action8');
 
         }

		return $this->render('Film/action11.html.twig',
                array('my_form'=>$form->createView()));
	}

    //lister les films sorties entre deux années donnés
    public function action13(Request $request){
        $date1 = $request->request->get('date1');
        $date2 = $request->request->get('date2');

        if($request->request->count()>0){
            $repo = $this->getDoctrine()->getManager()->getRepository(film::class);
            $films = $repo->reserch($date1,$date2);
            dump($films);
            return $this->render('film/action8.html.twig', 
            array('films' =>$films));
        }
        return $this->render('film/action13.html.twig');
        }

    //lister les film sorties (date antérieure)
    public function action14(Request $request){
        $dated = $request->request->get('dated');
        
        if($request->request->count()>0){
            $repo = $this->getDoctrine()->getManager()->getRepository(film::class);
            $films = $repo->reserchFilmAnte($dated);
            dump($films);
            return $this->render('film/action8.html.twig', 
            array('films' =>$films));
        }
        return $this->render('film/action14.html.twig');
    }

    //les acteurs ayant joué dans un film donné
    public function action15(Request $request){
        $titre = $request->request->get('titre');
        
        if($request->request->count()>0){
            $film= $this->getDoctrine()
                         ->getManager()
                         ->getRepository(film::class)
                         ->findOneBy( array('titre' => $titre ));               ;
            $acteurs= $film->getActeurs();
            dump($film);
            dump($acteurs);
            return $this->render('film/acteurFilms.html.twig', ['film' => $film, 'acteurs' => $acteurs]);
                            //compact('titre'), 
                            //compact('acteurs'));
        }
        return $this->render('film/action15.html.twig');

    }

    //les films dans lesquels 2 acteurs donnés ont joué ensemble
    public function action17(Request $request){
        $acteur1 = $request->request->get('acteur1');
        $acteur2 = $request->request->get('acteur2');

        if($request->request->count()>0){
            $repo = $this->getDoctrine()->getManager()->getRepository(film::class);
            $films = $repo->filmsDoncDeuxActeursEns($acteur1, $acteur2);
            dump($films);
            return $this->render('film/action8.html.twig', 
            array('films' =>$films));
        }
        
        return $this->render('film/action17.html.twig');

    } 

    //La durée moyenne de tous les films d’un genre donné
    public function action22(){

        $repo = $this->getDoctrine()->getManager()
                                    ->getRepository(Film::class);
        $films = $repo->filmsDoncDeuxActeursEns();
        dump($acteurs);
        return $this->render('film/action22.html.twig', ['acteurs'=>$acteurs]);

    }

    //Augmenter ou diminuer la note d’un film
    public function action23(Request $request){
        $film = $request->request->get('titre');
        $boutton = $request->request->get('boutton');
        $repo = $this->getDoctrine()->getManager()
                                    ->getRepository(Film::class);

         if($request->request->count() > 0){
          
            if($boutton == "+"){
                  $result = $repo()->augNoteFilm($titre);
            }else $result = $repo()->dimNoteFilm($titre);
        }
        return $this->render('film/action23.html.twig'); 
    }

    //Rechercher des films via une partie de titre
    public function action25(Request $request){
        $titre = $request->request->get('titre');
        

        if($request->request->count()>0){
            $repo = $this->getDoctrine()->getManager()->getRepository(film::class);
            $films = $repo->reserchParPartie($titre);
            dump($films);
            return $this->render('film/action8.html.twig', 
            array('films' =>$films));
        }
        return $this->render('film/action25.html.twig');
        }

    //Augmenter l’âge minimal de tous les films dans lesquels à jouer un acteur donné
    public function action26(Request $request){
        $nom = $request->request->get('nom');
        $aug = $request->request->get('aug');
        

        if($request->request->count()>0){
            if($aug == null) $aug =1;
            $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
            $acteur = $repo->findOneBy(array('nomPrenom'=> $nom));
            $films= $acteur->getfilm();
            $em = $this->getDoctrine()->getManager();
            foreach ($films as $film) {
                $film->setAgeMinimal($film->getAgeMinimal()+ $aug);
                dump($film);
                $em->persist($film); 
                # code...
            }
            $em->flush();
            return $this->render('film/action8.html.twig', 
                    ['films' =>$films, "age"=> true]);
        }
        return $this->render('film/action26.html.twig');

    }

}
