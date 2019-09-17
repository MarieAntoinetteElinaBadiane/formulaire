<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tarif;
use App\Entity\Compte;
use App\Entity\Transaction;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TransactionController extends AbstractController
{
/**
 * @Route("/api/envoi", name="envoi", methods={"POST"})
 * @Security("has_role('ROLE_Caissier')")
 */

 public function envoi (Request $request, EntityManagerInterface $entityManager)
 {

    $transaction = new Transaction();
        
        $connecte=$this->getUser();
        // $user = $this->getDoctrine()->getRepository(User::class)->find($connecte);
        $transaction->setUser($connecte);
        $transaction->setDateenvoi(new \Datetime());
        $annee = date('Y');
        $heure = date('H');
        $minute = date('i');
        $codeenvoi=$annee+$heure+$minute;
        $transaction->setCodeenvoi($codeenvoi);
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
             if($vo >= $values->getBorneInferieure() && $vo <= $values->getBorneSuperieure() ){

                $commission=$values->getValeur();
                $commi1= ($commission*10)/100;
                $commi2= ($commission*20)/100;
               $commi3=($commission*30)/100;
                $commi4=($commission*40)/100; 

     $transaction->setCommissionEnvoi($commi1);
     $transaction->setCommissionRetrait($commi2);
     $transaction->setCommissionEtat($commi3);
     $transaction->setCommissionAdmin($commi4);
       break;
        }
}
        
        $transaction->setCodeenvoi($codeenvoi);
        $is=$this->getUser();
        $transaction->setUser($is);
        $entityManager->persist($transaction);
        $entityManager->flush();
      $compte=$is->getCompte();
     
        if($compte->getSolde() > $transaction->getMontantenvoi() ) {
        $compte->setSolde($compte->getSolde() - $transaction->getMontantenvoi() + $commi1);

    
        $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($compte);
       $entityManager->flush();
       return new Response('Le transfert a été effectué avec succés.Voici le code : '.$transaction->getCodeenvoi());
        }
        else{
            return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');

 }
}




/**
 * @Route("/api/retrait", name="retrait", methods={"POST"})
 * @Security("has_role('ROLE_Caissier')")
 */

public function retrait (Request $request,  EntityManagerInterface $entityManager)
{   

  $transaction = new Transaction();
  $form=$this->createForm(TransactionType::class , $transaction);
  $form->handleRequest($request);
       $data=$request->request->all();
       $da=$this->getDoctrine()->getRepository(Transaction::class)->findOneBy(['codeenvoi'=> $data['codeenvoi']]);
       $da->setDateretrai(new \Datetime());
       $da->setCNIretrai($data['CNIretrai']);
       $da->setStatut($data['statut']);
        $entityManager->flush();
      return new Response('Votre retrait cest déroulé avec succés. Orange vous remercie: ');
       }
       
/**
* @Route("/api/transaction", name="transaction", methods={"GET"})
*@Security("has_role('ROLE_User')")
*/
    public function ListerTransaction(TransactionRepository $transactionRepository, SerializerInterface $serializer)
    {
        $trans = $transactionRepository->findAll();
        $transac = $serializer->serialize($trans, 'json', ['groups' => ['transaction']]);
        return new Response($transac, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}

