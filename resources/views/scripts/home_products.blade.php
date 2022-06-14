<script defer>


    const formSearch = document.querySelector('#form-search');
    const filtersButton = document.querySelector('.close-filters');
    const sidebar = document.querySelector('.sidebar');
    filtersButton.addEventListener('click', () => sidebar.classList.toggle('hide'));

    formSearch.addEventListener('submit', e => {
        e.preventDefault();
        document.querySelector('#length') ? document.querySelector('#length').value = 17 : ''
        document.querySelector('#order') ? document.querySelector('#order').value = 'new' : ''
        document.querySelector('#category') ? document.querySelector('#category').value = '' : ''
        loadProducts();
    })

    document.addEventListener('click', e => {
        if (e.target !== sidebar && e.target !== filtersButton && e.target !== filtersButton.children[0]) {
            sidebar.classList.add('hide');
        }
    });

    window.addEventListener('load', () => {
        loadProducts();
        loadCategories();
    })

    function loadCategories() {
        $.ajax(
            {
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },
                url: '{{route('home.categories')}}',
                type: 'POST',
            }
        ).done(
            response => {
                document.querySelector('.categories').innerHTML = response;

                document.querySelectorAll('.filter-category').forEach(element => {

                    element.addEventListener('click', () => {
                        document.querySelector('#category').value = element.dataset.category;
                        document.querySelector('#length').value = 18;
                        document.querySelector('.selected')?.classList.remove('selected');
                        element.classList.add('selected');
                        loadProducts();
                    })
                })

                document.querySelectorAll('.order-button').forEach(element => {
                    element.addEventListener('click', () => {
                        document.querySelector('#order').value = element.dataset.order;
                        document.querySelector('#length').value = 18;
                        loadProducts();
                    })
                })

                filtersButton.disabled = false;

            });
    }

    function loadProducts() {

        document.body.style.overflow = "hidden";
        document.querySelector('.spinner-wrapper').classList.remove('hide');

        $.ajax(
            {
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },
                url: '{{route('home.products')}}',
                data: {
                    'length': () => document.querySelector('#length')?.value,
                    'order': () => document.querySelector('#order')?.value,
                    'category': () => document.querySelector('#category')?.value,
                    'name': () => document.querySelector('#search-input')?.value,
                },
                type: 'POST',
            }
        ).done(
            response => {
                const productsHome = document.querySelector('.products-home');
                const length = JSON.parse(JSON.parse(response).length);
                const order = JSON.parse(response).order;
                const category = JSON.parse(response).category;
                const total = JSON.parse(response).total;
                const products = JSON.parse(JSON.parse(response).products);
                let productAisle = '';
                if (total > 0) {
                    for (const product of products) {
                        productAisle += `<div class="col-12 col-md-6 col-xl-4 mb-5"><div class="card h-100"><a href="{{url('products/view')}}/${product.id}" class="text-decoration-none"><img class="card-img-top dark-background" src="" alt="Card image cap"></a><div class="card-body d-flex flex-wrap justify-content-center align-content-around h-100"><h5 class="card-title">${product.name}</h5><p class="card-text w-100">${product.description}</p>`;
                        if (product.stock < 6) {
                            if (product.stock === 1) {
                                productAisle += `<div class="align-self-end d-flex justify-content-between w-100"><p class="card-text text-danger" title="¡¡Queda una unidad!!">Solo ${product.stock} unidad</p>`;
                            } else {
                                productAisle += `<div class="align-self-end d-flex justify-content-between w-100"><p class="card-text text-danger"title="¡Quedan pocas unidades!">Solo ${product.stock} unidades</p>`;
                            }
                        } else {
                            productAisle += `<div class="align-self-end d-flex justify-content-between w-100"><p class="card-text text-success">En stock</p>`;
                        }
                        productAisle += ` <p class="card-text h3">${product.price}€</p><button type="button" data-product='${JSON.stringify(product)}' class="btn button-primary-outline-dark align-self-end float-md-end add-cart"><i class="fa fa-cart-plus"></i></button>`;
                        productAisle += '</div></div></div></div>';

                    }
                    if (length !== total)
                        productAisle += '<button type="button" class="btn button-primary-dark load-button mb-3">Cargar más</button>';

                    productAisle += `<input id="length" type="hidden" name="length" value="${length}">`;
                    productAisle += `<input id="order" type="hidden" name="order" value="${order}">`;
                    productAisle += `<input id="category" type="hidden" name="category" value="${category}">`;
                } else {
                    productAisle += `<div class="card-body"><p class="card-title h5"> No se ha encontrado ningún producto<p></div>`;

                }

                setTimeout(() => {
                    productsHome.innerHTML = productAisle;
                    document.body.style.overflow = "";
                    assignEvents();
                    document.querySelector('.spinner-wrapper').classList.add('hide');
                    document.querySelector('#search-input').disabled = false;
                    document.querySelector('.button-search').disabled = false;
                }, 300);


            });
    }

    function assignEvents() {
        document.querySelectorAll('.add-cart').forEach(element => {
            element.addEventListener('click', () => {

                const arrayProducts = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : [];
                const product = element.dataset.product;

                if (arrayProducts.length === 0)
                    arrayProducts.push({'product': product, 'amount': 1});
                else if (checkNewProduct(arrayProducts, product))
                    arrayProducts.push({'product': product, 'amount': 1});


                localStorage.setItem('cart', JSON.stringify(arrayProducts));

                changeNumberItem();
            })
        })

        document.querySelector('.load-button')?.addEventListener('click', () => {
            const length = document.querySelector('#length');
            length.value = parseInt(length.value) + 10;
            loadProducts();
        });


    }

</script>
