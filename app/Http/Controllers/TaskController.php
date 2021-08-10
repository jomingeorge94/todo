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
}
