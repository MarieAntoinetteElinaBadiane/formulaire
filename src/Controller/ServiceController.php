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


class ServiceController extends FOSRestController
{

/**
* @Route("/register", name="app_register", methods={"POST"})
*/
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $data=$request->request->all();
        $file=$request->files->all()['imageFile'];

        $form->submit($data);

       
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(["Role_AdminWari"]);
            $user->setImageFile($file);
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            
            return new Response('Utilisateur ajouté',Response::HTTP_CREATED); 
    
    }

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

    $utilisateur->setRoles(["ROLE_CAISSIER"]);
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
//$utilisateur->setCompteUser($compte);
$compte->setPartenaire($part);
$entityManager->persist($compte);
$entityManager->flush();
return new Response('Les tables ont été ajouté',Response::HTTP_CREATED); 
}


/**
* @Route("/systeme", name="systeme", methods={"POST"})
*/
public function systeme (Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
{

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_CAISSIER"]);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
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
        $form = $this->createForm(CompteType::class, $compte);
        $data=$request->request->all();
        $form->submit($data);


$entityManager = $this->getDoctrine()->getManager();
$compte->setUserCompte($utilisateur);
$entityManager->persist($compte);
$entityManager->flush();
return new Response('Le deux tables ont été ajouté',Response::HTTP_CREATED); 
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
* @Route("/caissier", name="caissier", methods={"POST"})
*/
public function caissier(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
{

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();
    $file= $request->files->all()['imageFile'];
          $form->submit($data);
    
     $utilisateur->setRoles(['ROLE_CAISSIER']);
    $utilisateur->setImageFile($file);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($utilisateur);
    $entityManager->flush();
    return new Response('La personne a été ajouté',Response::HTTP_CREATED); 

    }

/**
* @Route("/admin", name="admin", methods={"POST"})
*/
public function admin(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
{

    $utilisateur = new User();
    $form=$this->createForm(UserType::class , $utilisateur);
    $form->handleRequest($request);
    $data=$request->request->all();

          $form->submit($data);

    $utilisateur->setRoles(["ROLE_SuperAdmin"]);

    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($utilisateur);
    $entityManager->flush();
    return new Response('La personne a été ajouté',Response::HTTP_CREATED); 

    }

/**
* @Route("/user", name="user", methods={"POST"})
*/
public function adduser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
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
    $form->submit($data);

    $utilisateur->setRoles(["ROLE_USER"]);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $utilisateur->setPartenaire($part);
    $entityManager->persist($utilisateur);
    $entityManager->flush();
    return new Response('Le partenaire a bien ajouté un user',Response::HTTP_CREATED); 
}


/**
* @Route("/adminuser", name="adminuser", methods={"POST"})
*/
public function addadmin(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
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
    $form->submit($data);
     $utilisateur->setRoles(['ROLE_ADMIN']);
    $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,
    $form->get('password')->getData()
        )
        );
    $entityManager = $this->getDoctrine()->getManager();
    $utilisateur->setPartenaire($part);
    $entityManager->persist($utilisateur);
    $entityManager->flush();
    return new Response('Le partenaire a bien ajouté admin du user',Response::HTTP_CREATED); 
}

/**
* @Route("/api/depot", name="depot", methods={"POST"})
* @Security("has_role('ROLE_CAISSIER') ")
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