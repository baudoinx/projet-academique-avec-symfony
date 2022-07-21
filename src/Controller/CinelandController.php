<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;

use Doctrine\Persistence\getManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CinelandController extends AbstractController
{
   
    public function accueil(): Response
    {
        return $this->render('cineland/accueil.html.twig');
    }

    public function init(){
    	$em = $this->getDoctrine()->getManager();

    	$genre1 = new Genre;
    	$genre2 = new Genre;
    	$genre3 = new Genre;
    	$genre4 = new Genre;
    	$genre4 = new Genre;
    	$genre5 = new Genre;

    	$genre1->setNom("animation");
    	$genre2->setNom("policier");
    	$genre3->setNom("drame");
    	$genre4->setNom("comédie");
    	$genre5->setNom("X");

    	$acteur1 = new Acteur;
    	$acteur2 = new Acteur;
    	$acteur3 = new Acteur;
    	$acteur4 = new Acteur;
    	$acteur5 = new Acteur;
    	
		$acteur1->setNomPrenom("Galabru Michel");
    	$acteur1->setDateNaissance(new \dateTime('27-10-1922'));
    	$acteur1->setNationalite("france");

    	$acteur2->setNomPrenom("Deneuve Catherine");
    	$acteur2->setDateNaissance(new \dateTime('22-10-1943'));
    	$acteur2->setNationalite("france");

    	$acteur3->setNomPrenom("Depardieu Gérard");
    	$acteur3->setDateNaissance(new \dateTime('27-12-1948'));
    	$acteur3->setNationalite("russie");

    	$acteur4->setNomPrenom("Lanvin Gérard");
    	$acteur4->setDateNaissance(new \dateTime('21-06-1950'));
    	$acteur4->setNationalite("france");

    	$acteur5->setNomPrenom("Désiré Dupond");
    	$acteur5->setDateNaissance(new \dateTime('23-12-2001'));
    	$acteur5->setNationalite("groland");


    	$film1 = new Film;
    	$film2 = new Film;
    	$film3 = new Film;
    	$film4 = new Film;
    	$film5 = new Film;

    	$film1->setTitre("Astérix aux jeux olympiques");
    	$film1->setDuree(117);
    	$film1->setDateSortie(new \dateTime('20-01-2008'));
    	$film1->setNote(8);
    	$film1->setAgeMinimal(0);
    	$film1->setGenre($genre1);

    	$film2->setTitre("Le Dernier Métro");
    	$film2->setDuree(131);
    	$film2->setDateSortie(new \dateTime('17-09-1980'));
    	$film2->setNote(15);
    	$film2->setAgeMinimal(12);
    	$film2->setGenre($genre3);
    	$film2->addActeur($acteur2);
    	$film2->addActeur($acteur3);

    	$film3->setTitre("le choix des armes");
    	$film3->setDuree(135);
    	$film3->setDateSortie(new \dateTime('19-10-1981'));
    	$film3->setNote(13);
    	$film3->setAgeMinimal(18);
    	$film3->setGenre($genre2);
    	$film3->addActeur($acteur2);
    	$film3->addActeur($acteur3);
    	$film3->addActeur($acteur4);

    	$film4->setTitre("Les Parapluies de Cherbourg");
    	$film4->setDuree(91);
    	$film4->setDateSortie(new \dateTime('19-10-1964'));
    	$film4->setNote(9);
    	$film4->setAgeMinimal(0);
    	$film4->setGenre($genre3);
    	$film4->addActeur($acteur2);

    	$film5->setTitre("La Guerre des boutons");
    	$film5->setDuree(90);
    	$film5->setDateSortie(new \dateTime('18-04-1962'));
    	$film5->setNote(7);
    	$film5->setAgeMinimal(0);
    	$film5->setGenre($genre4);
    	$film5->addActeur($acteur3);

    	$em->persist($genre1);
    	$em->persist($genre2);
    	$em->persist($genre3);
    	$em->persist($genre4);
    	$em->persist($genre5);
    	$em->persist($acteur1);
    	$em->persist($acteur2);
    	$em->persist($acteur3);
    	$em->persist($acteur4);
    	$em->persist($acteur5);
    	$em->persist($film1);
    	$em->persist($film2);
    	$em->persist($film3);
    	$em->persist($film4);
    	$em->persist($film5);

    	$em->flush();


    	return $this->render('cineland/accueil.html.twig');

    }
}
