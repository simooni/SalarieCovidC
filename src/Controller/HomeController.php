<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\RendezVous;
use App\Entity\Tdocument;
use App\Entity\User;
use App\Form\MessageFormType;
use App\Form\TdocumentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use PDO;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class HomeController extends AbstractController
{
   

    /**
     * @Route("/default", name="default")
     */
    public function default()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('ListeEtudiant');
        }else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/home", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function home()
    {
        
        $user = $this->getUser()->getId();
        $typedoc = null;

        if(isset($_GET['type'])){
            $typedoc = $_GET['type'];
            // dd($typedoc); 
        }

        $TdocumentSanitaire   = $this->getDoctrine()->getRepository(Tdocument::class)->findBy(array('type_document'=>'pass sanitaire', 'user_create' => $user,'supprimer' => '0'));
        if($typedoc == 'sanitaire'){
            return $this->render('tableBody/tdocument.html.twig', 
                [ 'TdocumentSanitaire'=>$TdocumentSanitaire  ]
            );
        }
        $TdocumentCovid   = $this->getDoctrine()->getRepository(Tdocument::class)->findBy(array('type_document'=>'certificat COVID' , 'user_create' => $user , 'supprimer' => '0'));
        // dd($TdocumentCovid);
        if($typedoc == 'COVID'){
            return $this->render('tableBody/tdocumentCovid.html.twig', 
                [ 'TdocumentCovid'=>$TdocumentCovid  ]
            );
        }
        $tmessage   = $this->getDoctrine()->getRepository(Message::class)->findBy(array('user_create' => $user));
        $DateRenderVous = $this->getDoctrine()->getRepository(RendezVous::class)->findOneByDateRenderVous($user);

        if($typedoc == 'message'){
            return $this->render('tableBody/message.html.twig', [ 
                'tmessage' => $tmessage,
                'DateRenderVous' => $DateRenderVous  ]
            );
        }

        return $this->render('home/index.html.twig', [
            'TdocumentSanitaire' => $TdocumentSanitaire,
            'TdocumentCovid' => $TdocumentCovid,
            'tmessage' => $tmessage,
            'DateRenderVous' => $DateRenderVous
        ]);
    }

     

    /**
     * @Route("/sidebar", name="sidebar")
     */
    public function sidebar()
    {
        return $this->render('admin/sidebar.html.twig');
    }

   /**
     * @Route("/new/{type_document}", name="new_document", methods={"GET","POST"})
     */
    public function new($type_document, Request $request,ValidatorInterface $validator, UserInterface $user): Response
    {
        $tdocument = new Tdocument();
        $errors    = $validator->validate($tdocument);
        $form      = $this->createForm(TdocumentFormType::class, $tdocument);
        $form->handleRequest($request);

        $file = $request->files->get('file');
        // dd($file);
        
            if($file != ""){
                $file = $request->files->get('file');
                $fileName = md5(uniqid()).'+'.str_replace(" ","_",$file->getClientOriginalName());
                $file->move($this->getParameter('brochures_directory'),$fileName);
                // $request->getPathInfo();
                // $guessExtension = explode('.',$file->getClientOriginalName());
                
                $tdocument->setFile($fileName);
                $tdocument->setTypeDocument($type_document) ;
                $tdocument->setUserCreate($user);
                $tdocument->setSupprimer('0');
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tdocument);
                $entityManager->flush();

                return new JsonResponse('ok');
            }else{

                    foreach ($form->getErrors(true) as $error) {
                        $ers[$error->getOrigin()->getName()] = $error->getMessage();
                    }
                    if(empty($file))
                    $ers['fileInput'] = "veuillez sélectionner file";
                    return new JsonResponse($ers);
            }

    }

    /**
     * @Route("/newMessage", name="send_message", methods={"GET","POST"})
     */
    public function newMessage( Request $request,ValidatorInterface $validator, UserInterface $user): Response
    {
     
        $message = new Message();
        $errors    = $validator->validate($message);
        $form      = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        $TextMessage = $request->request->get('_terx-message');
      
            if ($TextMessage !='' )  {
                
                $message->setText($TextMessage) ;
                $message->setUserCreate($user);
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($message);
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
 
    /**
     * @Route("/supprimer/{id}", name="document_supprimer", methods={"GET","POST"})
     */
    public function suspendre(Request $request, Tdocument $tdocument): Response
    {
        if ($this->isCsrfTokenValid('supprimer_'.$tdocument->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $tdocument->setSupprimer('1');
            $entityManager->persist($tdocument);
            $entityManager->flush();
            return new JsonResponse('ok');
        }else{
            return new JsonResponse('error');
        }

    }
    /**
     * @Route("/excel", name="exporter")
     */
    public function excel(Request $request)
    {

        $spreadsheet = new Spreadsheet();

        // $salarie  = $this->getDoctrine()->getRepository(User::class)->findAll();
        $salaries = $this->getDoctrine()->getRepository(Tdocument::class)->findByDocumentUser();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'User');
        $sheet->setCellValue('C1', 'Nom');
        $sheet->setCellValue('D1', 'Prenom');
        $sheet->setCellValue('E1', 'Cin');
        $sheet->setCellValue('F1', 'File'); 
  
       $i=2;
      
        foreach($salaries as $salarie){
            $sheet->setCellValue('A'.$i, $salarie->getUserCreate()->getId());
            $sheet->setCellValue('B'.$i, $salarie->getUserCreate()->getUser());
            $sheet->setCellValue('C'.$i, $salarie->getUserCreate()->getNom());
            $sheet->setCellValue('D'.$i, $salarie->getUserCreate()->getPrenom());
            $sheet->setCellValue('E'.$i, $salarie->getUserCreate()->getCin());
            $sheet->setCellValue('F'.$i, $salarie->getFile());
         
           $i++;
         }
         foreach(range('A','Z') as $columnID)
         {
             $sheet->getColumnDimension($columnID)->setAutoSize(true);
         }  
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
         $sheet->setTitle("Salarié");
        
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        
        // Create a Temporary file in the system
        $fileName = 'ETAT-COVID-FCZ.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);
        
        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }



}
