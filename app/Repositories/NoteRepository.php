<?php

namespace App\Repositories;

use App\Models\FragranceNote;

class NoteRepository extends BaseRepository
{
    public function __construct(FragranceNote $model)
    {
        parent::__construct($model);
    }

    public function getAllActive()
    {
        return $this->model->all();
    }
}
