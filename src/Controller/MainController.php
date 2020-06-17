<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MainController extends AbstractController
{   

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/image", name="image")
     */
    public function recognise_faces_image()
    {
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);

        $utilisateurs = $repository->findAll();

        return $this->render('main/image.html.twig', [
            'usernames' => getUsernames(),
            'utilisateurs' => $utilisateurs
        ]);
    }

    /**
     * @Route("/video", name="video")
     */
    public function recognise_faces_video()
    {
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);

        $utilisateurs = $repository->findAll();
        
        return $this->render('main/video.html.twig', [
            'usernames' => getUsernames(),
            'utilisateurs' => $utilisateurs
        ]);
    }

    /**
     * @Route("/table-utilisateur", name="table-utilisateur")
     */
    public function table_utilisateur()
    {
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);

        $utilisateurs = $repository->findAll();

        return $this->render('main/table-utilisateur.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * @Route("/add-user", name="add-user")
     */
    public function addUser(Request $request, ObjectManager $objectManager)
    {

        $utilisateur = new Utilisateur();
        $utilisateurFrom = $this->createFormBuilder($utilisateur)
                                ->add('cin', TextType::class, [
                                    'attr' => [
                                        'class' => "form-control",
                                        'data-parsley-minlength' => '7',
                                        'placeholder' => 'Min 7 chars',
                                        'value' => ''
                                    ]
                                ])
                                ->add('nom', TextType::class, [
                                    'attr' => [
                                        'class' => "form-control",
                                        'data-parsley-pattern' => '^[a-zA-Z]+$',
                                        'data-parsley-trigger' => 'keyup',
                                        'value' => ''
                                    ]
                                ])
                                ->add('prenom', TextType::class, [
                                    'attr' => [
                                        'class' => "form-control",
                                        'data-parsley-pattern' => '^[a-zA-Z]+$',
                                        'data-parsley-trigger' => 'keyup',
                                        'value' => ''
                                    ]
                                ])
                                ->add('age', NumberType::class, [
                                    'attr' => [
                                        'class' => "form-control",
                                        'min' => '0',
                                        'max' => '100',
                                        'value' => ''
                                    ]
                                ])
                                ->add('adresse', TextType::class, [
                                    'attr' => [
                                        'class' => "form-control",
                                        'data-parsley-minlength' => '18',
                                        'placeholder' => 'Min 18 chars',
                                        'value' => ''
                                    ]
                                ])
                                ->add('save', SubmitType::class, [
                                    'label' => 'Enregistrer',
                                    'attr' => [
                                        'class' => "btn btn-space btn-primary"
                                    ]
                                ])
                                ->add('imageFile1', VichImageType::class, [
                                    'attr' => [
                                        'class' => "custom-file-input custom-control",
                                    ]
                                ])
                                ->add('imageFile2', VichImageType::class, [
                                    'attr' => [
                                        'class' => "custom-file-input custom-control",
                                    ]
                                ])
                                ->add('imageFile3', VichImageType::class, [
                                    'attr' => [
                                        'class' => "custom-file-input custom-control",
                                    ]
                                ])
                                ->getForm();

        $utilisateurFrom->handleRequest($request);

        if ($utilisateurFrom->isSubmitted() && $utilisateurFrom->isValid()) {

            $foundUtilisateur = $objectManager->getRepository(Utilisateur::class)->findOneByCin($utilisateur->getCin());
            
            if (is_null($foundUtilisateur)) {
                $utilisateur->setImage1('1.jpg');
                $utilisateur->setImage2('2.jpg');
                $utilisateur->setImage3('3.jpg');
                $objectManager->persist($utilisateur);
                $objectManager->flush();

                $savedUtilisateur = $objectManager->getRepository(Utilisateur::class)->findOneByCin($utilisateur->getCin());
                $savedUtilisateur->setImage1('1.jpg');
                $savedUtilisateur->setImage2('2.jpg');
                $savedUtilisateur->setImage3('3.jpg');
                $objectManager->persist($savedUtilisateur);
                $objectManager->flush();

                createUtilisateurFolderImages($utilisateur->getCin());
            } else {
                    $nom = $utilisateur->getNom();
                    $prenom = $utilisateur->getPrenom();
                    $age = $utilisateur->getAge();
                    $adresse = $utilisateur->getAdresse();

                    if(is_null($nom) === false && is_null($prenom) === false && is_null($age) === false && is_null($adresse) === false) {
                    $foundUtilisateur->setNom($nom);
                    $foundUtilisateur->setPrenom($prenom);
                    $foundUtilisateur->setAge($age);
                    $foundUtilisateur->setAdresse($adresse);

                    $objectManager->persist($foundUtilisateur);
                    $objectManager->flush();
                }
            }
        }

        return $this->render('main/addUser.html.twig', [
            'utilisateurFrom' => $utilisateurFrom->createView()
        ]);

        /*
        dump($request);
        
        if ($request->request->count() > 0) {
            $utilisateur = new Utilisateur();
            $utilisateur->setCin($request->request->get('cin'))
                        ->setNom($request->request->get('nom'))
                        ->setPrenom($request->request->get('prenom'))
                        ->setAge($request->request->get('age'))
                        ->setAdresse($request->request->get('adresse'));

            $objectManager->persist($article);
            $objectManager->flush();
        }
        */
    }

}

