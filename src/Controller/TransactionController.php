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
 * @Route("/api/envoi", name="envoi", methods={"POST"})
 * @Security("has_role('ROLE_USER')")
 */

 public function envoi (Request $request, EntityManagerInterface $entityManager)
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

       $transaction->setCommissionEnvoi(($commission*10)/100);
       $transaction->setCommissionRetrait(($commission*20)/100);
       $transaction->setCommissionEtat(($commission*30)/100);
       $transaction->setCommissionAdmin(($commission*40)/100);
       break;
        }
}
       //$transaction->setUser($values);
       $transaction->setCodeenvoi($codeenvoi);
       $is=$this->getUser();
       $transaction->setUser($is);
       $entityManager->persist($transaction);
       $entityManager->flush();
       $compte= $this->getDoctrine()->getRepository(Compte::class)->findOneBy(['partenaire' => $is->getPartenaire()]);
//var_dump($is);
       
        if($compte->getSolde() > $transaction->getMontantenvoi() ){
       $mo= $compte->getSolde() - $transaction->getMontantenvoi() + $transaction->getCommissionEnvoi();
       $compte->setSolde($mo);

       var_dump($mo); die();
     
//        $typ = new Type();
//                 $form = $this->createForm(TypetransType::class, $typ);
//                 $form->handleRequest($request);
//                 $data = $request->request->all();
//                 $form->submit($data);
//                     $entityManager = $this->getDoctrine()->getManager();
//                     $entityManager->persist($tran);
//                     $entityManager->flush();

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
 * @Security("has_role('ROLE_USER')")
 */

public function retrait (Request $request, EntityManagerInterface $entityManager)
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

      $transaction->setCommissionEnvoi(($commission*10)/100);
      $transaction->setCommissionRetrait(($commission*20)/100);
      $transaction->setCommissionEtat(($commission*30)/100);
      $transaction->setCommissionAdmin(($commission*40)/100);
      break;
       }
}
      //$transaction->setUser($values);
      $transaction->setCodeenvoi($codeenvoi);
      $is=$this->getUser();
      $transaction->setUser($is);
      $entityManager->persist($transaction);
      $entityManager->flush();
      $compte= $this->getDoctrine()->getRepository(Compte::class)->findOneBy(['partenaire' => $is->getPartenaire()]);
//var_dump($is);
      
       if($compte->getSolde() < $transaction->getMontantenvoi() ){
      $mo= $compte->getSolde() + $transaction->getMontantenvoi() + $transaction->getCommissionRetrait();
      $compte->setSolde($mo);

      //var_dump($mo); die();

      $entityManager->persist($compte);
      $entityManager->flush();
      return new Response('Le transfert a été effectué avec succés.Voici le code : '.$transaction->getCodeenvoi());
       }
       else{
           return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');

}
}
}
