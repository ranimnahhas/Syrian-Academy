<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactRepository extends BaseRepository
{
    public function __construct(Contact $contact)
    {
        parent::__construct($contact);
    }

    public function getUnread(): LengthAwarePaginator
    {
        return $this->model
            ->whereNull('read_at')
            ->latest()
            ->paginate(15);
    }

    public function getAllPaginated(): LengthAwarePaginator
    {
        return $this->model
            ->latest()
            ->paginate(15);
    }
    public function countUnread(): int
    { 
         return $this->model->whereNull('read_at')->count();
    }
    public function getRecent(int $limit = 5)
    { 
        return $this->model
            ->latest()
            ->limit($limit)
            ->get();
    }
}