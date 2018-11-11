<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\BlogArticle;
use App\Entity\BlogComment;
use App\Form\BlogArticleType;
use App\Form\BlogCommentType;
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
        $em = $this->getDoctrine()->getManager();
        return [
            'articles' => $em->getRepository('App:BlogArticle')->findBy([], ['createdAt' => 'DESC']),
            'texts' => $em->getRepository('App:TextBlock')->findAllByName(),
        ];
    }

    /**
     * @Route("/nouvel-article", name="article_new")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function newArticle(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$newArticle = new BlogArticle();

        $newArticleForm = $this->createForm(BlogArticleType::class, $newArticle)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

    	$newArticleForm->handleRequest($request);

        if ($newArticleForm->isSubmitted() && $newArticleForm->isValid()) {
	        $newArticle->setCreatedAt(new \DateTime('now'));
	        $em->persist($newArticle);
	        $em->flush();

	        return $this->redirectToRoute('article_show', ['id' => $newArticle->getId()]);
	    }
        return ['form' => $newArticleForm->createView()];
    }

    /**
     * @Route("/article-{id}", name="article_show")
     * @Template
     */
    public function showArticle(Request $request, BlogArticle $article)
    {
        $em = $this->getDoctrine()->getManager();
        $newComment = new BlogComment();

        $newCommentForm = $this->createForm(BlogCommentType::class, $newComment)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

        $newCommentForm->handleRequest($request);

        if ($newCommentForm->isSubmitted() && $newCommentForm->isValid()) {
            $newComment->setCreatedAt(new \DateTime('now'))->setArticle($article);
            $em->persist($newComment);
            $em->flush();
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return ['article' => $article, 'commentForm' => $newCommentForm->createView()];
    }

    /**
     * @Route("/article-{id}/editer", name="article_edit")
     * @IsGranted("ROLE_ADMIN")
     * @Template
     */
    public function editArticle(Request $request, BlogArticle $article)
    {
    	$em = $this->getDoctrine()->getManager();

        $articleForm = $this->createForm(BlogArticleType::class, $article)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;

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
     * @IsGranted("ROLE_ADMIN")
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
