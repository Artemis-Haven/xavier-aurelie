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
    public function newCarpoolSearch(Request $request, \Swift_Mailer $mailer)
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

            $message = (new \Swift_Message('Mariage de Xavier et Aurélie - Votre annonce est publiée'))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($newCarpoolSearch->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/carpool_published.html.twig',
                        ['carpool' => $newCarpoolSearch, 'type' => 'search']
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            $this->addFlash('success', "Votre annonce est en ligne. Vous avec reçu un e-mail contenant toutes les informations nécessaires à son suivi.");

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
    public function newCarpoolProposal(Request $request, \Swift_Mailer $mailer)
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

            $message = (new \Swift_Message('Mariage de Xavier et Aurélie - Votre annonce est publiée'))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($newCarpoolProposal->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/carpool_published.html.twig',
                        ['carpool' => $newCarpoolProposal, 'type' => 'proposal']
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            
            $this->addFlash('success', "Votre annonce est en ligne. Vous avec reçu un e-mail contenant toutes les informations nécessaires à son suivi.");

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
    public function newCarpoolProposalAnswer(Request $request, \Swift_Mailer $mailer, CarpoolProposal $proposal)
    {
        return $this->newCarpoolAnswer($request, $mailer, null, $proposal);
    }

    /**
     * @Route("/recherche-{id}/reponse", name="carpool_search_answer_new", requirements={"id" = "\d+"})
     * @Template
     */
    public function newCarpoolSearchAnswer(Request $request, \Swift_Mailer $mailer, CarpoolSearch $search)
    {
        return $this->newCarpoolAnswer($request, $mailer, $search, null);
    }

    private function newCarpoolAnswer(Request $request, \Swift_Mailer $mailer, CarpoolSearch $search = null, CarpoolProposal $proposal = null)
    {
        $em = $this->getDoctrine()->getManager();
        $answer = new CarpoolAnswer();
        $carpool = ($search ?? $proposal);
        $maxSeats = $carpool->getNbrOfSeats() - $carpool->getReservedSeats();
        if ($search) {
            $answer->setSearch($search);
        } else {
            $answer->setProposal($proposal);
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

            $message = (new \Swift_Message('Mariage de Xavier et Aurélie - Vous avez une réponse à votre annonce'))
                ->setFrom($this->getParameter('mail_from'))
                ->setTo($carpool->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/carpool_answered.html.twig',
                        ['answer' => $answer, 'type' => ($search ? 'search' : 'proposal')]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            $this->addFlash('success', "Votre réponse a été envoyée par e-mail à ".$carpool->getAuthor().". Dès qu'iel l'aura accepté, vous en serez informé par e-mail.");

            if ($search) {
                return $this->redirectToRoute('carpool_search_show', ['id' => $search->getId()]);
            } else {
                return $this->redirectToRoute('carpool_proposal_show', ['id' => $proposal->getId()]);
            }
        }

        return [
            'form' => $newCarpoolAnswerForm->createView(),
            'carpool' => $carpool
        ];
    }

    /**
     * @Route("/reponse-{id}/accepter", name="carpool_answer_accept", requirements={"id" = "\d+"})
     */
    public function acceptCarpoolAnswer(\Swift_Mailer $mailer, CarpoolAnswer $answer)
    {
        $em = $this->getDoctrine()->getManager();
        $carpool = ($answer->getSearch() ?? $answer->getProposal());
        $answer->setStatus(CarpoolAnswer::STATUS_ACCEPTED);
        
        $carpool->setReservedSeats($carpool->getReservedSeats() + $answer->getNbrOfSeatsRequested());
        $em->flush();

        $message = (new \Swift_Message('Mariage de Xavier et Aurélie - '.($answer->getSearch() ? 'Votre proposition de covoiturage a été acceptée' : 'Votre demande de covoiturage a été acceptée')))
            ->setFrom($this->getParameter('mail_from'))
            ->setTo($answer->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/carpool_accepted.html.twig',
                    ['answer' => $answer, 'type' => ($answer->getSearch() ? 'search' : 'proposal')]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);

        $this->addFlash('success', "Vous avez accepté la proposition de ".$answer->getAuthor().". Un e-mail lui a été envoyé pour l'en informer.");

        if ($answer->getSearch()) {
            return $this->redirectToRoute('carpool_search_manage', ['id' => $carpool->getId()]);
        } else {
            return $this->redirectToRoute('carpool_proposal_manage', ['id' => $carpool->getId()]);
        }
    }

    /**
     * @Route("/reponse-{id}/refuser", name="carpool_answer_reject", requirements={"id" = "\d+"})
     */
    public function rejectCarpoolAnswer(\Swift_Mailer $mailer, CarpoolAnswer $answer)
    {
        $em = $this->getDoctrine()->getManager();
        $carpool = ($answer->getSearch() ?? $answer->getProposal());
        $answer->setStatus(CarpoolAnswer::STATUS_REJECTED);
        
        $em->flush();

        $message = (new \Swift_Message('Mariage de Xavier et Aurélie - '.($answer->getSearch() ? 'Votre proposition de covoiturage a été rejetée' : 'Votre demande de covoiturage a été rejetée')))
            ->setFrom($this->getParameter('mail_from'))
            ->setTo($answer->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/carpool_rejected.html.twig',
                    ['answer' => $answer, 'type' => ($answer->getSearch() ? 'search' : 'proposal')]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        
        $this->addFlash('danger', "Vous avez refusé la proposition de ".$answer->getAuthor().". Un e-mail lui a été envoyé pour l'en informer.");

        if ($answer->getSearch()) {
            return $this->redirectToRoute('carpool_search_manage', ['id' => $carpool->getId()]);
        } else {
            return $this->redirectToRoute('carpool_proposal_manage', ['id' => $carpool->getId()]);
        }
    }

}
