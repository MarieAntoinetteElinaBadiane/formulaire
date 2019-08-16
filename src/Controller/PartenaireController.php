<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Form\UserType;
use App\Form\DepotType;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PartenaireController extends FOSRestController
{
    /**
* @Route("/usercompte", name="usercompte", methods={"POST"})
*/
public function usercompte (Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{

    $part= new Partenaire();
                $form = $this->createForm(PartenaireType::class, $part);
                $data=$request->request->all();
                $form->submit($data);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($part);
                $entityManager->flush();

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_CAISSIER_PATENAIRE"]);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $utilisateur->setPartenaire($part);
    $entityManager->persist($utilisateur);
    $entityManager->flush();


        $compte = new Compte();
        $jour = date('d');
        $mois = date('m');
        $annee = date('Y');
        $heure = date('H');
        $minute = date('i');
        $seconde= date('s');
        $tata= date('ma');
        $numerocompte=$jour.$mois.$annee.$heure.$minute.$seconde.$tata;
        $compte->setNumerocompte($numerocompte);
        $compte->setSolde(0);
        $utilisateur->setCompte($compte);
        $form = $this->createForm(CompteType::class, $compte);
        $data=$request->request->all();
        $form->submit($data);


$entityManager = $this->getDoctrine()->getManager();
//$utilisateur->setCompteUser($compte);
$compte->setPartenaire($part);
$entityManager->persist($compte);
$entityManager->flush();
return new Response('Les tables ont été ajouté',Response::HTTP_CREATED); 
}

/**
* @Route("/adduser", name="adduser", methods={"POST"})
*/
public function adduser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
    
                $partenaire= new Partenaire();
                $form = $this->createForm(PartenaireType::class, $partenaire);
                $data=$request->request->all();
                $form->submit($data);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($partenaire);
                $entityManager->flush();

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_ADMIN"]);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
     $utilisateur->setPartenaire($partenaire);
    $entityManager->persist($utilisateur);
    $entityManager->flush();
    return new Response('Un utitilisateur est bien ajouté',Response::HTTP_CREATED); 
}

/**
* @Route("/api/user", name="user", methods={"POST"})
*@Security("has_role('ROLE_ADMIN') ")
*/
public function user(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
    

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_USER"]);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $connecte=$this->getUser()->getPartenaire();
    $partenaire = $this->getDoctrine()->getRepository(Partenaire::class)->find($connecte);
     $utilisateur->setPartenaire($partenaire);
    // $entityManager->persist($partenaire);
    $entityManager->persist($utilisateur);
    $entityManager->flush();
    return new Response('Un utitilisateur est bien ajouté',Response::HTTP_CREATED); 
}

/**
* @Route("/usersysteme", name="usersysteme", methods={"POST"})
*/
public function usersysteme (Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{

    $part= new Partenaire();
                $form = $this->createForm(PartenaireType::class, $part);
                $data=$request->request->all();
                $form->submit($data);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($part);
                $entityManager->flush();

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_USER"]);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $utilisateur->setPartenaire($part);
    $entityManager->persist($utilisateur);
    $entityManager->flush();


        $compte = new Compte();
        $jour = date('d');
        $mois = date('m');
        $annee = date('Y');
        $heure = date('H');
        $minute = date('i');
        $seconde= date('s');
        $tata= date('ma');
        $numerocompte=$jour.$mois.$annee.$heure.$minute.$seconde.$tata;
        $compte->setNumerocompte($numerocompte);
        $compte->setSolde(0);
        $form = $this->createForm(CompteType::class, $compte);
        $data=$request->request->all();
        $form->submit($data);


$entityManager = $this->getDoctrine()->getManager();
$compte->setPartenaire($part);
$entityManager->persist($compte);
$entityManager->flush();
return new Response('Les tables ont été ajouté',Response::HTTP_CREATED); 
}



/**
* @Route("/ajoutdestrois", name="ajoutdestrois", methods={"POST"})
*/
public function ajout(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{
    
                $part= new Partenaire();
                $form = $this->createForm(PartenaireType::class, $part);
                $data=$request->request->all();
                $form->submit($data);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($part);
                $entityManager->flush();

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_USER"]);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $utilisateur->setPartenaire($part);
    $entityManager->persist($utilisateur);
    $entityManager->flush();

return new Response('On a bien ajouté un utilisateur',Response::HTTP_CREATED); 
}

/**
* @Route("/api/depot", name="depot", methods={"POST"})
* @Security("has_role('ROLE_CAISSIER_PARTENAIRE') ")
*/
    public function argent(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {

        $values = json_decode($request->getContent());
        if ($values->montant >= 75000){
            

                $depot = new Depot();
                $depot->setDate(new \DateTime);
                $connecte=$this->getUser();
                $user = $this->getDoctrine()->getRepository(User::class)->find($connecte);
                $depot->setUser($user);
                $form = $this->createForm(DepotType::class, $depot);
                $data=$request->request->all();
                
                $form->submit($data);

                $compte = new Compte();
                
                    $compte = $this->getDoctrine()->getRepository(Compte::class)->
                    findOneBy(["numerocompte"=>$values->numerocompte]);

                $compte->setSolde($compte->getSolde()+ $values->montant);
                $depot->setCompte($compte);
                $form = $this->createForm(CompteType::class, $compte);
                $data=$request->request->all();
                $form->submit($data);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depot);
            $entityManager->persist($compte);
            $entityManager->flush();
        return new Response('Le depot sur votre compte sest bien passé',Response::HTTP_CREATED); 
        }
}
