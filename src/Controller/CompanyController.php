<?php

namespace App\Controller;

use App\Event\Complany\CreateCompanyEvent;
use App\Event\Complany\DeleteCompanyEvent;
use App\Event\Complany\Query\GetCompaniesQueryEvent;
use App\Event\Complany\Query\GetCompanyQueryEvent;
use App\Event\Complany\UpdateCompanyEvent;
use App\Request\CompanyRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/companies')]
class CompanyController extends AbstractController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $event = new GetCompaniesQueryEvent();
        $this->eventDispatcher->dispatch($event, GetCompaniesQueryEvent::NAME);

        return $this->json($event->getCompanies());
    }

    #[Route('', methods: ['POST'])]
    public function create(#[MapRequestPayload] CompanyRequest $companyRequest,): JsonResponse {
        $event = new CreateCompanyEvent($companyRequest);
        $this->eventDispatcher->dispatch($event, CreateCompanyEvent::NAME);

        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $event = new GetCompanyQueryEvent($id);
        $this->eventDispatcher->dispatch($event, GetCompanyQueryEvent::NAME);

        $company = $event->getCompany();

        if (!$company) {
            throw $this->createNotFoundException('Company with ID ' . $id . ' not found');
        }

        return $this->json($company);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        #[MapRequestPayload] CompanyRequest $companyRequest,
    ): JsonResponse {
        $event = new UpdateCompanyEvent($id, $companyRequest);
        $this->eventDispatcher->dispatch($event, UpdateCompanyEvent::NAME);

        return $this->json([]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id,): JsonResponse {
        $event = new DeleteCompanyEvent($id);
        $this->eventDispatcher->dispatch($event, DeleteCompanyEvent::NAME);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
