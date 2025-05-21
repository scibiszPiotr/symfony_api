<?php

namespace App\Controller;

use App\Event\Employee\EmployeeCreatedEvent;
use App\Event\Employee\EmployeeDeletedEvent;
use App\Event\Employee\Query\GetEmployeeQueryEvent;
use App\Event\Employee\Query\GetEmployeesQueryEvent;
use App\Event\Employee\EmployeeUpdatedEvent;
use App\Request\EmployeeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[Route('/api/employees')]
class EmployeeController extends AbstractController
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $event = new GetEmployeesQueryEvent();
        $this->eventDispatcher->dispatch($event, GetEmployeesQueryEvent::NAME);

        return $this->json($event->getEmployees());
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse {
        $event = new GetEmployeeQueryEvent($id);
        $this->eventDispatcher->dispatch($event, GetEmployeeQueryEvent::NAME);
        $employee = $event->getEmployee();

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($employee);
    }

    #[Route('', methods: ['POST'])]
    public function create(#[MapRequestPayload] EmployeeRequest $employeeRequest,): JsonResponse {
        $event = new EmployeeCreatedEvent($employeeRequest);
        $this->eventDispatcher->dispatch($event, EmployeeCreatedEvent::NAME);

        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        #[MapRequestPayload] EmployeeRequest $employeeRequest,
    ): JsonResponse {
        $event = new EmployeeUpdatedEvent($id, $employeeRequest);
        $this->eventDispatcher->dispatch($event, EmployeeUpdatedEvent::NAME);

        return $this->json([]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $event = new EmployeeDeletedEvent($id);
        $this->eventDispatcher->dispatch($event, EmployeeDeletedEvent::NAME);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}