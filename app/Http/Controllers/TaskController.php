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
        $tasks = Task::orderBy('id', 'desc')
            ->where('company_id', Auth()->user()->company_id)->get();

        return [
            'tasks' => TaskResource::collection($tasks),
            'tasks_count' => $tasks->count(),
            'completed_tasks_count' => $tasks->where('completed', true)->count(),
        ];
    }

    public function store(CreateTaskRequest $request)
    {
        $input = $request->validated();

        $user = Auth()->user();

        $task = $user->tasks()->create([
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

    }
}
