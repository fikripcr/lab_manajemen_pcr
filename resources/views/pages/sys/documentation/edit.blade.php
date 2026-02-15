@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.sys.empty' : 'layouts.sys.app')

@section('title', 'Edit Documentation: ' . $page)



@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Documentation: {{ $page }}</h5>
            <div>
                <x-tabler.button type="back" :href="route('sys.documentation.show', $page)" class="me-2" />
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('sys.documentation.update', $page) }}">
                @csrf
                @method('PUT')

                <x-tabler.form-textarea type="editor" id="content" name="content" label="Content" :value="old('content', $content)" height="500" />

                <div class="d-flex justify-content-end gap-2">
                    <x-tabler.button type="back" :href="route('sys.documentation.show', $page)" />
                    <x-tabler.button type="submit" />
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
