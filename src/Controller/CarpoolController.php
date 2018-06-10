<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\CarpoolSearch;
use App\Entity\CarpoolProposal;
use App\Form\CarpoolSearchType;
use App\Form\CarpoolProposalType;

class CarpoolController extends Controller
{
    /**
     * @Route("/covoiturage", name="carpool")
     * @Template
     */
    public function index()
    {
    	$em = $this->getDoctrine()->getManager();

        return [
        	'carpoolSearches' => $em->getRepository('App:CarpoolSearch')->findAll(),
        	'carpoolProposals' => $em->getRepository('App:CarpoolProposal')->findAll()
        ];
    }

    /**
     * @Route("/recherche/nouvelle-annonce", name="carpool_search_new")
     * @Template
     */
    public function newCarpoolSearch(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newCarpoolSearch = new CarpoolSearch();

        $newCarpoolSearchForm = $this->createForm(CarpoolSearchType::class, $newCarpoolSearch)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

    	$newCarpoolSearchForm->handleRequest($request);

        if ($newCarpoolSearchForm->isSubmitted() && $newCarpoolSearchForm->isValid()) {
	        $newCarpoolSearch->setCreatedAt(new \DateTime('now'));
	        $em->persist($newCarpoolSearch);
	        $em->flush();

	        return $this->redirectToRoute('carpool_search_show', ['id' => $newCarpoolSearch->getId()]);
	    }
        return [
        	'form' => $newCarpoolSearchForm->createView(),
        ];
    }

    /**
     * @Route("/recherche/annonce-{id}", name="carpool_search_show")
     * @Template
     */
    public function showCarpoolSearch(Request $request, CarpoolSearch $carpoolSearch)
    {

        return ['search' => $carpoolSearch];
    }

    /**
     * @Route("/offre/nouvelle-annonce", name="carpool_proposal_new")
     * @Template
     */
    public function newCarpoolProposal(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newCarpoolProposal = new CarpoolProposal();

        $newCarpoolProposalForm = $this->createForm(CarpoolProposalType::class, $newCarpoolProposal)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

    	$newCarpoolProposalForm->handleRequest($request);

        if ($newCarpoolProposalForm->isSubmitted() && $newCarpoolProposalForm->isValid()) {
	        $newCarpoolProposal->setCreatedAt(new \DateTime('now'));
	        $em->persist($newCarpoolProposal);
	        $em->flush();

	        return $this->redirectToRoute('carpool_proposal_show', ['id' => $newCarpoolProposal->getId()]);
	    }
        return [
        	'form' => $newCarpoolProposalForm->createView(),
        ];
    }

    /**
     * @Route("/offre/annonce-{id}", name="carpool_proposal_show")
     * @Template
     */
    public function showCarpoolProposal(Request $request, CarpoolProposal $carpoolProposal)
    {

        return ['proposal' => $carpoolProposal];
    }

}
