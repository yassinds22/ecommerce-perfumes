<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FragranceNote;
use App\Services\NoteService;
use Illuminate\Http\Request;

class FragranceNoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = $this->noteService->getAllNotes();
        return view('admin.notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $this->noteService->createNote($request->all());

        return redirect()->back()->with('success', 'تمت إضافة المكون بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $this->noteService->updateNote($id, $request->all());

        return redirect()->back()->with('success', 'تم تحديث المكون بنجاح');
    }

    public function destroy($id)
    {
        $this->noteService->deleteNote($id);
        return redirect()->back()->with('success', 'تم حذف المكون بنجاح');
    }

}
