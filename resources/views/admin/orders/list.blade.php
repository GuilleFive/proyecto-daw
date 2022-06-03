@extends('layouts.app')
@section('content')
    <div class="container pb-5">
        <h2 class="mb-4">{{__('Lista de pedidos')}}</h2>
        <table class="table table-bordered table-striped table-dark yajra-datatable">
            <thead>
            <tr>
                <th>{{__('ID')}}</th>
                <th>{{__('Usuario')}}</th>
                <th>{{__('Nº de Productos')}}</th>
                <th>{{__('Dirección')}}</th>
                <th>{{__('Fecha pedido')}}</th>
                <th>{{__('Fecha entrega')}}</th>
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

                const table = $('.yajra-datatable').DataTable({
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('orders.list') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'user', name: 'user_id'},
                        {data: 'product', name: 'product_id'},
                        {data: 'address', name: 'address_id'},
                        {data: 'order_date', name: 'order_date'},
                        {data: 'delivery_date', name: 'delivery_date'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        },
                    ]
                });

        </script>
    @endpush
@endsection
