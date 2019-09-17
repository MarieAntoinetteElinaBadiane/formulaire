<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tarif;
use App\Entity\Compte;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
{

/**
 * @Route("/bloquer", name="bloquer", methods={"POST"})
 */
public function bloquer(Request $request, EntityManagerInterface $entityManager){
        $data=$request->request->all();
         $vava=$this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=> $data['username']]);
         $vava->setStatut("bloquer");
         $entityManager->flush();
}


/**
* @Route("/authentification", name="authentification", methods={"POST"})
*/
public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
{
$sms='message';
$status='status';

$values = json_decode($request->getContent());
if(isset($values->username,$values->password)) {

$user = new User();
$user->setNom->codage($values->nom);
$user->setPrenom->codage($values->prenom);
$user->setStatut->codage($values->statut);
$file=$request->files->all()['imageFile'];
$user->setUsername->codage($values->username);
$user->setPassword($passwordEncoder->encodePassword($user, $values->password));
$user->setRoles(['ROLE_SuperAdmin']);
$user->setImageFile($file);
$entityManager = $this->getDoctrine()->getManager();
$entityManager->persist($user);
$entityManager->flush();

$data = [
$status => 201,
$sms => 'Les propriétés du user ont été bien ajouté'
];
return new JsonResponse($data, 201);
}
$data = [
$status => 500,
$sms => 'Vous devez renseigner les clés username et password'
];
return new JsonResponse($data, 500);
}

/**
* @Route("/api/login_check", name="login_check", methods={"POST"})
* @param Request $request
* @param JWTEncoderInterface $JWTEncoder
* @return JsonResponse
* @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
*/
public function login(Request $request, JWTEncoderInterface $JWTEncoder, UserPasswordEncoderInterface $passwordEncoder)
{
  $user = new User();
        $form=$this->createForm(LoginType::class , $user);
        $form->handleRequest($request);
        $data=$request->request->all();
         $form->submit($data);
         $utili=$this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=> $data['username']]);
        $isValid = $passwordEncoder->isPasswordValid($utili,$data['password']);
     
        if (!$isValid) {
                throw $this->createNotFoundException('User and Password Not Found');
                }
                else {
                        if ($utili->getStatut() == null || $utili->getStatut() == "actif") {
                                $token = $JWTEncoder->encode([
                                        'username' => $user->getUsername(),
                                        'roles' => $user->getRoles(),
                                        'exp' => time() + 3600 // 1 hour expiration
                                ]);
                        
                                return new JsonResponse(['token' => $token]);
                
        }
        elseif($utili->getStatut() == "bloquer") {
                return $this->json(
                        [
                                'Message'=>'Vous Etes Bloquer'

                        ]
                );
        }
                }
      

         
}



}