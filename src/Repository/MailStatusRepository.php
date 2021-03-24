<?php

namespace Moukail\MailgunMailStatusBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Moukail\MailgunMailStatusBundle\Entity\MailStatus;

class MailStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailStatus::class);
    }

    /**
     * @param array $data
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(array $data)
    {
        $eventData = $data['event-data'];
        $tags = $eventData['tags'];
        $recipient = $eventData['recipient'];
        $event = $eventData['event'];

        $entityManager = $this->getEntityManager();

        $mail = $this->findOneBy(['tag' => $tags[0], 'recipient' => $recipient]);
        if (!$mail) {
            $mail = new MailStatus();
            $mail->setTag($tags[0]);
            $mail->setRecipient($recipient);
            $entityManager->persist($mail);
        }
        $mail->setStatus($event);
        $mail->setData($data);

        $entityManager->flush();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();

        return $qb->getArrayResult();
    }

    /**
     * @param string $monthYear
     * @return array
     */
    public function getList(string $monthYear): array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m')
            ->where('DATE_FORMAT(m.createdAt, \'%m-%Y\') = :monthYear')
            ->setParameter('monthYear', $monthYear)
            ->getQuery();

        return $qb->getArrayResult();
    }

    /**
     * @param null $startDate
     * @param null $endDate
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotal($startDate = null, $endDate = null)
    {
        $entityManager = $this->getEntityManager();

        if (!is_null($startDate) && !is_null($endDate)) {
            $dql =  /* @lang DQL */ "SELECT COUNT(m) AS total FROM App\Entity\MailStatus m WHERE m.createdAt BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        } else {
            $dql =  /* @lang DQL */ "SELECT COUNT(m) AS total FROM App\Entity\MailStatus m";
        }

        $query = $entityManager->createQuery($dql);

        return $query->getSingleResult();
    }

    /**
     * @param string $status
     * @param null $startDate
     * @param null $endDate
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalByStatus(string $status, $startDate = null, $endDate = null)
    {
        $entityManager = $this->getEntityManager();

        if (!is_null($startDate) && !is_null($endDate)) {
            $dql =  /* @lang DQL */ "SELECT COUNT(m) AS total FROM App\Entity\MailStatus m WHERE m.status = '$status' AND m.createdAt BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
        } else {
            $dql =  /* @lang DQL */ "SELECT COUNT(m) AS total FROM App\Entity\MailStatus m WHERE m.status = '$status'";
        }

        $query = $entityManager->createQuery($dql);

        return $query->getSingleResult();
    }
}
