<?php

namespace Moukail\MailgunMailStatusBundle\Controller;

use Moukail\MailgunMailStatusBundle\Repository\MailStatusRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailStatusController extends AbstractController
{
    /**
     * @var MailStatusRepository
     */
    private MailStatusRepository $mailStatusRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * DeviceController constructor.
     * @param MailStatusRepository $mailStatusRepository
     * @param LoggerInterface $logger
     */
    public function __construct(MailStatusRepository $mailStatusRepository, LoggerInterface $logger)
    {
        $this->mailStatusRepository = $mailStatusRepository;
        $this->logger = $logger;
    }

    public function list(): JsonResponse
    {
        // todo use voter
        $user = $this->getUser();
        if(!$user || !in_array('ROLE_ADMIN', $user->getRoles())){
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->mailStatusRepository->getAll();
        return $this->json($result, Response::HTTP_OK);
    }

    public function list_month(string $month): JsonResponse
    {
        // todo use voter
        $user = $this->getUser();
        if(!$user || !in_array('ROLE_ADMIN', $user->getRoles())){
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        $result = $this->mailStatusRepository->getList($month);
        return $this->json($result, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        /** @var string $content */
        $content = $request->getContent();

        /** @var array $data */
        $data = json_decode($content, true);

        //$logger->alert('MailController - delivered:', $data);

        $this->mailStatusRepository->save($data);

        return $this->json(['result' => $data], Response::HTTP_OK);
    }
}
