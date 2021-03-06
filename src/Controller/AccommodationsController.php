<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Accommodation;
use App\Form\AccommodationType;
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
        $em = $this->getDoctrine()->getManager();
        return [
            'accommodations' => $em->getRepository('App:Accommodation')->findAll(),
            'texts' => $em->getRepository('App:TextBlock')->findAllByName(),
        ];
    }

    /**
     * @Route("/nouvel-hebergement", name="accommodation_new")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function newAccommodation(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newAccommodation = new Accommodation();

        $newAccommodationForm = $this->createForm(AccommodationType::class, $newAccommodation)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

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
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function editAccommodation(Request $request, Accommodation $accommodation)
    {
    	$em = $this->getDoctrine()->getManager();

        $accommodationForm = $this->createForm(AccommodationType::class, $accommodation)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

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
     * @IsGranted("ROLE_ADMIN")
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
