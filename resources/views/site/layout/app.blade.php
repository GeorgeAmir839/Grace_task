<!doctype html>
<html lang="en">
@includeIf('site.layout.components.head')

<body>

    <div class="mt-5">
        <div class="alert alert-danger error_from_ajax d-none pb-0">

        </div>
        <div class="alert alert-success success_from_ajax d-none pb-0">

        </div>
        @if (Session::has('danger'))
            <div class="alert alert-danger m-5" role="alert">
                {{ Session::get('danger') }}
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success m-5" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        @yield('content')
    </div>


</body>
@includeIf('site.layout.components.script')

</html>
