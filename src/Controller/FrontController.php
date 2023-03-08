<?php

namespace App\Controller;
use App\Entity\Don;
use App\Form\DonType;
use Endroid\QrCode\QrCode;
use App\Repository\DonRepository;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    
// #[Route('/Qrcode', name: 'Qrcode')]

public function getQrCodeForProduct(int $id): Response
{
    // Récupérer les informations du compte bancaire à partir de la base de données
    $pr = $this->getDoctrine()->getRepository(Don::class)->find($id);

    if (!$pr) {
        throw $this->createNotFoundException('Le  produit  n\'existe pas');
    }
    $qrText = sprintf(
        "Nom : %s\n Id benovole : mahdii %s",
        $pr->getIdBen(),
        $pr->getTitre(),
       
    );

$qrCode = new QrCode($qrText);
    // Générer le code QR à partir des informations du compte bancaire
   
    $qrCode->setSize(300);
    $qrCode->setMargin(10);

    $pngWriter = new PngWriter();
    $qrCodeResult = $pngWriter->write($qrCode);

     // Générer la réponse HTTP contenant le code QR
     $response = new QrCodeResponse($qrCodeResult);
     $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
         ResponseHeaderBag::DISPOSITION_ATTACHMENT,
         'qr_code.png'
     ));


    return $response;
}   



}
