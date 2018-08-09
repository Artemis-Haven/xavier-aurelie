<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\CarpoolSearch;
use App\Entity\CarpoolProposal;
use App\Entity\CarpoolAnswer;
use App\Form\CarpoolSearchType;
use App\Form\CarpoolProposalType;
use App\Form\CarpoolAnswerType;

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
            ->add('submit', SubmitType::class, array('label' => "Publier l'annonce"))
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
     * @Route("/recherche/annonce-{id}", name="carpool_search_show", requirements={"id" = "\d+"})
     * @Template
     */
    public function showCarpoolSearch( CarpoolSearch $carpoolSearch)
    {

        return ['search' => $carpoolSearch];
    }

    /**
     * @Route("/recherche/annonce-{id}/gestion", name="carpool_search_manage", requirements={"id" = "\d+"})
     * @Template("carpool/manage_carpool.html.twig")
     */
    public function manageCarpoolSearch(CarpoolSearch $carpoolSearch)
    {
        return [
            'carpool' => $carpoolSearch,
            'type' => 'search'
        ];
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
            ->add('submit', SubmitType::class, array('label' => "Publier l'annonce"))
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
     * @Route("/offre/annonce-{id}", name="carpool_proposal_show", requirements={"id" = "\d+"})
     * @Template
     */
    public function showCarpoolProposal(CarpoolProposal $carpoolProposal)
    {

        return ['proposal' => $carpoolProposal];
    }

    /**
     * @Route("/offre/annonce-{id}/gestion", name="carpool_proposal_manage", requirements={"id" = "\d+"})
     * @Template("carpool/manage_carpool.html.twig")
     */
    public function manageCarpoolProposal(CarpoolProposal $carpoolProposal)
    {
        return [
            'carpool' => $carpoolProposal,
            'type' => 'proposal'
        ];
    }

    /**
     * @Route("/offre-{id}/reponse", name="carpool_proposal_answer_new", requirements={"id" = "\d+"})
     * @Template
     */
    public function newCarpoolProposalAnswer(Request $request, CarpoolProposal $proposal)
    {
        return $this->newCarpoolAnswer($request, null, $proposal);
    }

    /**
     * @Route("/recherche-{id}/reponse", name="carpool_search_answer_new", requirements={"id" = "\d+"})
     * @Template
     */
    public function newCarpoolSearchAnswer(Request $request, CarpoolSearch $search)
    {
        return $this->newCarpoolAnswer($request, $search, null);
    }

    private function newCarpoolAnswer(Request $request, CarpoolSearch $search = null, CarpoolProposal $proposal = null)
    {
        $em = $this->getDoctrine()->getManager();
        $answer = new CarpoolAnswer();
        $maxSeats = 1;
        if ($search) {
            $answer->setSearch($search);
            $maxSeats = $search->getNbrOfSeats() - $search->getReservedSeats();
        } else {
            $answer->setProposal($proposal);
            $maxSeats = $proposal->getNbrOfSeats() - $proposal->getReservedSeats();
        }

        $newCarpoolAnswerForm = $this->createForm(CarpoolAnswerType::class, $answer, [
            'type' => ($search ? 'search' : 'proposal'),
            'maxSeats' => $maxSeats
        ])
            ->add('submit', SubmitType::class, array('label' => "Envoyer la réponse"))
        ;

        $newCarpoolAnswerForm->handleRequest($request);

        if ($newCarpoolAnswerForm->isSubmitted() && $newCarpoolAnswerForm->isValid()) {
            $answer->setCreatedAt(new \DateTime('now'));
            $em->persist($answer);
            $em->flush();

            if ($search) {
                return $this->redirectToRoute('carpool_search_show', ['id' => $search->getId()]);
            } else {
                return $this->redirectToRoute('carpool_proposal_show', ['id' => $proposal->getId()]);
            }
        }

        return [
            'form' => $newCarpoolAnswerForm->createView(),
            'carpool' => ($search ?? $proposal)
        ];
    }

    /**
     * @Route("/reponse-{id}/accepter", name="carpool_answer_accept", requirements={"id" = "\d+"})
     */
    public function acceptCarpoolAnswer(CarpoolAnswer $answer)
    {
        $em = $this->getDoctrine()->getManager();
        $answer->setStatus(CarpoolAnswer::STATUS_ACCEPTED);
        if ($answer->getSearch()) {
            $answer->getSearch()->setReservedSeats($answer->getSearch()->getReservedSeats() + $answer->getNbrOfSeatsRequested());
            $em->flush();
            return $this->redirectToRoute('carpool_search_manage', ['id' => $answer->getSearch()->getId()]);
        } else {
            $answer->getProposal()->setReservedSeats($answer->getProposal()->getReservedSeats() + $answer->getNbrOfSeatsRequested());
            $em->flush();
            return $this->redirectToRoute('carpool_proposal_manage', ['id' => $answer->getProposal()->getId()]);
        }
    }

    /**
     * @Route("/reponse-{id}/refuser", name="carpool_answer_reject", requirements={"id" = "\d+"})
     */
    public function rejectCarpoolAnswer(CarpoolAnswer $answer)
    {
        $em = $this->getDoctrine()->getManager();
        $answer->setStatus(CarpoolAnswer::STATUS_REJECTED);
        if ($answer->getSearch()) {
            $em->flush();
            return $this->redirectToRoute('carpool_search_manage', ['id' => $answer->getSearch()->getId()]);
        } else {
            $em->flush();
            return $this->redirectToRoute('carpool_proposal_manage', ['id' => $answer->getProposal()->getId()]);
        }
    }

}
