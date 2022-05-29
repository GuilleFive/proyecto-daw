@extends('layouts.app')
@section('content')
    <div class="container-fluid px-5 pb-5 mt-5">
        <h2 class="mb-4">{{__('Lista de productos')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('Nombre')}}</th>
                <th>{{__('Descripción')}}</th>
                <th>{{__('Stock')}}</th>
                <th>{{__('Categoría')}}</th>
                <th>{{__('Precio (€)')}}</th>
                <th>{{__('Acción')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <a href="{{route('products.create')}}" class="btn btn-outline-primary float-end"><i class="fa fa-plus-circle"> </i>{{__(' Añadir producto')}}</a>
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
                    ajax: "{{ route('products.list') }}",
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'description', name: 'description'},
                        {data: 'stock', name: 'stock'},
                        {data: 'category', name: 'category'},
                        {data: 'price', name: 'price'},
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
