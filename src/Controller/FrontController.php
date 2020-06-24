<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Articles;
use App\Entity\Reply;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\ReplyType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FrontController extends AbstractController
{


    /**
     * @Route("/articlesall", name="articlesall")
     */
    public function articlesall(ArticlesRepository $repo, PaginatorInterface $paginator, Request $request)
    {




        $articles = $repo->findAll();




        foreach ($articles as $article) {
            $data[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'imageFilename' => $article->getImageFilename(),
                'createdAt' => $article->getCreatedAt()
            ];
        }



        return new JsonResponse(
            $data
        );
    }


    /**
     * @Route("/articless", name="articless")
     */
    public function articless(ArticlesRepository $repo, PaginatorInterface $paginator, Request $request)
    {




        $data = $repo->findAll();

        $articles = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );


        foreach ($articles as $article) {
            $data[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'imageFilename' => $article->getImageFilename(),
                'createdAt' => $article->getCreatedAt()
            ];
        }



        return new JsonResponse(
            $data
        );
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articles()
    {





        return $this->render('front/articles.html.twig', [
            'controller_name' => 'FrontController',
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
     * @Route("/articles/{id}", name="article", options={"expose"=true})
     */
    public function article(Articles $article, Comment $comment1, Request $request, EntityManagerInterface $manager)
    {

        $user = $this->getUser();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setAuthor($user->getUsername())
                ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }


        return $this->render('front/article.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),
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

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();

            $message = (new \Swift_Message('Contact mail infos/devis'))
                ->setFrom($contactFormData['email'])
                ->setTo('corentinlafay@gmail.com')
                ->setBody(
                    '<html>' .
                        ' <body>' .
                        '  Message de: ' . $contactFormData['nom'] .
                        '<br>' .
                        '  Adresse: ' . $contactFormData['email'] .
                        '<br>' .
                        '  Message: ' . $contactFormData['message'] .
                        ' </body>' .
                        '</html>',
                    'text/html'


                );

            $mailer->send($message);

            return $this->redirectToRoute('contact');
        }



        return $this->render('front/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
