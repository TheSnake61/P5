<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Articles;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
  * Require ROLE_ADMIN for *every* controller method in this class.
  *
  * @IsGranted("ROLE_ADMIN")
  */

class BackController extends AbstractController
{
    /**
     * @Route("/article/new", name="article_create")
     * @Route("/articles/{id}/edit", name="article_edit")
     */
    public function form(Articles $article = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$article) {
            $article = new Articles();
        }
        
    

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
            $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }

        return $this->render('back/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }
}

