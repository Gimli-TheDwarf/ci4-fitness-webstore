<script>
    import { updateProducts, productsStore, tagsStore } from './stores/store-products.js';
    let { locationSelection } = $props();
    let tags = $state($tagsStore);
    let page = $state(1);
    let pageSize = $state(14);

    let displayProducts = $derived($productsStore.slice((page - 1) * pageSize, (page - 1) * pageSize + 20));
    //0 * 20, 0 * 20 + 20
    // 0, 20. displayproducts = [0 -> 20th element] 
    let maxPage = $state(Math.max(1, Math.ceil($productsStore.length / pageSize)));
    console.log($productsStore);
    
    //$: maxPage = products.length % pageSize !== 0 ? Math.floor(products.length / pageSize) + 1 : products.length / pageSize; more complex stupid version that i made before finding out i could just mathmax everything
    function previousPage()
    {
        if (page != 1)
        {
            page -= 1;
        }
        notify("First Page Reached");
    }

    function nextPage()
    {
        if(page < maxPage)
        {
            page += 1;
        }
        notify("Last Page Reached");
    }

    function clamp()
    {
        if(page > maxPage)
        {
            page = maxPage;
            notify("Last Page Reached");
        }
        else if (!page)
        {
            page = 1;
            notify("First Page Reached");
        }
    }

    function updateSelected()
    {
        let admin = document.getElementById("admin-panel-inner-wrapper");
        let updatedItemsArray = admin.querySelectorAll('[id$="-Wrapper-Container"]')
        let objectField = [];
        let inputField;

        updatedItemsArray.forEach((item) => 
        {
            inputField = item.querySelectorAll("input");
            let obj =
            {
                id: inputField[0]?.value ?? "",
                name: inputField[1]?.value ?? "",
                discount_percentage: inputField[2]?.value ?? "",
                status: inputField[3].checked ? 1 : 0,
            }
            objectField.push(obj);
        });
        console.log(objectField);

        jQuery.ajax({
            
            url: 'changeProducts',
            method: 'PATCH',
            data: JSON.stringify(objectField),
            dataType: 'json', //what datatype will the response be from the server
            contentType: 'application/json', //what datatype we're sending to the server

            success: function(response)
            {
                notify(response.message);
                let updatedProducts = response['data'];
                console.log("UPDATED PRODUCTS: ", updatedProducts)
                console.log("________________________________________")
                updateProducts(updatedProducts);
            },

            error: function(jqXHR)
            {
                let message = jqXHR.responseJSON.message;
                notify(message);
            }
        })
    }

    function openProductEditor(modeSelect, productSelect = {})
    {
        locationSelection('specificProduct', modeSelect, productSelect.id);
    }

</script>

    <div class="d-flex flex-column min-vh-100 w-100 gap-3" id="admin-panel-inner-wrapper">

        <div id="administrator-products-filter" class="homepage-filter-toolbar bg-white border shadow-sm rounded-2 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 w-100">
            <div class="d-flex align-items-center gap-2 text-blue-gray">
                <span class="admin-icon-box d-inline-flex justify-content-center align-items-center rounded-2 bg-orange text-white shadow-sm fs-5">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </span>
                <div>
                    <h1 class="h5 fw-semibold mb-0">Products</h1>
                    <span class="small text-secondary">Edit market status, names, and discounts.</span>
                </div>
            </div>
            <span class="badge rounded-pill bg-light text-blue-gray border px-3 py-2">Page {page} of {maxPage}</span>
        </div>

        <div class="row g-4">
            {#each displayProducts as product}
            <div id="{product.name + '-Wrapper-Container'}" class="col-12 col-xl-6">
                <article class="bg-white border rounded-2 shadow-sm overflow-hidden h-100 d-flex flex-column">
                    <input type="hidden" id="{product.name + '-id-input'}" value={product.id}>

                    <div class="d-flex flex-column flex-md-row h-100">
                        <button type="button" on:click|preventDefault={() => openProductEditor('edit', product)} class="btn p-0 m-0 rounded-0 bg-blue-gray d-flex justify-content-center align-items-center flex-shrink-0 cursor-pointer" style="width: 10rem; min-height: 12rem;">
                            <img class="w-100 h-100 object-fit-contain p-3" alt="{product.name}" src="{product?.images?.[0]?.img ? 'images/productsImages/' + product?.images[0]?.img : 'images/defaultImage.png'}">
                        </button>

                        <div class="flex-grow-1 p-3 d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 border-bottom pb-2">
                                <div class="min-w-0">
                                    <span class="small text-uppercase text-orange fw-semibold">Product</span>
                                    <h2 class="h6 text-blue-gray fw-semibold mb-0 text-truncate">{product.name}</h2>
                                </div>

                                <button type="button" on:click|preventDefault={() => openProductEditor('edit', product)} class="btn btn-sm btn-outline-secondary rounded-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 flex-shrink-0">
                                    <i class="bi bi-pencil-square"></i><span>Edit</span>
                                </button>
                            </div>

                            <div id="{product.name + '-name-container'}" class="w-100">
                                <label class="form-label small fw-semibold text-blue-gray d-inline-flex align-items-center gap-2" for="{product.name + '-name-input'}">
                                    <i class="bi bi-box-seam opacity-75"></i><span>Name</span>
                                </label>
                                <input type="text" id="{product.name + '-name-input'}" placeholder="Name" class="rounded-1 form-control shadow-sm" value="{product.name}">
                            </div>

                            <div class="row g-3 align-items-end">
                                <div id="{product.name + '-discount-container'}" class="col-12 col-sm-6">
                                    <label class="form-label small fw-semibold text-blue-gray d-inline-flex align-items-center gap-2" for="{product.name + '-discount-input'}">
                                        <i class="bi bi-percent opacity-75"></i><span>Discount</span>
                                    </label>
                                    <div class="input-group shadow-sm">
                                        <input type="number" id="{product.name + '-discount-input'}" class="rounded-start-1 form-control" min="0" max="99" step="1" value={product.discount_percentage} placeholder="Discount" required>
                                        <span class="input-group-text text-blue-gray">%</span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <span class="form-label small fw-semibold text-blue-gray d-block mb-2">Status</span>
                                    <div class="border rounded-2 bg-light p-2 d-flex align-items-center justify-content-between gap-3">
                                        <label class="small fw-semibold m-0" for="{product.name + '-status-input'}">
                                            <span class:text-danger={product.status === '0'} class:text-success={product.status === '1'}>{product.status === '1' ? "In Stock" : "Out of Stock" }</span>
                                        </label>
                                        <div class="form-check form-switch p-0 m-0 d-flex align-items-center">
                                            <input checked="{product.status === '1'}" id="{product.name + '-status-input'}" class="m-0 rounded-1 form-check-input shadow-sm" type="checkbox" role="switch" style="margin-left: 0 !important;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            {/each}
        </div>

        <div class="col-12 d-flex justify-content-center mt-auto px-3 pb-3">
            <div class="w-100 d-flex justify-content-center">
                <div class="w-100 bg-light bg-gradient border shadow-sm rounded-2 px-3 px-md-4 py-3 d-flex justify-content-between align-items-center gap-3" style="max-width: 1100px;">

                    <div class="d-flex align-items-center gap-3">
                        <span class="small text-muted fw-semibold d-none d-md-inline d-flex align-items-center gap-2"><i class="bi bi-layers opacity-75"></i><span>Navigation</span></span>

                        <div class="input-group input-group-sm shadow-sm" style="width: 320px;">
                            <button on:click={() => previousPage()} class="btn btn-outline-secondary rounded-0 rounded-start-2 fw-semibold d-inline-flex align-items-center gap-2"><i class="bi bi-chevron-left"></i><span>Prev</span></button>
                            <input type="number" class="form-control no-focus-outline no-spin text-center" min="1" max={maxPage} placeholder="Page" bind:value|number={page} on:input={() => clamp(page)}>
                            <button on:click={() => nextPage()} class="btn btn-outline-secondary rounded-0 rounded-end-2 fw-semibold d-inline-flex align-items-center gap-2"><span>Next</span><i class="bi bi-chevron-right"></i></button>
                        </div>

                        <span class="small text-muted d-none d-lg-inline">of <span class="fw-semibold">{maxPage}</span></span>
                    </div>

                    <button class="btn btn-orange shadow-sm fw-semibold rounded-2 px-3 d-inline-flex align-items-center gap-2" on:click={() => updateSelected()}><i class="bi bi-check2-circle"></i><span>Save Changes</span></button>

                </div>
            </div>
        </div>

    </div>
