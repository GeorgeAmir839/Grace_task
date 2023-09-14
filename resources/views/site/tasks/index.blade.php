@extends('site.layout.app')

@section('css')
    <style>
        .relative.z-0.inline-flex.shadow-sm.rounded-md {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="text-left">
        <div class="row align-items-center p-0 m-0">
            <div class="col-auto">
                <h1 class="h3">{{ trans('All Tasks') }}</h1>
            </div>
            <div class="col text-right">
                <button class="btn btn-circle btn-info display_div_create_task btn-sm">Add New Task</button>
                </a>
            </div>

        </div>
    </div>
    <div class="row d-none div_create_task px-5 py-3">
        @include('site.tasks.create')
    </div>
    <div class="card">
        <div class="card-header px-5 py-3">
            <form class="row" method="GET" action="{{ route('tasks.index') }}" id="sortForm">

                <div class="input-group mb-3">


                    <select class="form-select sort" id="inputGroupSelect04" name="sort"
                        aria-label="Example select with button addon">
                        <option value="newest" @if ($sort == 'newest') selected @endif>Newest First</option>

                        <option value="oldest" @if ($sort == 'oldest') selected @endif>Oldest First</option>
                    </select>
                    <button class="btn btn-outline-secondary" type="submit">Sort by Date Added</button>

                </div>
            </form>
            <form action="{{ route('tasks.index') }}" method="GET">

                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" id="search"
                        value="@if ($search != null) {{ $search }} @endif">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                </div>
            </form>

        </div>
        <div class="card-body p-5">
            @include('site.tasks.table')

        </div>

    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            //display alert message window.location.reload()
            $(function() {
                if (sessionStorage.getItem('reloadAfterPageLoad') === 'true') {
                    sessionStorage.setItem('reloadAfterPageLoad', 'false');
                    var message = sessionStorage.getItem('message');
                    if (message) {
                        var html = '<div class="alert alert-success mb-0">' + message + '</div>';
                        $('.success_from_ajax').html(html).removeClass('d-none');
                    }
                }
            });

            function saveTasksToLocalStorage(tasks) {
                localStorage.setItem('tasks', JSON.stringify(tasks));
            }

            function restoreTasksFromLocalStorage() {
                var tasks = JSON.parse(localStorage.getItem('tasks'));

                if (tasks) {
                    tasks.forEach(function(task) {});
                }
            }


            //create ajax functions
            $('.display_div_create_task').click(function() {

                $('.div_create_task').toggleClass('d-none');
            });

            $('#addTaskForm').submit(function(e) {
                e.preventDefault(); 
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('tasks.store') }}'
                    data: formData,
                    success: function(response) {

                        saveTasksToLocalStorage(response.tasks);;
                        sessionStorage.setItem('message', response.message);
                        sessionStorage.setItem('reloadAfterPageLoad', 'true');
                        window.location.reload();

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });



            //edit ajax functions
            // Enable inline editing when focus on a task title
            $(".editable").on("focus", function() {
                var taskId = $(this).data("task-id");
                $(this).data("prev-value", $(this).text());
                $(`.save-task${taskId}, .cancel-edit${taskId}`).removeClass("d-none");
            });
            // Hide "Cancel" button when clicking outside of the task title
            // $(".editable").on("blur", function () {
            //     var taskId = $(this).data("task-id");
            //     $(`.save-task${taskId}, .cancel-edit${taskId}`).addClass("d-none");
            // });
            // Cancel inline editing
            $(".cancel-edit").click(function() {
                var taskId = $(this).data("id");
                var taskTitle = $(`tr[data-task-id="${taskId}"] .editable`);
                taskTitle.text(taskTitle.data("prev-value"));
                $(this).addClass("d-none");
                $(`.save-task${taskId}`).addClass("d-none");
            });

            // Save edited task title
            $(".save-task").click(function() {
                var taskId = $(this).data("id");
                var taskTitle = $(`tr[data-task-id="${taskId}"] .editable`).text();
                let url = "{{ route('tasks.update', ':id') }}";
                url = url.replace(':id', taskId);
                $.ajax({
                    type: 'PUT',
                    url: url, 
                    data: {
                        _token: "{{ csrf_token() }}",
                        title: taskTitle
                    },
                    success: function(response) {
                        saveTasksToLocalStorage(response.tasks);

                        $(`.save-task${taskId}, .cancel-edit${taskId}`).addClass("d-none");
                    },
                    error: function(xhr, status, error) {

                        console.error(xhr.responseText);
                    }
                });
            });

            //dellete ajax function 

            $('.delete-task').click(function() {
                var taskId = $(this).data('id');
                deleteTask(taskId);
            });
            function deleteTask(taskId) {
                let url = "{{ route('tasks.destroy', ':id') }}";

                url = url.replace(':id', taskId);
                $.ajax({
                    url: url, 
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        saveTasksToLocalStorage(response.tasks);
                        sessionStorage.setItem('message', response.message);

                        // Reload the page
                        sessionStorage.setItem('reloadAfterPageLoad', 'true');
                        window.location.reload();
                    },
                    error: function() {
                        alert('An error occurred while deleting the task.');
                    }
                });
            }
        });
    </script>
@endsection
