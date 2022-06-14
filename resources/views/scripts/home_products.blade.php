<script defer>

    const buttonFilters = document.querySelector('.close-filters');
    const sidebar =  document.querySelector('.sidebar');
    buttonFilters.addEventListener('click', () => sidebar.classList.toggle('hide'));

    document.addEventListener('click', e => {
        if(e.target!==sidebar && e.target!==buttonFilters && e.target!==buttonFilters.children[0]){
            sidebar.classList.add('hide');
        }
    });

    function checkNewProduct(arrayProducts, product) {

        for (const element of arrayProducts) {

            if (element.product === product) {
                element.amount++;
                return false;
            }
        }

        return true;
    }

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
                        document.querySelector('#length').value = 17;
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

                buttonFilters.disabled = false;

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
                for (const product of products) {
                    productAisle += `<div class="col-12 col-md-6 col-xl-4 mb-5"><div class="card h-100"><a href="#" class="text-decoration-none"><img class="card-img-top dark-background" src="" alt="Card image cap"></a><div class="card-body d-flex flex-wrap justify-content-center align-content-around h-100"><h5 class="card-title">${product.name}</h5><p class="card-text w-100">${product.description}</p>`;
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
                    productAisle += '<button type="button" class="btn button-primary-dark load mb-3">Cargar más</button>';

                productAisle += `<input id="length" type="hidden" name="length" value="${length}">`;
                productAisle += `<input id="order" type="hidden" name="order" value="${order}">`;
                productAisle += `<input id="category" type="hidden" name="category" value="${category}">`;


                setTimeout(() => {
                    document.querySelector('.spinner-wrapper').classList.add('hide');
                    productsHome.innerHTML = productAisle;
                    document.body.style.overflow = "";
                }, 300);

                assignEvents();

            });
    }

    function assignEvents() {
        document.querySelectorAll('.add-cart').forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
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

        document.querySelector('.load')?.addEventListener('click', () => {
            document.querySelector('#length').value = parseInt(document.querySelector('#length').value) + 10;
            loadProducts()
        });


    }

</script>
