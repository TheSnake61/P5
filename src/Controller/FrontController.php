<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Articles;
use App\Entity\Livredor;
use App\Entity\Reply;
use App\Form\ArticleType;
use App\Form\LivredorType;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\ReplyType;
use App\Repository\ArticlesRepository;
use App\Repository\LivredorRepository;
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

        $result=[];

        foreach ($articles as $article) {
            $result[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'content' => $article->getContent(),
                'imageFilename' => $article->getImageFilename(),
                'createdAt' => $article->getCreatedAt()
            ];
        }



        return new JsonResponse(
            $result
        );
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articles(ArticlesRepository $repo)
    {
        $data=$repo->findAll();
        $articlecount=count($data);
        




        return $this->render('front/articles.html.twig', [
            'controller_name' => 'FrontController',
            'articlecount'  => $articlecount,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(ArticlesRepository $repo)
    {
        $articles = $repo->findAll();

        return  $this->render('front/home.html.twig', [
            
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
     * @Route("/livredor", name="livredor", options={"expose"=true})
     */
    public function livredor(Request $request, LivredorRepository $repo, EntityManagerInterface $manager)
    {


        $user = $this->getUser();

        $entry = new Livredor();

        $form = $this->createForm(LivredorType::class, $entry);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $entry->setCreatedAt(new \DateTime())
                  ->setName($user->getUsername())
                  ->setValid(1);
                

            $manager->persist($entry);
            $manager->flush();

            return $this->redirectToRoute('livredor');
        }
        
        $livredor = $repo->findAll();
            

       

        return  $this->render('front/livredor.html.twig', [
            'livredor' => $livredor,
            'livredorForm' => $form->createView(),
            

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
                ->setTo('opnclsrmsprojects@gmail.com')
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
