<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

use App\Task;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;

class Tasks extends Controller
{
    public function create(TaskRequest $request) : TaskResource
    {
        $data = $request->only(["task"]);
        $data["user_id"] = Auth::id();
        $task = Task::create($data);

        return new TaskResource($task);
    }

    public function list() : ResourceCollection
    {
        return TaskResource::collection(Auth::user()->tasks);
    }

    public function read(Task $task) : TaskResource
    {
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task) : TaskResource
    {
        $data = $request->only(["task"]);
        $task->fill($data)->save();

        return new TaskResource($task);
    }

    public function complete(Task $task) : TaskResource
    {
        $task->completed = true;
        $task->save();
        return new TaskResource($task);
    }

    public function delete(Task $task) : Response
    {
        $task->delete();
        return response(null, 204);
    }
}
