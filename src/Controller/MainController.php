<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function index()
    {
        return [];
    }

    /**
     * @Route("/plan-acces", name="access_map")
     * @Template
     */
    public function accessMap()
    {
        return [];
    }

    /**
     * @Route("/contact", name="contact")
     * @Template
     */
    public function contact(Request $request)
    {
    	$form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', EmailType::class)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

    	$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        // TODO
	    }
        return [
        	'form' => $form->createView()
        ];

        return [];
    }

    /**
     * @Route("/votre-reponse", name="answer")
     * @Template
     */
    public function answer()
    {
        return [];
    }

    /**
     * @Route("/liste-de-mariage", name="wedding_list")
     * @Template
     */
    public function weddingList()
    {
        return [];
    }
}
