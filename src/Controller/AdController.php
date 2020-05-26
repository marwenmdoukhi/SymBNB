<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdController extends AbstractController
{

    /**
     * @var AdRepository
     */
    private $repo;

    /*
     * @var ObjectManager
     */
    private $manager;

    public function __construct(AdRepository $repo, EntityManagerInterface $manager)
    {
        $this->repo = $repo;
        $this->manager = $manager;
    }

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
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request,EntityManagerInterface $manager){
        $ad = new Ad();
        $image = new Image();
        $form=$this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
        $manager->persist($ad);
        $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitre()}</strong> a bien été enregistrée !"
            );
       return  $this->redirectToRoute('ads_show',[
            'slug'=>$ad->getSlug()
        ]);
        }
        return $this->render('ad/new.html.twig',[
            'form'=>$form->createView()

        ]);

    }

    /**
     * @Route("/ads/{slug}/edit",name="ads_edit")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     *  @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     */
    public function edit(Ad $ad,Request $request,EntityManagerInterface $manager){

        $form=$this->createForm(AdType::class,$ad);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitre()}</strong> a bien été enregistrée !"
            );
           return $this->redirectToRoute('ads_show',[
                'slug'=>$ad->getSlug()
            ]);


        }

    return $this->render('ad/edit.html.twig',[
        'form'=>$form->createView(),
        'ad'=>$ad
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

    /**
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     * @param Ad $ad
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Ad $ad )
    {
        $this->manager->remove($ad);
        $this->manager->flush();

        $this->addFlash(
            'success',
            "Suppression de l'annonce (<strong>{$ad->getTitre()}</strong>) effectuée avec succès - le ".date('d/m/Y à H:i').""
        );

        return $this->redirectToRoute('ads_index');
    }

}
