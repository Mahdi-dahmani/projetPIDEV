<?php

namespace App\Controller;
use App\Entity\Don;
use App\Form\DonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DonRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(Request $request,DonRepository $donRepository,SluggerInterface $slugger): Response
    {
        

        return $this->render('front/index.html.twig');
    }


    #[Route('/affichage_don_front', name: 'app_affichage_don')]
    public function affichage_don(DonRepository $donRepository,Request $request,SluggerInterface $slugger): Response
    {
        $don = new Don();
        $form = $this->createForm(donType::class, $don);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('imge')->getData();
        
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
        
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('don_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
        
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $don->setImge($newFilename);
            }
        
                    $donRepository->save($don, true);
        
                    return $this->redirectToRoute('app_front');
                }



        return $this->render('front/affichage_don.html.twig', array("dons"=> $donRepository->findAll(),"form"=>$form->CreateView()));
    }
    




}
