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
            'title' => $em->getRepository('App:TextBlock')->findOneByName('welcome')->getContent(),
            'legend' => $em->getRepository('App:TextBlock')->findOneByName('introduction')->getContent(),
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
            'legend' => $em->getRepository('App:TextBlock')->findOneByName('legend_access')->getContent(),
        ];
    }

    /**
     * @Route("/contact", name="contact")
     * @Template
     */
    public function contact(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    	$form = $this->createFormBuilder()
            ->add('name', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Votre nom"]])
            ->add('surname', TextType::class, ['label' => "Qui êtes-vous ?", 'attr' => ['placeholder' => "Votre prénom"]])
            ->add('email', EmailType::class, ['label' => "Votre e-mail ou votre numéro de téléphone (auquel vous souhaitez être recontacté)"])
            ->add('title', TextType::class, ['label' => "Votre message", 'attr' => ['placeholder' => "Titre du message"]])
            ->add('content', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => "Contenu du message"]])
            ->add('submit', SubmitType::class, array('label' => 'Envoyer le message'))
            ->getForm();

    	$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        // TODO
	    }
        return [
        	'form' => $form->createView(),
            'legend' => $em->getRepository('App:TextBlock')->findOneByName('legend_contact')->getContent(),
        ];

        return [];
    }

    /**
     * @Route("/votre-reponse", name="answer")
     * @Template
     */
    public function answer(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $answer = new Answer();
        $guest1 = new Guest();
        $guest2 = new Guest();
        $answer->addGuest($guest1);
        $answer->addGuest($guest2);

        $answerForm = $this->createForm(AnswerType::class, $answer)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
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
            'legend' => $em->getRepository('App:TextBlock')->findOneByName('legend_answer')->getContent(),
        ];
    }

    /**
     * @Route("/admin/textes", name="admin_text_blocks")
     * @Template
     */
    public function adminTextBlocks(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $textBlocks = $em->getRepository('App:TextBlock')->findBy([], ['id' => 'ASC']);
        if (empty($textBlocks)) {
            $init = [
                'welcome' => "Bienvenue !",
                'introduction' => "Présentation du site",
                'legend_blog' => "",
                'legend_photos' => "",
                'legend_access' => "",
                'legend_carpool' => "",
                'legend_accommodations' => "",
                'legend_contact' => "",
                'legend_answer' => "",
                'legend_wedding_list' => ""
            ];
            foreach ($init as $name => $content) {
                $textBlock = new TextBlock($name, $content);
                $em->persist($textBlock);
                $textBlocks[] = $textBlock;
            }
        }

        $form = $this->createFormBuilder($textBlocks)
            ->add('textBlocks', CollectionType::class, array(
                'entry_type' => TextBlockType::class,
                'entry_options' => array('label' => false),
                'data' => $textBlocks
            ))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer les modifications'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les modifications ont bien été enregistrées.');
        }


        return [
            'form' => $form->createView()
        ];
    }
}
