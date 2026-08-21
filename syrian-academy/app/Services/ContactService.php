<?php

namespace App\Services;

use App\Repositories\ContactRepository;

class ContactService
{
    public function __construct(
        private ContactRepository $contactRepository
    ) {}

    public function create(array $data)
    {
        return $this->contactRepository->create($data);
    }

    public function getAll()
    {
        return $this->contactRepository->getAllPaginated();
    }

    public function getUnread()
    {
        return $this->contactRepository->getUnread();
    }

    public function getById(int $id)
    {
        return $this->contactRepository->find($id);
    }

    public function markAsRead(int $id)
    {
        $contact = $this->contactRepository->find($id);

        if (!$contact) {
            return null;
        }

        $contact->markAsRead();
        return $contact->fresh();
    }

    public function delete(int $id): ?bool
    {
        return $this->contactRepository->delete($id);
    }
}