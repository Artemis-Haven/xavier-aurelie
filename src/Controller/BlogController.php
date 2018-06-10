<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\BlogArticle;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     * @Template
     */
    public function index()
    {
        return ['articles' => $this->getDoctrine()->getManager()->getRepository('App:BlogArticle')->findAll()];
    }

    /**
     * @Route("/nouvel-article", name="article_new")
     * @Template
     */
    public function newArticle(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newArticle = new BlogArticle();

    	$newArticleForm = $this->createFormBuilder($newArticle)
            ->add('author', TextType::class)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

    	$newArticleForm->handleRequest($request);

        if ($newArticleForm->isSubmitted() && $newArticleForm->isValid()) {
	        $newArticle->setCreatedAt(new \DateTime('now'));
	        $em->persist($newArticle);
	        $em->flush();

	        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
	    }
        return ['form' => $newArticleForm->createView()];
    }

    /**
     * @Route("/article-{id}", name="article_show")
	 * @ParamConverter("article", class="App:BlogArticle")
     * @Template
     */
    public function showArticle(BlogArticle $article)
    {
        return ['article' => $article];
    }

    /**
     * @Route("/article-{id}/editer", name="article_edit")
	 * @ParamConverter("article", class="App:BlogArticle")
     * @Template
     */
    public function editArticle(Request $request, BlogArticle $article)
    {
    	$em = $this->getDoctrine()->getManager();

    	$articleForm = $this->createFormBuilder($article)
            ->add('author', TextType::class)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
            ->getForm();

    	$articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
	        $article->setLastEditedAt(new \DateTime('now'));
	        $em->flush();

	        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
	    }
        return [
        	'form' => $articleForm->createView(), 
        	'article' => $article
        ];
    }

    /**
     * @Route("/article-{id}/supprimer", name="article_delete")
	 * @ParamConverter("article", class="App:BlogArticle")
     * @Template
     */
    public function deleteArticle(BlogArticle $article)
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($article);
    	$em->flush();

        return $this->redirectToRoute('blog');
    }
}
