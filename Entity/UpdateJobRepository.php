<?php

namespace Mopa\Bundle\RemoteUpdateBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UpdateJobRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UpdateJobRepository extends EntityRepository
{
	public function getRunningJob(){
		$result = $this->createQueryBuilder("j")
			->select("j")
			->where("j.startAt < ?1")
			->andWhere("j.finishedAt IS NULL")
			->setParameter(1, new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
			->getQuery()
			->getOneOrNullResult();
		return $result;
	}
	public function hasRunningJob(){
		return $this->getRunningJob() ? true : false;
	}
	public function getPendingJob(){
		$result = $this->createQueryBuilder('j')
			->select("j")
			->where("j.startAt IS NULL AND j.finishedAt IS NULL")
			->getQuery()
			->getOneOrNullResult();
		return $result;
	}
	public function hasPendingJob(){
		return $this->hasRunningJob() || $this->getPendingJob() ? true : false;
	}
	public function getLastJobs($count){
		$result = $this->createQueryBuilder('j')
			->select("j")
			->orderBy('j.createdAt', 'ASC')
			->getQuery()
			->setMaxResults($count)
			->getResult();
		return $result;
	}
}
