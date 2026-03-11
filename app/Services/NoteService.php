<?php

namespace App\Services;

use App\Repositories\NoteRepository;
use Illuminate\Support\Facades\DB;

class NoteService
{
    use \App\Traits\LogsActivity;

    protected $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function getAllNotes()
    {
        return $this->noteRepository->getAllActive();
    }

    /**
     * Get paginated notes.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedNotes(int $perPage = 10)
    {
        return \App\Models\FragranceNote::latest()->paginate($perPage);
    }

    public function createNote(array $data)
    {
        $note = $this->noteRepository->create([
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'] ?? $data['name_ar'],
            ],
            'description' => [
                'ar' => $data['description_ar'] ?? null,
                'en' => $data['description_en'] ?? null,
            ],
        ]);

        $this->logActivity('إضافة مكون عطر', "تم إضافة المكون: {$note->name_ar}", $note);

        return $note;
    }

    public function updateNote($id, array $data)
    {
        $this->noteRepository->update($id, [
            'name' => [
                'ar' => $data['name_ar'],
                'en' => $data['name_en'] ?? $data['name_ar'],
            ],
            'description' => [
                'ar' => $data['description_ar'] ?? null,
                'en' => $data['description_en'] ?? null,
            ],
        ]);

        $note = $this->findNote($id);
        $this->logActivity('تعديل مكون عطر', "تم تعديل بيانات المكون: {$note->name_ar}", $note);

        return $note;
    }

    public function deleteNote($id)
    {
        $this->logActivity('حذف مكون عطر', "تم حذف المكون رقم: {$id}");
        return $this->noteRepository->delete($id);
    }

    public function findNote($id)
    {
        return $this->noteRepository->find($id);
    }
}
