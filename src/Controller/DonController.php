<?php

namespace App\Controller;

use App\Entity\Don;
use App\Form\DonType;
use App\Repository\DonRepository;
use App\Repository\CategorieRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/don')]
class DonController extends AbstractController
{
    


    #[Route('/', name: 'app_don_index')]
    public function index(DonRepository $donRepository,Request $request,SluggerInterface $slugger): Response
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

            return $this->redirectToRoute('app_don_index');
        }






        return $this->render('don/index.html.twig', array('dons' => $donRepository->findAll(),'form' => $form->CreateView()));
    }

    #[Route('/statisreclamation', name: 'app_reclamation_statisreclamation', methods: ['GET'])]
public function statisreclamation(DonRepository $donRepository)
{
    //on va chercher les categories
    $rech = $donRepository->barDep();
    $arr = $donRepository->barArr();
    
    $bar = new barChart ();
    $bar->getData()->setArrayToDataTable(
        [['don', 'Type'],
         ['Collecte des déchets', intVal($rech)],
         ['Éclairage public', intVal($arr)],
        

        ]
    );

    $bar->getOptions()->setTitle('les Dons');
    $bar->getOptions()->getHAxis()->setTitle('Nombre de don');
    $bar->getOptions()->getHAxis()->setMinValue(0);
    $bar->getOptions()->getVAxis()->setTitle('Type');
    $bar->getOptions()->SetWidth(800);
    $bar->getOptions()->SetHeight(400);


    return $this->render('Don/statisDon.html.twig', array('bar'=> $bar )); 

}

    #[Route('/new', name: 'app_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonRepository $donRepository,SluggerInterface $slugger): Response
    {
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);
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

            return $this->redirectToRoute('app_don_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/new.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('don/show.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, DonRepository $donRepository): Response
    {
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donRepository->save($don, true);

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_don_delete')]
    public function delete(Request $request, Don $don, DonRepository $donRepository): Response
    {
       
            $donRepository->remove($don, true);
        

        return $this->redirectToRoute('app_don_index');
    }

    /**
     * @Route("/statis", name="app_stattttt")
     */
    public function statsParType(EntityManagerInterface $entityManager): Response
    {
        // Récupération des statistiques des offres par type
        $stats = $entityManager->createQuery(
            'SELECT t.nom AS categorie, COUNT(o.id) AS nbdon
             FROM Don o
             JOIN o.Categorie t
             GROUP BY t.nom'
        )->getResult();

        // Retourne une vue Twig avec les statistiques des offres par type
        return $this->render('don/statistique.html.twig', [
            'stats' => $stats

        ]);
    }
    
////////////////////////json  pour mobile///////////////////////////////////////////////////


    #[Route('/affichage/mobile', name: 'app_don_allApp')]
    public function allApp(DonRepository $donRepository,SerializerInterface $s){
        $x=$donRepository->findAll();
    
        $json=$s->serialize($x,'json',['groups'=>"dons"]);
        return new Response($json);
     
    }
    #[Route('/ajout/mobile', name: 'app_don_ajoutApp')]
    public function AjoutMobil(Request $req,NormalizerInterface $s,ManagerRegistry $doctrine){
        
        $em = $doctrine->getManager();
        $don=new Don();
        $don->setIdBen($req->get('id_ben'));
        $don->setTitre($req->get('titre'));
        $don->setQte($req->get('qte'));
        $don->setType($req->get('type'));
        $don->setDate($req->get('date'));
        $don->setIdLocal($req->get('id_local'));
        $don->setImge($req->get('imge'));
        $em -> persist($don);
        $em->flush();
        $json=$s->normalize($don,'json',['groups'=>"dons"]);
        return new Response(json_encode($json));
     
    }

    #[Route('/Update/mobile/{id}', name: 'app_don_editApp')]
    public function UpdateMobile(Request $req,$id,NormalizerInterface $s,ManagerRegistry $doctrine){
        
        $em = $doctrine->getManager();
        $don=$em->getRepository(Don::class)->find($id);
        $don->setIdBen($req->get('id_ben'));
        $don->setTitre($req->get('titre'));
        $don->setQte($req->get('qte'));
        $don->setType($req->get('type'));
        $don->setDate($req->get('date'));
        $don->setIdLocal($req->get('id_local'));
        $don->setImge($req->get('imge'));
        $em->flush();
        $json=$s->normalize($don,'json',['groups'=>"dons"]);
        return new Response(" don updated successfully".json_encode($json));
     
    }


    #[Route('/delete/mobile/{id}', name: 'app__deleteApp')]
    public function deleteMobile($id,NormalizerInterface $s,ManagerRegistry $doctrine)
    {
        
        $em = $doctrine->getManager();
        $don=$em->getRepository(Don::class)->find($id);
        $em->remove($don);
        
        
        $em->flush();

        $json=$s->normalize($don,'json',['groups'=>"dons"]);
        return new Response(" don deleted successfully".json_encode($json));
     
    }
}
