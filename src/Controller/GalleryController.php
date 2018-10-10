<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\NewGalleryType;
use App\Form\GalleryType;
use App\Entity\Gallery;
use App\Entity\Photo;

/**
 * @Route("/photos")
 */
class GalleryController extends Controller
{
    /**
     * @Route("/", name="gallery")
     * @Template
     */
    public function index()
    {
    	$em = $this->getDoctrine()->getManager();

        return [
            "galleries" => $em->getRepository('App:Gallery')->findAll(),
            'legend' => $em->getRepository('App:TextBlock')->findOneByName('legend_photos')->getContent()
        ];
    }

    /**
     * @Route("/nouvel-album", name="gallery_new")
     * @Template
     */
    public function newGallery(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newGallery = new Gallery();

        $newGalleryForm = $this->createForm(NewGalleryType::class, $newGallery)
            ->add('submit', SubmitType::class, array('label' => 'Créer l\'album'))
        ;

    	$newGalleryForm->handleRequest($request);

        if ($newGalleryForm->isSubmitted() && $newGalleryForm->isValid()) {
	        $newGallery->setCreatedAt(new \DateTime('now'));
	        $em->persist($newGallery);
	        $em->flush();

	        return $this->redirectToRoute('gallery_upload_photos', ['id' => $newGallery->getId()]);
	    }
        return ['form' => $newGalleryForm->createView()];
    }

    /**
     * @Route("/album-{id}/ajouter-des-photos", name="gallery_upload_photos")
     * @Template
     */
    public function uploadPhotos(Gallery $gallery)
    {
        return ['gallery' => $gallery];
    }

    /**
     * @Route("/album-{id}/upload-process", name="gallery_upload_process")
     */
    public function processUpload(Request $request, Gallery $gallery)
    {
        $em = $this->getDoctrine()->getManager();
        // @todo access control
        // @todo input validation
        $files = $request->files->get('file');

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $photo = new Photo();
            $photo->setImageFile($file);
            $photo->setCreatedAt(new \DateTime('now'));
            $photo->setAuthor($gallery->getAuthor());
            $photo->setImageName($file->getClientOriginalName());
            $gallery->addPhoto($photo);
            $em->persist($photo);
        }

        $em->flush();

        return new JsonResponse([
            'success'     => true,
            'redirectUrl' => $this->generateUrl(
                'gallery_show',
                ['id' => $gallery->getId()]
            ),
        ]);
    }

    /**
     * @Route("/album-{id}", name="gallery_show")
     * @Template
     */
    public function showGallery(Gallery $gallery)
    {
        return ['gallery' => $gallery];
    }

    /**
     * @Route("/photo-{id}", name="gallery_photo_show")
     * @Template
     */
    public function showPhoto(Photo $photo)
    {
        return ['photo' => $photo];
    }

    /**
     * @Route("/album-{id}/editer", name="gallery_edit")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function editGallery(Request $request, Gallery $gallery)
    {
        $em = $this->getDoctrine()->getManager();

        $galleryForm = $this->createForm(GalleryType::class, $gallery)
            ->add('submit', SubmitType::class, array('label' => 'Valider les modifications'))
        ;

        $galleryForm->handleRequest($request);

        if ($galleryForm->isSubmitted() && $galleryForm->isValid()) {
            $em->flush();

            $this->addFlash('success', "Les modifications ont bien été enregistrées.");

            return $this->redirectToRoute('gallery_edit', ['id' => $gallery->getId()]);
        }

        return [
            'gallery' => $gallery,
            'form' => $galleryForm->createView()
        ];
    }

    /**
     * @Route("/photo-{id}/supprimer", name="gallery_photo_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletePhoto(Request $request, Photo $photo)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($photo);
            $em->flush();

            return new JsonResponse(['success' => true], 200);
        }
        return new JsonResponse([], 403);
    }

}
