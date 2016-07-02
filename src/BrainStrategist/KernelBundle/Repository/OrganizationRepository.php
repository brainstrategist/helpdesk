<?php

namespace BrainStrategist\KernelBundle\Repository;

/**
 * OrganizationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrganizationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findMyOrganizations($params=array('limit'=>100,'offset'=>0))
    {
        extract($params);

        if (!is_null($userID)) {

            $q = $this->createQueryBuilder('o')
                ->leftJoin('o.usersOrganization', 'uo')
                ->leftJoin('o.creator', 'uc')
                ->addSelect('uo')
                ->addSelect('uc')
                ->andWhere('uo.id = :user')
                ->setParameter('user',$userID)
                ->setMaxResults($limit)
                ->setFirstResult($offset);


            $query = $q->getQuery();
            return $query->getArrayResult();
        }
        return false;
    }

    /* check if current shooting is mine */
    public function isMyOrganization($obj=null,$currentUser=null){

        $q= $this->createQueryBuilder('o')
            ->leftJoin('o.usersOrganization', 'uo')
            ->addSelect('uo');

        if(is_numeric($obj)){
            $q->where('o.id = :id')->setParameter('id', $obj);
        }else{
            $q->where('o.slug = :slug')->setParameter('slug', $obj);
        }

        $req =  $q->getQuery();
        $res = $req->getArrayResult();

<<<<<<< HEAD
        if(isset($currentUser) && sizeof($res)>0){
=======
        if(isset($currentUser)){
>>>>>>> 9744836f48e01aed85810e566a68da6d13645931
            foreach($res[0]["usersOrganization"] as $r){

                if($r['id']==$currentUser)
                    return true;

            }

        }
        return false;
    }
}
