<?php

namespace App\Repositories;

use App\Models\FragranceNote;

class NoteRepository 
{
    protected $fragranceNote;
    public function __construct(FragranceNote $fragranceNote)
    {
       $this->fragranceNote=$fragranceNote;
    }
     public function all()
    {
        return $this->fragranceNote->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return fragranceNote
     */
    public function create(array $data)
    {
        return $this->fragranceNote->create($data);
    }

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->fragranceNote->findOrFail($id);
        return $record->update($data);
    }

    /**
     * Delete a record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->fragranceNote->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return fragranceNote|null
     */
    public function find(int $id)
    {
        return $this->fragranceNote->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return 
     */
    public function findOrFail(int $id)
    {
        return $this->fragranceNote->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->fragranceNote->count();
    }

    public function getAllActive()
    {
        return $this->fragranceNote->all();
    }
}
