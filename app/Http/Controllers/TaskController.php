<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function taskHandler(Request $request)
    {
        $this->validate($request, [
            'task_name' => 'required'
        ]);

        \App\Models\Task
            ::create(array(
                'task' => $request->task_name,
                'status' => 'pending'
            ));

        return array('status' => 'COMPLETE', 'message' => 'Successfully added the task');
    }

    public function getTasks()
    {
        $tasks = \App\Models\Task
            ::select(
                'task',
                'task_id',
                'status'
            )
            ->get()
            ->toArray();

        return array('status' => 'COMPLETE', 'data' => $tasks);
    }

    public function deleteTask(Request $request)
    {
        $this->validate($request, [
            'task_id' => 'required|numeric'
        ]);

        $task = \App\Models\Task
            ::find($request->task_id);

        if (!is_null($task)) {
            $task->delete();
        }

        return array('status' => 'COMPLETE', 'message' => 'Successfully deleted task');
    }

    public function completeTask(Request $request)
    {
        $this->validate($request, [
            'task_id' => 'required|numeric'
        ]);

        $task = \App\Models\Task
            ::find($request->task_id);

        if (!is_null($task)) {
            $task->status = 'complete';
            $task->save();
        }

        return array('status' => 'COMPLETE', 'message' => 'Successfully deleted task');
    }
}
