<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TextBlock;
use App\Entity\Answer;
use App\Entity\Guest;
use App\Form\TextBlockType;
use App\Form\AnswerType;


class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        return [
            'texts' => $em->getRepository('App:TextBlock')->findAllByName(),
            'lastBlogArticle' => $em->getRepository('App:BlogArticle')->findBy([], ['createdAt' => 'DESC'])[0]
        ];
    }

    /**
     * @Route("/plan-acces", name="access_map")
     * @Template
     */
    public function accessMap()
    {
        $em = $this->getDoctrine()->getManager();
        return [
            'texts' => $em->getRepository('App:TextBlock')->findAllByName(),
        ];
    }

    /**
     * @Route("/contact", name="contact")
     * @Template
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
    	$form = $this->createFormBuilder()
            ->add('name', TextType::class, ['label' => "Qui êtes-vous ?", 'attr' => ['placeholder' => "Votre nom"]])
            ->add('email', EmailType::class, ['label' => "Si vous souhaitez être recontacté", 'attr' => ['placeholder' => "Votre e-mail ou votre numéro de tél."], 'required' => false])
            ->add('title', TextType::class, ['label' => "Votre message", 'attr' => ['placeholder' => "Titre"]])
            ->add('content', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => "Contenu"]])
            ->add('submit', SubmitType::class, array('label' => 'Envoyer le message', 'attr' => ['class' => "btn-primary"]))
            ->getForm();

    	$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = (new \Swift_Message('Mariage - Vous avez reçu un message de '.$form->get('name')->getData()))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($this->getParameter('mail_from'))
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig',
                        ['data' => $form->getData()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('contact');
	    }
        return [
        	'form' => $form->createView(),
            'texts' => $em->getRepository('App:TextBlock')->findAllByName()
        ];

        return [];
    }

    /**
     * @Route("/votre-reponse", name="answer")
     * @Template
     */
    public function answer(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $answer = new Answer();
        $guest1 = new Guest();
        $guest2 = new Guest();
        $answer->addGuest($guest1);
        $answer->addGuest($guest2);

        $answerForm = $this->createForm(AnswerType::class, $answer)
            ->add('submit', SubmitType::class, ['label' => 'Valider et envoyer votre réponse', 'attr' => ['class' => 'btn-success']])
        ;

        $answerForm->handleRequest($request);

        if ($answerForm->isSubmitted() && $answerForm->isValid()) {
            $answer->setCreatedAt(new \DateTime('now'));
            $em->persist($answer);
            $em->flush();
            $this->addFlash('success', 'Merci ! Les informations ont bien été enregistrées.');

            $message = (new \Swift_Message('Mariage - Vous avez reçu une nouvelle réponse !'))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($this->getParameter('mail_from'))
                ->setBody(
                    $this->renderView(
                        'emails/new_answer.html.twig',
                        ['answer' => $answer]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $answerForm->createView(),
            'texts' => $em->getRepository('App:TextBlock')->findAllByName()
        ];
    }
}
