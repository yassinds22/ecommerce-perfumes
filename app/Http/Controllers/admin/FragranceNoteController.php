<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FragranceNote;
use App\Services\NoteService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class FragranceNoteController extends Controller
{
    use LogsActivity;
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

        $note = $this->noteService->createNote($request->all());

        $this->logActivity('إضافة مكون عطر', "تم إضافة المكون: {$note->name_ar}", $note);

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

        $note = $this->noteService->updateNote($id, $request->all());

        $this->logActivity('تعديل مكون عطر', "تم تعديل بيانات المكون: {$note->name_ar}", $note);

        return redirect()->back()->with('success', 'تم تحديث المكون بنجاح');
    }

    public function destroy($id)
    {
        $this->noteService->deleteNote($id);

        $this->logActivity('حذف مكون عطر', "تم حذف المكون رقم: {$id}");
        return redirect()->back()->with('success', 'تم حذف المكون بنجاح');
    }

}
