<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdController extends AbstractController
{
    #[Route('/ads', name: 'ads_index')]
    public function index(AdRepository $repo): Response
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    // #[Route('/ads/{slug}', name: "ads_show")]
    // public function show(string $slug, AdRepository $repo): Response
    // {
    //     $ad = $repo->findOneBy(["slug"=>$slug]);

    //     return $this->render("ad/show.html.twig",[
    //         'ad' => $ad
    //     ]);
    // }

  
    #[Route("/ads/new", name: "ads_create")]
    public function create(EntityManagerInterface $manager): Response
    {
        $ad = new Ad();
        // php bin/console make:form AnnonceType => association avec Ad
        $form = $this->createForm(AnnonceType::class,$ad);


            // $manager->persist($ad);
            // $manager->flush();

        return $this->render("ad/new.html.twig",[
            'myForm' => $form->createView()
        ]);
    }


    #[Route('/ads/{slug}', name: "ads_show")]
    public function show(
      #[MapEntity(mapping: ['slug' => 'slug'])] Ad $ad): Response
    {
        return $this->render("ad/show.html.twig",[
            'ad' => $ad
        ]);
    }
    
 
    
}
