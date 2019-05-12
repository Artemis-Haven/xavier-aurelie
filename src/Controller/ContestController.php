<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Gallery;
use App\Form\GalleryForContestType;

/**
 * @Route("/concours")
 */
class ContestController extends AbstractController
{
    /**
     * @Route("/", name="contest_login")
     * @Template
     */
    public function login(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
	
    	$teamId = (int) $this->get('session')->get('teamId');
    	$team = $em->getRepository('App:Gallery')->findOneBy(['contest' => true, 'id' => $teamId]);
    	if ($team) {
    		return $this->redirectToRoute('gallery_show', ['id' => $teamId]);
    	}

		$formBuilder = $this->createFormBuilder()
            ->add('code', Type\PasswordType::class, [
                'mapped' => false,
                'label' => "Saisissez le code pour accéder à votre album :",
                'required' => true
            ]);

        $form = $formBuilder
            ->add('save', Type\SubmitType::class, array('label' => 'Connexion'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    		$code = strtolower($form->get('code')->getData());
    		$team = $em->getRepository('App:Gallery')->findOneByCode($code);
    		if ($team) {
    			$this->get('session')->set('teamId', $team->getId());
	    		return $this->redirectToRoute('gallery_show', ['id' => $team->getId()]);
    		}
        }

        return [
            'texts' => $em->getRepository('App:TextBlock')->findAllByName(),
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/logout", name="contest_logout")
     * @Template
     */
    public function logout()
    {
    	$teamId = (int) $this->get('session')->set('teamId', null);

        return $this->redirectToRoute('contest_login');
    }

    /**
     * @Route("/admin/gestion", name="contest_admin_manage_teams")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function manageTeams()
    {
    	$em = $this->getDoctrine()->getManager();

        return [
        	'teams' => $em->getRepository('App:Gallery')->findBy(['contest' => true], ['title' => 'ASC'])
        ];
    }

    /**
     * @Route("/nouvelle-equipe", name="contest_admin_new_team")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function newTeam(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newGallery = new Gallery();

        $newGalleryForm = $this->createForm(GalleryForContestType::class, $newGallery)
            ->add('submit', Type\SubmitType::class, array('label' => 'Créer l\'équipe'))
        ;

    	$newGalleryForm->handleRequest($request);

        if ($newGalleryForm->isSubmitted() && $newGalleryForm->isValid()) {
        	$newGallery->setContest(true);
	        $newGallery->setCreatedAt(new \DateTime('now'));
	        $newGallery->setTitle("Concours photos - ".$newGalleryForm->get('author')->getData());
	        $newGallery->setCode(strtolower($newGallery->getCode()));
	        $em->persist($newGallery);
	        $em->flush();

	        return $this->redirectToRoute('contest_admin_manage_teams');
	    }
        return [
        	'form' => $newGalleryForm->createView()
        ];
    }

    /**
     * @Route("/admin", name="contest_admin_view_teams")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function viewTeams()
    {
    	$em = $this->getDoctrine()->getManager();

        return [
        	'teams' => $em->getRepository('App:Gallery')->findBy(['contest' => true], ['title' => 'ASC'])
        ];
    }
}
