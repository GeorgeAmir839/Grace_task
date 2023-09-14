<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5 class="m-5 h6">{{ trans('Task Create') }}</h5>
        </div>
        <div class="card-body">
            <form id="addTaskForm">
                @csrf

                <div class="form-group row">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Title</label>
                        <textarea name="title" class="form-control" id="exampleFormControlTextarea1"
                            placeholder="Ex.Prepare your reports"rows="3"></textarea>
                        @error('title')
                            <span class="text-danger">{{ trans($message) }}</span>
                        @enderror
                    </div>
                </div>



                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
