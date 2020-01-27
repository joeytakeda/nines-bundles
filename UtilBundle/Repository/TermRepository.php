<?php

namespace Nines\UtilBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * TermRepository
 */
abstract class TermRepository extends EntityRepository {
    
    /**
     * Do a typeahead-style query and return the results.
     * 
     * @param string $q
     * @return Collection|AbstractTerm[]
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('v');
        $qb->where('v.label like :q');
        $qb->setParameter('q', '%' . $q . '%');
        return $qb->getQuery()->execute();
    }

    /**
     * Do a full text search on the label and description fields.
     *
     * @param $q
     *
     * @return Query
     */
    public function searchQuery($q) {
        $qb = $this->createQueryBuilder('v');
        $qb->where( "MATCH (v.label, v.description) AGAINST (:q BOOLEAN) > 0.0");
        $qb->setParameter('q', $q);
        return $qb->getQuery();
    }
}
