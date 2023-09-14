<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreTaskRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;



class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', null);
        $search = $request->input('search');
        $filePath = storage_path('app/tasks.json');
        if (!File::exists($filePath)) {
            File::put($filePath, '{}');
        }
        $tasks = json_decode(Storage::get('tasks.json'), true);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;
        if ($sort == 'oldest') {
            usort($tasks, function ($a, $b) {
                return strtotime($a['created_at']) - strtotime($b['created_at']);
            });
        } else {
            usort($tasks, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }
        if (!empty($search)) { // Filter tasks based on the search query
            $filteredTasks = array_filter($tasks, function ($task) use ($search) {
                // Assuming the 'title' key exists in each task
                return stripos($task['title'], $search) !== false;
            });

            // You can convert the filtered tasks back to an array if needed
            $tasks = array_values($filteredTasks);
        }
        $currentTasks = array_slice($tasks, ($currentPage - 1) * $perPage, $perPage);

        $tasks = new LengthAwarePaginator(
            $currentTasks,
            count($tasks),
            $perPage,
            $currentPage,
            [
                'path' => route('tasks.index'),
                'query' => request()->query(),
            ]
        );

        return view('site.tasks.index', compact('tasks', 'sort', 'search'));
    }



    public function store(StoreTaskRequest $request)
    {
        $tasks = json_decode(Storage::get('tasks.json'), true);
        $newTask = [
            'id' => uniqid(),
            'title' => $request->input('title'),
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
        $tasks[] = $newTask;
        Storage::put('tasks.json', json_encode($tasks));
        return response()->json(
            [
                'message' => 'Task saved successfully',
                'tasks' => $tasks
            ]);
    
    }
    public function show($id)
    {
        $tasks = json_decode(Storage::get('tasks.json'));
        $task = collect($tasks)->firstWhere('id', $id);
        return view('site.tasks.show', compact('task'));
    }
   
    public function update(Request $request, $id)
    {
        $tasks = json_decode(Storage::get('tasks.json'));
        $taskIndex = collect($tasks)->search(function ($item) use ($id) {
            return $item->id === $id;
        });

        if ($taskIndex !== false) {
            $tasks[$taskIndex]->title = $request->input('title');
            Storage::put('tasks.json', json_encode($tasks));
        }
        return response()->json(
            [
                'message' => 'Task title updated successfully',
                'tasks' => $tasks
            ]);
    }

    public function destroy($id)
    {
        // dd($id);
        $tasks = json_decode(Storage::get('tasks.json'));
        $tasks = collect($tasks)->reject(function ($item) use ($id) {
            return $item->id === $id;
        })->values();
        Storage::put('tasks.json', json_encode($tasks));
        return response()->json(
            [
                'message' => 'Task deleted successfully.',
                'tasks' => $tasks
            ]);
       
    }
}
