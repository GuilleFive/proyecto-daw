@extends('layouts.app')
@section('content')
    <div class="container-fluid px-5 pb-5 mt-5">
        <h2 class="mb-4">{{__('Lista de clientes')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('Nombre')}}</th>
                <th>{{__('Usuario')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Teléfono')}}</th>
                <th>{{__('Acción')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            $(function () {

                var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.list') }}",
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'username', name: 'username'},
                        {data: 'email', name: 'email'},
                        {data: 'phone', name: 'phone'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        },
                    ]
                });

            });
        </script>
    @endpush
@endsection
