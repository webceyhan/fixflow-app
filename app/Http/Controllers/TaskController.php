<?php

namespace App\Http\Controllers;

use App\Actions\Tasks\CreateTask;
use App\Actions\Tasks\DeleteTask;
use App\Actions\Tasks\QueryTasks;
use App\Actions\Tasks\UpdateTask;
use App\Http\Requests\Tasks\TaskStoreRequest;
use App\Http\Requests\Tasks\TaskUpdateRequest;
use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QueryTasks $queryTasks): Response
    {
        $tasks = $queryTasks->handle()
            ->with(['ticket.device', 'ticket.customer', 'assignee'])
            ->paginate();

        return Inertia::render('tasks/Index', [
            'tasks' => $tasks,
            'filter' => request('filter'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Ticket $ticket): Response
    {
        $task = new Task(['ticket_id' => $ticket->id]);
        $ticket->load(['device', 'customer']);

        return Inertia::render('tasks/Create', [
            'task' => $task,
            'ticket' => $ticket,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request, Ticket $ticket, CreateTask $createTask): RedirectResponse
    {
        $task = $createTask->handle([
            ...$request->validated(),
            'ticket_id' => $ticket->id,
        ]);

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): Response
    {
        $task->load(['ticket', 'ticket.device', 'ticket.customer', 'assignee']);

        return Inertia::render('tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): Response
    {
        $task->load(['ticket', 'ticket.device', 'ticket.customer', 'assignee']);

        return Inertia::render('tasks/Edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task, UpdateTask $updateTask): RedirectResponse
    {
        $updateTask->handle($task, $request->validated());

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, DeleteTask $deleteTask): RedirectResponse
    {
        $deleteTask->handle($task);

        return redirect()->route('tasks.index');
    }
}
