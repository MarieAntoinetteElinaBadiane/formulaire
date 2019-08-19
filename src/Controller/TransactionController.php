<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tarif;
use App\Entity\Compte;
use App\Entity\Transaction;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{
/**
 * @Route("/api/envoi/{id}", name="envoi", methods={"POST"})
 * @Security("has_role('ROLE_USER')")
 */

 public function envoi (Request $request, $id, EntityManagerInterface $entityManager)
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
      $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);

       
      if($compte->getSolde() > $transaction->getMontantenvoi() ){
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
 * @Route("/api/retrait/{id}", name="retrait", methods={"POST"})
 * @Security("has_role('ROLE_USER')")
 */

public function retrait (Request $request, $id, EntityManagerInterface $entityManager)
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
      //$transaction->setUser($values);
      $transaction->setCodeenvoi($codeenvoi);
      $is=$this->getUser();
      $transaction->setUser($is);
      $entityManager->persist($transaction);
      $entityManager->flush();
    $compte=$is->getCompte();
    $compte = $this->getDoctrine()->getRepository(Compte::class)->find($id);
      
       if($compte->getSolde() > $transaction->getMontantenvoi() ){
        $compte->setSolde($compte->getSolde() + $transaction->getMontantenvoi() + $commi2);


      $entityManager->persist($compte);
      $entityManager->flush();
      return new Response('Votre retrait cest déroulé avec succés. Orange vous remercie: ');
       }
       else{
           return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');

}
}
}
