<button type="button" class="close-filters btn button-primary-dark" disabled><i class="fa fa-bars"></i></button>
<div class="sidebar hide">
    <p class="h2 mb-3">{{__('Filtros')}}</p>
    <hr>
    <div class="filters-container">
        <div class="categories d-flex flex-column mb-5">

        </div>
        <div class="orders d-flex flex-column mb-5">

            <p class="h5 ms-3 mb-4">{{__('Ordenar')}}</p>

            <div class="price-order mb-4 ms-5 d-flex flex-wrap">
                <p class="mb-2 w-100">{{__('Precio')}}</p>
                <button type="button" data-order="cheap" class="btn button-primary-outline-dark order-button m-2">De
                    menor a mayor (€)
                </button>
                <button type="button" data-order="expen" class="btn button-primary-outline-dark order-button m-2">De
                    mayor a menor (€)
                </button>
            </div>
            <div class="new-order mb-4 ms-5 d-flex flex-wrap">
                <p class="mb-2 w-100">{{__('Antigüedad')}}</p>
                <button type="button" data-order="new" class="btn button-primary-outline-dark order-button m-2">Novedad
                    primero
                </button>
                <button type="button" data-order="old" class="btn button-primary-outline-dark order-button m-2">Novedad
                    último
                </button>
            </div>
        </div>
    </div>
</div>
