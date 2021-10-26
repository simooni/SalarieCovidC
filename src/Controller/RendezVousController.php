<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\RendezVous;
use App\Entity\Tdocument;
use App\Entity\User;
use App\Form\MessageFormType;
use App\Form\RendezVousFormType;
use App\Form\TdocumentFormType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/etudiant")
 */

class RendezVousController extends AbstractController
{
   

    

    /**
     * @Route("/liste", name="ListeEtudiant")
     * @IsGranted("ROLE_ADMIN")
     */ 
    public function ListeEtudiant()
    {
        $etudiants = $this->getDoctrine()->getRepository(User::class)->countDocumentUser();
        // dd($etudiants);
        // $etudiants   = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('home/ListeEtudiant.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }
    /**
     * @Route("/RendezVous/{id}", name="RendezVous")
     * @IsGranted("ROLE_ADMIN")
     */
    public function RendezVous($id)
    {
        $type = null;

        if(isset($_GET['type'])){
            $type = $_GET['type'];
            // dd($typedoc); 
        }

        $etudiant  = $this->getDoctrine()->getRepository(User::class)->find($id);
        $tmessage   = $this->getDoctrine()->getRepository(Message::class)->findBy(array('user_create' => $id));
        $rendez_vous  = $this->getDoctrine()->getRepository(RendezVous::class)->findBy(array('etudiant' => $id));

        if($type == 'RendezVous'){
            return $this->render('tableBody/RendezVousMessage.html.twig', [ 
                'etudiant' => $etudiant,
                'tmessage'=>$tmessage ,
                'rendez_vous' => $rendez_vous ]
            );
        }


        
        $TdocumentSanitaire   = $this->getDoctrine()->getRepository(Tdocument::class)->findBy(array('type_document'=>'pass sanitaire', 'user_create' => $id,'supprimer' => '0'));
        $TdocumentCovid   = $this->getDoctrine()->getRepository(Tdocument::class)->findBy(array('type_document'=>'certificat COVID' , 'user_create' => $id , 'supprimer' => '0'));
        
        return $this->render('home/RendezVous.html.twig', [
            'etudiant' => $etudiant,
            'TdocumentSanitaire' => $TdocumentSanitaire,
            'TdocumentCovid' => $TdocumentCovid,
            'tmessage' => $tmessage,
            'rendez_vous' => $rendez_vous
        ]);
    }

     /**
     * @Route("/date/{id}", name="DateRendezVous", methods={"GET","POST"})
     *  @IsGranted("ROLE_ADMIN")
     */
    public function DateRendezVous( Request $request,ValidatorInterface $validator, UserInterface $user,$id): Response
    {
     
        $rendezvous = new RendezVous();
        $errors    = $validator->validate($rendezvous);
        $form      = $this->createForm(RendezVousFormType::class, $rendezvous);
        $form->handleRequest($request);

        $dateRV = $request->request->get('_rendez-vous');
        $etudiant = $this->getDoctrine() ->getRepository(User::class)->find($id);
      
            if ($dateRV !='' )  {
$dateRV = new DateTime($dateRV);
                
                $rendezvous->setDateRV($dateRV) ;
                $rendezvous->setUserCreate($user) ;
                $rendezvous->setEtudiant($etudiant) ;
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($rendezvous);
                $entityManager->flush();
                
              

                return new JsonResponse('ok');
            }else{

                    foreach ($form->getErrors(true) as $error) {
                        $ers[$error->getOrigin()->getName()] = $error->getMessage();
                    }
                    if(empty($file))
                    $ers['fileInput'] = "veuillez remplir ce champ";
                    return new JsonResponse($ers);
            }

    }




}
