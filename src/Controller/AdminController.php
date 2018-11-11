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

        $formBuilder = $this->createFormBuilder($textBlocks)
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
            ->add('brideImage', Type\FileType::class, [
                'mapped' => false,
                'label' => "Changer la photo de la mariée ?",
                'required' => false
            ])
            ->add('groomImage', Type\FileType::class, [
                'mapped' => false,
                'label' => "Changer la photo du marié ?",
                'required' => false
            ]);
        
        for ($i=1; $i <= 12; $i++) { 
            $formBuilder->add('witness'.$i.'Image', Type\FileType::class, [
                'mapped' => false,
                'label' => "Changer la photo du témoin ".$i." ?",
                'required' => false
            ]);
        }

        $form = $formBuilder
            ->add('save', Type\SubmitType::class, array('label' => 'Enregistrer les modifications'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        	$indexFile = $form['indexImage']->getData();
            if ($indexFile && in_array(strtolower($indexFile->guessExtension()), ['jpg', 'jpeg'])) {
                $test = $indexFile->move('images/uploads', 'index.jpg');
            }
            $brideFile = $form['brideImage']->getData();
            if ($brideFile && in_array(strtolower($brideFile->guessExtension()), ['jpg', 'jpeg'])) {
                $brideFile->move('images/uploads', 'bride.jpg');
            }
            $groomFile = $form['groomImage']->getData();
            if ($groomFile && in_array(strtolower($groomFile->guessExtension()), ['jpg', 'jpeg'])) {
                $groomFile->move('images/uploads', 'groom.jpg');
            }
            for ($i=1; $i <= 12; $i++) {
                $witnessFile = $form['witness'.$i.'Image']->getData();
                if ($witnessFile && in_array(strtolower($witnessFile->guessExtension()), ['jpg', 'jpeg'])) {
                    $witnessFile->move('images/uploads', 'witness'.$i.'.jpg');
                }
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
