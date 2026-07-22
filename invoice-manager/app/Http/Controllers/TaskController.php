<?php

namespace App\Http\Controllers;

use App\Models\FormOrderTask;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = FormOrderTask::query()
            ->where('assigned_to', auth()->id())
            ->with('formOrder.brand')
            ->orderBy('urutan')
            ->get();

        $pending = $tasks->where('is_done', false)->values();
        $done = $tasks->where('is_done', true)->sortByDesc('completed_at')->values();

        return view('tasks.index', compact('pending', 'done'));
    }

    public function toggle(FormOrderTask $task)
    {
        $this->authorize('update', $task);

        $isDone = ! $task->is_done;

        $task->update([
            'is_done' => $isDone,
            'completed_at' => $isDone ? now() : null,
        ]);

        return back()->with('success', $isDone ? 'Tugas ditandai selesai.' : 'Tugas dibuka kembali.');
    }
}
