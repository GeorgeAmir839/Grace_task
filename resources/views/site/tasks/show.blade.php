@extends('site.layout.app')

@section('css')
<div class="card m-5">
    <div class="card-header row m-5">
        <div class="text-center text-md-left m-5">
            <h5 class="mb-md-0 h5">Title: {{ $task->title }}</h5>
           <div class="mt-2">
            <span class="ml-2">ID: {{ $task->id }} </span>
            <span class="ml-2"> </span>
            <span class="ml-2">Created At: {{ $task->created_at }} </span>
           </div>
        </div>
    </div>
</div>
@endsection