<?php

namespace BrainStrategist\KernelBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUsersByProjects($params=array('limit'=>100))
    {
        extract($params);

        if (!is_null($projectID)) {

            $q = $this->createQueryBuilder('u')
                ->leftJoin('u.projects', 'up')
                ->addSelect('up')
                ->andWhere('up.id = :project')
                ->setParameter('project',$projectID)
                ->setMaxResults($limit);

            $query=$q->getQuery();
            return $q;

        }
        return false;
    }
}
