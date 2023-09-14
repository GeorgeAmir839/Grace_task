<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class CreateJsonFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:json-file {file_name}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileName = $this->argument('file_name');
        $filePath = storage_path('app/'.$fileName.'.json'); // Define the file path

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error('The file already exists.');
            return;
        }

        File::put($filePath, '{}');
        if($fileName == 'tasks'){
            $tasks = [];
            for ($i = 1; $i <= 10; $i++) {
                $newTask = [
                    'id' => uniqid(), 
                    'title' => "Task $i",
                    'created_at' => now()->toDateTimeString(),
                ];
                $tasks[] = $newTask;
            }
            Storage::put('tasks.json', json_encode($tasks));
        }
       

        $this->info('JSON file created successfully!');
    }
}
