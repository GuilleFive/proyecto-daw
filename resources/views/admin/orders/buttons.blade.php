<button data-order="{{json_encode($order)}}" class="btn button-primary-dark btn-sm orders-view-btn"><i
        class="fa fa-eye"></i></button>
@if($order->delivery_date === null)
    <button data-order="{{json_encode($order)}}" class="btn btn-success btn-sm orders-deliver-btn"><i
            class="fa fa-box"></i></button>
@endif
