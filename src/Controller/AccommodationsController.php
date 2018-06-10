<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Accommodation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AccommodationsController extends Controller
{
    /**
     * @Route("/hebergements", name="accommodations")
     * @Template
     */
    public function index()
    {
        return ['accommodations' => $this->getDoctrine()->getManager()->getRepository('App:Accommodation')->findAll()];
    }

    /**
     * @Route("/nouvel-hebergement", name="accommodation_new")
     * @Template
     */
    public function newAccommodation(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newAccommodation = new Accommodation();

    	$newAccommodationForm = $this->createFormBuilder($newAccommodation)
            ->add('name', TextType::class)
            ->add('url', TextType::class, [
                'required' => false
            ])
            ->add('address', TextareaType::class)
            ->add('details', TextareaType::class)
            ->add('pricing', TextareaType::class, [
                'required' => false
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => true,
                'download_label' => true,
                'download_uri' => true,
                'image_uri' => true,
            ])
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

    	$newAccommodationForm->handleRequest($request);

        if ($newAccommodationForm->isSubmitted() && $newAccommodationForm->isValid()) {
	        $em->persist($newAccommodation);
	        $em->flush();

	        return $this->redirectToRoute('accommodations');
	    }
        return ['form' => $newAccommodationForm->createView()];
    }

    /**
     * @Route("/hebergement-{id}/editer", name="accommodation_edit")
	 * @ParamConverter("accommodation", class="App:Accommodation")
     * @Template
     */
    public function editAccommodation(Request $request, Accommodation $accommodation)
    {
    	$em = $this->getDoctrine()->getManager();

    	$accommodationForm = $this->createFormBuilder($accommodation)
            ->add('name', TextType::class)
            ->add('url', TextType::class, [
                'required' => false
            ])
            ->add('address', TextareaType::class)
            ->add('details', TextareaType::class)
            ->add('pricing', TextareaType::class, [
                'required' => false
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => true,
                'download_label' => '...',
                'download_uri' => true,
                'image_uri' => true,
            ])
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

    	$accommodationForm->handleRequest($request);

        if ($accommodationForm->isSubmitted() && $accommodationForm->isValid()) {
	        $em->flush();

	        return $this->redirectToRoute('accommodations');
	    }
        return [
        	'form' => $accommodationForm->createView(), 
        	'accommodation' => $accommodation
        ];
    }

    /**
     * @Route("/hebergement-{id}/supprimer", name="accommodation_delete")
	 * @ParamConverter("accommodation", class="App:Accommodation")
     * @Template
     */
    public function deleteAccommodation(Accommodation $accommodation)
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($accommodation);
    	$em->flush();

        return $this->redirectToRoute('accommodations');
    }
}
