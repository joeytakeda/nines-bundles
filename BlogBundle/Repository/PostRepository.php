<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace Nines\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Nines\BlogBundle\Entity\PostStatus;

/**
 * PostRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository {
    /**
     * Return a full text query, respecting private comments.
     *
     * @param string $q
     * @param string $private
     *
     * @return Query
     */
    public function fulltextQuery($q, $private = false) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect('MATCH (e.title, e.searchable) AGAINST (:q BOOLEAN) as HIDDEN score');
        $qb->andWhere('MATCH (e.title, e.searchable) AGAINST (:q BOOLEAN) > 0');
        if ( ! $private) {
            $em = $this->getEntityManager();
            $statuses = $em->getRepository(PostStatus::class)->findBy([
                'public' => true,
            ]);
            $qb->andWhere('e.status = :status');
            $qb->setParameter('status', $statuses);
        }
        $qb->orderBy('score', 'desc');
        $qb->setParameter('q', $q);

        return $qb->getQuery();
    }

    /**
     * Get a query to list recent blog posts.
     *
     * @param bool $private
     * @param int $limit
     *
     * @return Query
     */
    public function recentQuery($private = false, $limit = 0) {
        $em = $this->getEntityManager();
        $qb = $this->createQueryBuilder('e');
        if ( ! $private) {
            $statuses = $em->getRepository(PostStatus::class)->findBy([
                'public' => true,
            ]);
            $qb->andWhere('e.status = :status');
            $qb->setParameter('status', $statuses);
        }
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        $qb->orderBy('e.id', 'DESC');

        return $qb->getQuery();
    }
}
