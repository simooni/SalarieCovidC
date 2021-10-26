<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use PDO;
class RegistrationController extends AbstractController
{
    /**
     * @Route("/testreg", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/pssw", name="pssw", methods={"GET","POST"})
    //  */
    // public function pssw(UserPasswordEncoderInterface $passwordEncoder): Response
    // {
    //     $user = new User();
    //     $sql="SELECT id , cin FROM  user" ;

    //     $stmt = $this->getDoctrine()->getConnection('default')->prepare($sql);
    //     $stmt->execute();
    //     $detail_user = $stmt->fetchAll(PDO::FETCH_OBJ); 
         
            
    //     foreach($detail_user as $deman){
    //             if($deman->id>2){
    //             $ps = $passwordEncoder->encodePassword( $user,$deman->cin);
    //             $sql_upda = "  UPDATE `user` SET`password`= '$ps' WHERE id=$deman->id ";
                                    
        
    //                 $stm_upda = $this->getDoctrine()->getConnection('default')->prepare($sql_upda);
    //                 $stm_upda->execute();
    //             // UPDATE `user` SET`pasw`=md5('test') WHERE id=3
    //         }
    //      }
    //      dd('ok');
    // }
}
