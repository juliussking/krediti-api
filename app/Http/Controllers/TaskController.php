<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\CreateTaskResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        Log::info('>>> REQUISIÇÃO RECEBIDA', [
            'rota' => request()->path(),
            'ip' => request()->ip(),
            'agente' => request()->header('User-Agent'),
        ]);

        $tasks = Task::orderBy('id', 'desc')->get();

        return [
            'tasks' => TaskResource::collection($tasks),
            'tasks_count' => $tasks->count(),
            'completed_tasks_count' => $tasks->where('completed', true)->count(),
        ];
    }

    public function store(CreateTaskRequest $request)
    {
        $input = $request->validated();

        $task = Task::create([
            'title' => $input['title'],
            'author' => Auth()->user()->name,
        ]);

        return new CreateTaskResource($task);
    }

    public function updateCompleted($id)
    {
        $task = Task::findOrFail($id);

        if ($task->completed) {
            $task->completed = false;
            $task->user_id = null;
        } else {
            $task->completed = true;
            $task->user_id = auth()->id();
        }

        $task->save();

        return [
            'task_completed' => $task->completed,
        ];
    }
}
