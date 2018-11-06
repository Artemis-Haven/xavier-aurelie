<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\TextBlockType;
use App\Entity\TextBlock;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @Template
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('App:BlogArticle')->findAll();
        $accommodations = $em->getRepository('App:Accommodation')->findAll();

        return [
        	'blog' => $blog,
        	'accommodations' => $accommodations
        ];
    }

    /**
     * @Route("/admin/textes", name="admin_text_blocks")
     * @Template
     */
    public function textBlocks(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $textBlocks = $em->getRepository('App:TextBlock')->findBy([], ['id' => 'ASC']);
        if (empty($textBlocks)) {
            $init = [
                'welcome' => "Bienvenue !",
                'introduction' => "Présentation du site",
                'home_textarea' => "Texte de la page d'accueil",
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
            ->add('textBlocks', Type\CollectionType::class, array(
                'entry_type' => TextBlockType::class,
                'entry_options' => array('label' => false),
                'data' => $textBlocks
            ))
            ->add('indexImage', Type\FileType::class, [
            	'mapped' => false,
            	'label' => "Changer l'image de la page d'accueil ?",
            	'required' => false
            ])
            ->add('save', Type\SubmitType::class, array('label' => 'Enregistrer les modifications'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	$file = $form['indexImage']->getData();
        	if ($file && $file->guessExtension() == 'png') {
        		$file->move('images', 'index.png');
        	}
            $em->flush();
            $this->addFlash('success', 'Les modifications ont bien été enregistrées.');
        }


        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/admin/reponses", name="admin_answers")
     * @Template
     */
    public function answers()
    {
        $em = $this->getDoctrine()->getManager();
        $answers = $em->getRepository('App:Answer')->findAll();
 
        return [
            'answers' => $answers,
        ];
    }

    /**
     * @Route("/admin/reponses/exporter", name="admin_answers_export")
     */
    public function exportAnswers()
    {
        $em = $this->getDoctrine()->getManager();
        $answers = $em->getRepository('App:Answer')->findAll();
        $csv = join(';', ["Invité", "Cérémonie", "Brunch", "Domaine de Sarson"]).PHP_EOL;
        foreach ($answers as $answer) {
            foreach ($answer->getGuests() as $guest) {
                $csv .= join(';', [
                    (string) $guest,
                    $guest->getAttendCeremony(),
                    $guest->getAttendBrunch(),
                    $guest->getAccommodationOnSite(),
                ]).PHP_EOL;
            }
        }

        return new Response($csv, 200, array(
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="export-guests.csv"',
        ));
    }
}
