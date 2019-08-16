<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tarif;
use App\Entity\Compte;
use App\Form\LoginType;
use App\Entity\Partenaire;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Entity\User as AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
{
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
$user->setUsername->codage($values->username);
$user->setPassword($passwordEncoder->encodePassword($user, $values->password));
$user->setRoles(['ROLE_SUPER']);
$user->setPhoto->codage($values->photo);
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
* @Route("/login", name="login", methods={"POST"})
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
        // var_dump($utili); die();
        $isValid = $passwordEncoder->isPasswordValid($utili,$data['password']);
        if (!$isValid) {
                throw $this->createNotFoundException('User and Password Not Found');
                }
                else {
                        if ($utili->getStatut() == null || $utili->getStatut() == "actif") {
                                $token = $JWTEncoder->encode([
                                        'username' => $user->getUsername(),
                                        'exp' => time() + 3600 // 1 hour expiration
                                ]);
                        
                                return new JsonResponse(['token' => $token]);
                
        }
        elseif($utili->getStatut() == "bloqué") {
                throw $this->createNotFoundException('User and Password Not Found');
        }
                }
      //  $utili->getStatut();
        

                        
                // var_dump($utili->getStatut()); die();








         
}

/**
 * @Route("/api/transaction", name="transaction", methods={"POST"})
 * @Security("has_role('ROLE_USER')")
 */

 public function transaction (Request $request, EntityManagerInterface $entityManager)
 {

    $transaction = new Transaction();
        
        $connecte=$this->getUser();
        $user = $this->getDoctrine()->getRepository(User::class)->find($connecte);
        $transaction->setUser($user);
        $transaction->setDateenvoi(new \Datetime());
        $annee = date('Y');
        $heure = date('H');
        $minute = date('i');
        $codeenvoi=$annee+$heure+$minute;
        $transaction->setCodeenvoi($codeenvoi);
        $transaction->setDateretrai(new \Datetime());
        $form=$this->createForm(TransactionType::class , $transaction);
        $form->handleRequest($request);
        $data=$request->request->all();
         $form->submit($data);

         $vo= $form->get('montantenvoi')->getData();
         $tarif= $this->getDoctrine()->getRepository(Tarif::class)->findAll();
         foreach($tarif as $values){
             $values->getBorneInferieure();
             $values->getBorneSuperieure();
             $values->getValeur();
             if($vo>=$values->getBorneInferieure() && $vo<=$values->getBorneSuperieure() ){
     $vop=$values->getValeur();
             }
          }

       $transaction->setCommissionEnvoi(($vop*10)/100);
       $transaction->setCommissionRetrait(($vop*20)/100);
       $transaction->setCommissionEtat(($vop*30)/100);
       $transaction->setCommissionAdmin(($vop*40)/100);
       $transaction->setCodeenvoi($codeenvoi);
        $is=$this->getUser();
       $transaction->setUser($is);
        $entityManager->persist($transaction);
        $entityManager->flush();

        $comp=$this->getDoctrine()->getRepository(Compte::class)->findOneBy(['partenaire' => $is->getPartenaire()->getId()]);

//var_dump($is);
        if($comp->getSolde() >$transaction->getMontantenvoi() ){
       $mo= $comp->getSolde()-$transaction->getMontantenvoi()+$transaction->getCommissionEnvoi();
    

       $comp->setSolde($mo);
     
       $entityManager->persist($comp);
       $entityManager->flush();
       return new Response('Le transfert a été effectué avec succés.Voici le code : '.$transaction->getCodeenvoi());
        }else{
            return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');

 }
}
}