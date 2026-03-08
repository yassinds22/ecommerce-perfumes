<?php

namespace App\Services;

use App\Repositories\NoteRepository;
use Illuminate\Support\Facades\DB;

class NoteService
{
    protected $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function getAllNotes()
    {
        return $this->noteRepository->getAllActive();
    }

    public function createNote(array $data)
    {
        return $this->noteRepository->create([
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'] ?? $data['name_ar'],
            ],
            'description' => [
                'ar' => $data['description_ar'] ?? null,
                'en' => $data['description_en'] ?? null,
            ],
        ]);
    }

    public function updateNote($id, array $data)
    {
        return $this->noteRepository->update($id, [
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'] ?? $data['name_ar'],
            ],
            'description' => [
                'ar' => $data['description_ar'] ?? null,
                'en' => $data['description_en'] ?? null,
            ],
        ]);
    }

    public function deleteNote($id)
    {
        return $this->noteRepository->delete($id);
    }

    public function findNote($id)
    {
        return $this->noteRepository->find($id);
    }
}
