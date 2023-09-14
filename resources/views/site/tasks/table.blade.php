<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th class="text-right">Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $key => $task)
            <tr class="align-items-center" data-task-id="{{ $task['id'] }}">
                <th scope="row">{{ $key + 1 }}</th>
                <td width="55%" class="editable" data-task-id="{{ $task['id'] }}" contenteditable="true">
                    {{ $task['title'] }}

                </td>
                <td class="text-left row ">
                    <div class="col-6">
                        <button class="btn btn-secondary cancel-edit {{ 'cancel-edit' . $task['id'] }} d-none"
                            data-id="{{ $task['id'] }}">X</button>
                        <button class="btn btn-primary save-task {{ 'save-task' . $task['id'] }} d-none"
                            data-id="{{ $task['id'] }}">Edit</button>

                    </div>
                    <div class="col-6">
                        <a class="btn btn-success  btn-sm"
                            href="{{ route('tasks.show', ['task' => $task['id']]) }}">
                            show
                        </a>

                        <button class="btn btn-danger btn-sm delete-task"
                            data-id="{{ $task['id'] }}">Delete</button>
                    </div>
                </td>
            </tr>
        @endforeach

    </tbody>
</table>
<div class="aiz-pagination">
    {{ $tasks->links() }}
</div>