<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Get all the posts and the related amount of comments
     *
     * @return array The posts for the homepage
     */
    public function findHomepage()
    {
        // SELECT post.*, COUNT(comment.id) as nb_comments
        // FROM post AS p
        // JOIN comment AS c
        // ON post.id = comment.post_id
        // WHERE post.public = true
        // GROUP BY post.id
        // ORDER BY post.id DESC

        $results = $this->createQueryBuilder('p')
            ->select('p as post, COUNT(c.id) as nbComments')
            ->join('p.comments', 'c')
            ->where('p.public = true')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $posts = [];
        foreach ($results as $result) {
            $post = $result['post'];
            $post->setNbComments($result['nbComments']);
            array_push($posts, $post);
        }

        return $posts;
    }

//     /**
//      * @return Post[] Returns an array of Post objects
//      */

     /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.author = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
