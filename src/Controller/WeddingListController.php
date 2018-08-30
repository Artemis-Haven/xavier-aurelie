<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use App\Entity\ListItem;
use App\Form\ListItemType;

/**
 * @Route("/liste-de-mariage")
 */
class WeddingListController extends Controller
{
    /**
     * @Route("/", name="wedding_list")
     * @Template
     */
    public function index(UploaderHelper $uploaderHelper)
    {
    	$listItems = $this->getDoctrine()->getManager()->getRepository('App:ListItem')->findBy([], ['ordering' => 'ASC']);

    	$markerList = [];
    	$polyline = [];

    	foreach ($listItems as $item) {
    		$markerList[] = [
    			'title' => $item->getTitle(),
    			'description' => $item->getDescription(),
    			'ordering' => $item->getOrdering(),
    			'latitude' => (float) $item->getLatitude(),
    			'longitude' => (float) $item->getLongitude(),
    			'price' => $item->getPrice(),
    			'image' => $uploaderHelper->asset($item, 'imageFile')
    		];
    		$polyline[] = [(float) $item->getLatitude(), (float) $item->getLongitude()];
    	}

        return [
        	'listItems' => $listItems,
        	'markerList' => $markerList,
        	'polyline' => $polyline
    	];
    }

    /**
     * @Route("/version-classique", name="wedding_list_classic")
     * @Template
     */
    public function classic()
    {
        return ['listItems' => $this->getDoctrine()->getManager()->getRepository('App:ListItem')->findBy([], ['ordering' => 'ASC'])];
    }

    /**
     * @Route("/admin", name="wedding_list_admin")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function admin()
    {
        return ['listItems' => $this->getDoctrine()->getManager()->getRepository('App:ListItem')->findBy([], ['ordering' => 'ASC'])];
    }

    /**
     * @Route("/admin/nouvel-element", name="wedding_list_new")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function newItem(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newItem = new ListItem();

        $newItemForm = $this->createForm(ListItemType::class, $newItem)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

    	$newItemForm->handleRequest($request);

        if ($newItemForm->isSubmitted() && $newItemForm->isValid()) {
	        $em->persist($newItem);
	        $em->flush();

	        return $this->redirectToRoute('wedding_list_admin');
	    }
        return ['form' => $newItemForm->createView()];
    }

    /**
     * @Route("/admin/modifier-un-element-{id}", name="wedding_list_edit")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function editItem(Request $request, ListItem $item)
    {
        $em = $this->getDoctrine()->getManager();

        $itemForm = $this->createForm(ListItemType::class, $item, ['new' => false])
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

        $itemForm->handleRequest($request);

        if ($itemForm->isSubmitted() && $itemForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('wedding_list_admin');
        }
        return ['form' => $itemForm->createView()];
    }

    /**
     * @Route("/admin/supprimer-un-element-{id}", name="wedding_list_delete")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function deleteItem(ListItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        $this->addFlash('success', "L'élément a bien été supprimé");

        return $this->redirectToRoute('wedding_list_admin');
    }

}
