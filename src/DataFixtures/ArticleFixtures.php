<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Articles;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        
        

        for($i = 1; $i <=20; $i++){
            $article = new Articles();

            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

            $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'));

            $manager->persist($article);


            // comments
            for($j = 1; $j <= mt_rand(5, 10); $j++) {
                $comment = new Comment();

                $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                $now = new \DateTime();
                $interval = $now->diff($article->getCreatedAt());
                $days = $interval->days;
                $minimum = '-' . $days . 'days'; // -100 days par ex

                $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween($minimum))
                        ->setArticle($article);

                $manager->persist($comment);

            }
        }

        $manager->flush();
    }
}
