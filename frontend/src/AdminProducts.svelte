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

    <div class="d-flex flex-column min-vh-100 w-100" id="admin-panel-inner-wrapper">

        <div id="administrator-products-filter" class="col-12 ms-2 ">
            <button class="fw-semibold btn bg-none">Filter ^</button>
        </div>

        {#each displayProducts as product}
        <div id="{product.name + '-Wrapper-Container'}" class="col-sm-12 col-lg-6 p-2 m-0">
            <div class="w-100 rounded-4 btn btn-light vh-10 bg-light bg-gradient border shadow-sm p-2 d-flex flex-row gap-3">

                    <input type="hidden" id="{product.name + '-id-input'}" value={product.id}>

                    
                    <button on:click|preventDefault={() => openProductEditor('edit', product)} class="rounded btn p-0 m-0 d-flex justify-content-center align-items-center w-10 h-100">
                        <img class="shadow-sm d-flex align-items-center justify-content-center rounded img-fluid h-100" alt="{product.name}" src="{product?.images?.[0]?.img ? 'images/productsImages/' + product?.images[0]?.img : 'images/defaultImage.png'}">
                    </button>
                    

                    <div id="{product.name + '-name-container'}" class="d-flex flex-column justify-content-end align-items-start h-100 ">
                        <label class="form-check-label fw-semibold" for="{product.name + '-name-input'}">Name</label>
                        <input type="text" id="{product.name + '-name-input'}" placeholder="Name" class="rounded-3 h-50 form-control" value="{product.name}">
                    </div>
                    
                    <div id="{product.name + '-discount-container'}" class="d-flex flex-column justify-content-end align-items-start h-100">
                        <label class="form-check-label fw-semibold" for="{product.name + '-discount-input'}">Discount Percentage</label>
                        <input type="text" id="{product.name + '-discount-input'}" class="rounded-3 h-50 form-control" min="0" max="99" step="1" value={product.discount_percentage} placeholder="Discount" required>
                    </div>

                    <div class="form-check form-switch d-flex flex-column justify-content-end align-items-start h-100 p-0" style="width: 15%">
                        <label class="form-check-label fw-semibold text-start" for="{product.name + '-status-input'}">Status<br><span class:text-danger={product.status === '0'} class:text-success={product.status === '1'} class="fs-7 fw-semibold">{product.status === '1' ? "In Stock" : "Out of Stock" }</span></label>
                        <input checked="{product.status === '1'}" id="{product.name + '-status-input'}" class="h-50 w-100 m-0 rounded-3 form-check-input" type="checkbox" role="switch"  style=" margin-left: 0 !important;">
                    </div>
            </div>
        </div>
        {/each}

        <div class="col-12 d-flex justify-content-center mt-auto px-3 pb-3">
            <div class="w-100 d-flex justify-content-center">
                <div class="w-100 bg-light bg-gradient border-top shadow-sm rounded-top-3 px-3 px-md-4 py-3 d-flex justify-content-between align-items-center gap-3" style="max-width: 1100px;">

                    <div class="d-flex align-items-center gap-3">
                        <span class="small text-muted fw-semibold d-none d-md-inline d-flex align-items-center gap-2"><i class="bi bi-layers opacity-75"></i><span>Navigation</span></span>

                        <div class="input-group input-group-sm shadow-sm" style="width: 320px;">
                            <button on:click={() => previousPage()} class="btn btn-outline-primary rounded-0 rounded-start-3 fw-semibold d-inline-flex align-items-center gap-2"><i class="bi bi-chevron-left"></i><span>Prev</span></button>
                            <input type="number" class="form-control no-focus-outline no-spin text-center" min="1" max={maxPage} placeholder="Page" bind:value|number={page} on:input={() => clamp(page)}>
                            <button on:click={() => nextPage()} class="btn btn-outline-primary rounded-0 rounded-end-3 fw-semibold d-inline-flex align-items-center gap-2"><span>Next</span><i class="bi bi-chevron-right"></i></button>
                        </div>

                        <span class="small text-muted d-none d-lg-inline">of <span class="fw-semibold">{maxPage}</span></span>
                    </div>

                    <button class="btn btn-success shadow-sm fw-semibold rounded-3 px-3 d-inline-flex align-items-center gap-2" on:click={() => updateSelected()}><i class="bi bi-check2-circle"></i><span>Save Changes</span></button>

                </div>
            </div>
        </div>

    </div>