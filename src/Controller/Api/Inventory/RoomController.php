<?php

namespace App\Controller\Api\Inventory;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/rooms')]
class RoomApiController extends AbstractController
{
    private $entityManager;

    private $roomRepository;

    public function __construct(EntityManagerInterface $entityManager, RoomRepository $roomRepository)
    {
        $this->entityManager = $entityManager;
        $this->roomRepository = $roomRepository;
    }
   
    #[Route('/', name: 'api_room_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $room = new Room();
        $room->setName($data[ 'name' ]);
        $room->setCreatedAt(new \DateTimeImmutable());
        $room->setUpdatedAt(new \DateTimeImmutable());
      

        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Room created'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_room_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $room = $this->roomRepository->find($id);
        if (!$room) {
            return new JsonResponse(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $room->setName($data[ 'name' ]);
        $room->setUpdatedAt(new \DateTimeImmutable());
        

        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Room updated'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_room_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $room = $this->roomRepository->find($id);
        if (!$room) {
            return new JsonResponse(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }

        // Vérifier s'il y a des inventaires ou des produits associés
        if (!$room->getInventories()->isEmpty() || !$room->getProducts()->isEmpty()) {
            return new JsonResponse(['message' => 'Room cannot be deleted, it has associated inventories or products'], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($room);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Room deleted'], Response::HTTP_OK);
    }
    }

