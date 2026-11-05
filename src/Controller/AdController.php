<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $ad = new Ad();
        // php bin/console make:form AnnonceType => association avec Ad
        $form = $this->createForm(AnnonceType::class,$ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
        
            // gestion des images
            foreach( $ad->getImages() as $image ){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>".$ad->getTitle()."</strong> a bien été enregistrée"
            );
            return $this->redirectToRoute('ads_show',[
                "slug" => $ad->getSlug()
            ]);
        }

        return $this->render("ad/new.html.twig",[
            'myForm' => $form->createView()
        ]);
    }

    #[Route("/ads/{slug}/edit", name:"ads_edit")]
    public function edit(
          #[MapEntity(mapping: ['slug' => 'slug'])] Ad $ad,
          Request $request, EntityManagerInterface $manager  
    ): Response
    {
        $form = $this->createForm(AnnonceType::class,$ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
             // gestion des images
            foreach( $ad->getImages() as $image ){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "warning",
                "L'annonce <strong>".$ad->getTitle()."</strong> a bien été modifiée"
            );

            return $this->redirectToRoute("ads_show",[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render("ad/edit.html.twig",[
            "ad" => $ad,
            "myForm" => $form->createView()
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



