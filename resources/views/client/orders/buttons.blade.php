<a href="{{url('orders/show')}}/{{$order->id}}" class="btn button-primary-dark btn-sm"><i
        class="fa fa-eye"></i></a>
@if($order->delivery_date === null)
    <button data-order="{{json_encode($order)}}" class="btn btn-danger btn-sm orders-cancel-btn"><i
            class="fa fa-times"></i></button>
@endif
