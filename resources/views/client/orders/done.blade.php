@extends('layouts.app')

@section('content')
    <div class="container">
        Done
    </div>
    @push('scripts')
        <script defer>
            localStorage.clear();
        </script>
    @endpush
@endsection
