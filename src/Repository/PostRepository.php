<?php

namespace App\Repository;

use AllowDynamicProperties;
use App\Entity\Post;
use App\Repository\IRepository\PostRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Post>
 */
#[AllowDynamicProperties] class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    private PaginatorInterface $paginator;


    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return PaginationInterface
     */
    public function getAll(int $page = 1, int $limit = 15): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $page,
            $limit
        );
    }
}
