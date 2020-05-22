<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Articles;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FrontController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function articles(ArticlesRepository $repo, Request $request, PaginatorInterface $paginator)
    {
        $data = $repo->findAll();

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('front/articles.html.twig', [
            'controller_name' => 'FrontController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(ArticlesRepository $repo)
    {
        $articles = $repo->findAll();

        return  $this->render('front/home.html.twig', [
            'title' => "Bienvenue",
            'controller_name' => 'FrontController',
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/articles/{id}", name="article")
     */
    public function article(Articles $article, Request $request, EntityManagerInterface $manager)
    {

        $user = $this->getUser();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                    ->setAuthor($user->getUsername())
                    ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }

        return $this->render('front/article.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/realisations", name="realisations")
     */
    public function realisations()
    {
        

        return  $this->render('front/realisations.html.twig', [
            'title' => "Bienvenue",
            
        ]);
    }

    // To move to backend

}
