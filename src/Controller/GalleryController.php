<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\GalleryType;
use App\Entity\Gallery;


class GalleryController extends Controller
{
    /**
     * @Route("/photos", name="gallery")
     * @Template
     */
    public function index()
    {
    	$em = $this->getDoctrine()->getManager();

        return ["galleries" => $em->getRepository('App:Gallery')->findAll()];
    }

    /**
     * @Route("/nouvelle-galerie", name="gallery_new")
     * @Template
     */
    public function newGallery(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newGallery = new Gallery();

        $newGalleryForm = $this->createForm(GalleryType::class, $newGallery)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

    	$newGalleryForm->handleRequest($request);

        if ($newGalleryForm->isSubmitted() && $newGalleryForm->isValid()) {
	        $newGallery->setCreatedAt(new \DateTime('now'));
	        $em->persist($newGallery);
	        $em->flush();

	        return $this->redirectToRoute('gallery_show', ['id' => $newGallery->getId()]);
	    }
        return ['form' => $newGalleryForm->createView()];
    }

    /**
     * @Route("/gallery-{id}", name="gallery_show")
     * @Template
     */
    public function showGallery(Gallery $gallery)
    {
        return ['gallery' => $gallery];
    }
}
