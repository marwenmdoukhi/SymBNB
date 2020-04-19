<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $repo
     * @return Response
     */
    public function index(AdRepository $repo)
    {

        $ads=$repo->findAll();
        return $this->render('ad/index.html.twig', [
        'ads'=>$ads
        ]);
    }

    /**
     * permet de crer une annonce
     * @Route("/ads/new",name="ads_create")
     */
    public function create(){
        $ad = new Ad();
        $form=$this->createForm(AdType::class,$ad);



        return $this->render('ad/new.html.twig',[
            'form'=>$form->createView()

        ]);

    }

    /**
     * @Route("/ads/{slug}",name="ads_show")
     * @param $slug
     * @param Ad $ad
     * @return Response
     */
    public function show($slug,Ad $ad){
        // on utiliser le parm converter dans le parametre
        // supprision ,AdRepository $repository
       // $ad=$repository->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',[
            'ad'=>$ad
        ]);
    }


}