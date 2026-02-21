<script>
    import { onMount, onDestroy } from 'svelte';
    import { baseURLStore, tagsStore, updateProducts ,productsStore, removeProduct } from './stores/store-products.js'
    import 'select2';
    import jquery from 'jquery';
    let { mode, passedProduct } = $props()

    let Select2Element;
    let cost = $state(0.01);
    let discount = $state(0);
    let status = $state(0);
    let product = $state(0);
    let modalContainer;
    let imageItemsCount = $state(0);
    let lengthObserver;
    let tagNames;
    let tags;
    let checkboxLabel = $state(true);
    let undoChangesInfo = $state({ name: '', price: 0, description: '' });

    $effect(() => 
    {
        const id = passedProduct;
    
        const unsubscribe = productsStore.subscribe(list => 
        {
            product = list.find(p => p.id === id) ?? null;
        });
        return unsubscribe; //return runs whenever dependancies change or when unmounted/destroyed
        //subscribe returns a function, calling that function undoes the subscription
    });

    $effect(() => 
    {
        if(product)
        {
            status = product.status;
            console.log("status: ", status);

            undoChangesInfo.name = product.name;
            undoChangesInfo.price = product.price;
            undoChangesInfo.discount_percentage = product.discount_percentage;
            undoChangesInfo.description = product.description;
            undoChangesInfo.status = product.status;
        };
    });

    $effect(() => 
    {
        if(cost < 0.01 || !cost) 
        {
            cost = 0.01
        }
        cost = Number(cost.toFixed(2));
    });

    $effect(() => 
    {   
        console.log(discount);
        if(discount < 0)
        {
            discount = 0;
        }
        else if (discount >= 100)
        {
            discount = 0
            notify("Discount cannot exceed 100%");
        }
        discount = Math.round(discount);
    });

    onDestroy(() => 
    {
        lengthObserver.disconnect();
    });

    onMount(() => 
    {
        const editorModal = document.getElementById("EditorModalWindow");

        let attributes = { childList: true }
        let lengthObserver = new MutationObserver(() => 
        {
            imageItemsCount = modalContainer.querySelectorAll('.product-image-item').length;
            console.log("updated modal container children length: ", imageItemsCount);
        });
        lengthObserver.observe(modalContainer, attributes);

        if(editorModal)
        {
            editorModal.addEventListener('change', (e) => 
            {
                let inputElement = e.target;
                if(!inputElement.matches('input')) return; //if clicked outside, 

                let parentContainer = inputElement.parentElement;
                let imageContainer = parentContainer.querySelector("img");

                if(inputElement)
                {
                    imageContainer.src = URL.createObjectURL(inputElement.files[0])
                }
            });
        }
        
        jQuery(Select2Element).select2(
        {
            width: '100%',
            placeholder: 'Select Tags'
        });

        if(product)
        {
            cost = parseFloat(product.price);
            discount = parseFloat(product.discount_percentage);

            jQuery.ajax(
            {
                url: 'GetItemTags',
                method: 'GET',
                data: { item_id: product.id },
                dataType: 'json',
                contentType: 'json',

                success: function (success) 
                {
                    const el = jQuery(Select2Element);
                    const tagNames = (success.data || []).map(t => t.name);
                    const tagValues = tagNames.map(name => el.find("option").filter(function () { return jQuery(this).text().trim() === name; }).val()).filter(Boolean);
                    el.val(tagValues).trigger("change.select2");
                },

                error: function(jqXHR)
                {
                    let message = jqXHR.responseJSON.message;
                    notify(message);
                }
            })        
        }
    });
    
    function syncCheckbox(input)
    {
        let checkbox = input.checked;
        checkboxLabel = checkbox == true ? true : false;
    }
    
    function imageSection()
    {
        let parentElementDiv = document.getElementById("modal-body-inner");

        let addition = 
        `
        <div class="image-addition-section product-image-item p-2 gap-2 col-sm-6 col-md-4 d-flex flex-column align-items-end">
            <button type="button" class="btn-close" aria-label="Close"></button>
            <button class="w-100 h-100 border border-1 btn btn-light shadow d-flex flex-column justify-content-center gap-2" style="min-height: 15vh;">
                <img class="rounded-1" src="${'images/defaultImage.png'}">
            </button>
            <input type="file" name="productImage" class="shadow form-control" accept="image/*">
            <select class="img-slot-selection form-select">
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
                <option value="4">Four</option>
                <option value="5">Five</option>
                <option value="6">Six</option>
            </select>
        </div>
        `

        parentElementDiv.insertAdjacentHTML("afterbegin", addition);

        let section = parentElementDiv.firstElementChild;
        let closeButton = section.querySelector('.btn-close')

        closeButton.addEventListener("click", (e) => 
        {
            e.preventDefault();
            closeImageSection(e.currentTarget);
        });

    };

    function restoreModal()
    {
        modalContainer.querySelectorAll(".image-addition-section").forEach(element => element.remove());
        modalContainer.querySelectorAll(".product-existing-image").forEach(item => 
        {
            let originalImage = item.querySelector('.product-data').dataset.img;
            item.querySelector('img').src = 'images/productsImages/' + originalImage;
            item.querySelector('input[type="file"]').value = '';
        });
        console.log("unga");
    };

    function closeImageSection(image)
    {
        let parentElement = image.closest('.product-image-item');
        
        if(parentElement.classList.contains('product-existing-image'))
        {
            let productData = parentElement.querySelector('.product-data');
            let productId = productData.dataset.itemId;
            let productImage = productData.dataset.img;
            let productSlot = productData.dataset.slot;
            console.log(productId, productImage);
            removeProduct(productId, productImage);

            jQuery.ajax(
                {
                url: 'deleteProductImage',
                method: 'DELETE',
                data: JSON.stringify({id: productId, img: productImage, slot: productSlot}),
                contentType: 'application/json',
                dataType: 'json',

                success: function(response)
                {
                    notify(response.message);
                },
                error: function(jqXHR)
                {
                    let message= jqXHR.responseJSON?.message;
                    notify(message);
                }
            });
            return;    
        }
        else
        {
            parentElement.remove();
        };
    }

    function updateImages()
    {
        let updateArray = [];
        let upload;
        let modalContainer = document.getElementById("modal-body-inner");
        let items = Array.from(modalContainer.querySelectorAll(':scope > .product-image-item'));
        
        for(let item of items)
        {
            let rowId = null;
            let itemImgFile = null;
            let oldImgName = null;

            let slot = item.querySelector('select').value;
            let productId = item.parentElement.querySelector('.product-id').dataset.itemId;
            itemImgFile = item.querySelector('input[type="file"]')?.files[0];

            let matchingItems = items.filter(arrayItem => arrayItem.querySelector('select').value === slot);
            if(matchingItems.length > 1)
            {
                notify("Product images must have unique slots.")
                return;
            }

            if(item.classList.contains('product-existing-image'))
            {
                rowId = item.querySelector('.product-data').dataset.rowId;
                oldImgName = item.querySelector('.product-data').dataset.img;
            }

            if(!itemImgFile && !rowId)
            {
                notify("New additions to the product's gallery must contain an image.");
                return;
            }

            if(itemImgFile || slot)
            {   
                upload = { product_id: productId, slot: slot, img_file: itemImgFile}
                if(rowId && oldImgName)
                {
                    upload.id = rowId;
                    upload.oldImgName = oldImgName;
                }
                updateArray.push(upload)
            }
        }
        let formData = new FormData()

        updateArray.forEach((item, i) => 
        {
            formData.append(`items[${i}][product_id]`, item.product_id);
            formData.append(`items[${i}][slot]`, item.slot);

            if(item.id != null) formData.append(`items[${i}][id]`, item.id);
            if(item.oldImgName) formData.append(`items[${i}][old_img_name]`, item.oldImgName);
            if(item.img_file instanceof File  && item.img_file.size > 0) formData.append(`items[${i}][img_file]`, item.img_file);
        });

        formData.forEach((value, key) => 
        {
            console.log("FORM Key: " ,key , " : " , value);
        });

        if(Array.from(formData.keys()).length === 0)
        {
            notify("Nothing to update.");
            return;
        }

        jQuery.ajax(
        {
            url: 'updateImage',
            method: 'POST',
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,

            success: function(response)
            {
                let updateProduct = product;
                updateProduct.images = response.updatedImages;

                console.log("ajax request updated products:" , $state.snapshot(updateProduct));
                updateProducts($state.snapshot(updateProduct));
                restoreModal();
                notify(response.message);
            },

            error: function(jqXHR) //422 status code triggers error(xhr). the parsed error body (if json) is storedin xhr.responseJSON
            {
                let msgElement = jqXHR.responseJSON.message;
                notify(msgElement);
            },
        })
    }

    function undoChanges()
    {
        let name = document.getElementById("productNameInput");
        let Price = document.getElementById("productPriceInput");
        let description = document.getElementById("productDescriptionInput");
        let status = document.getElementById("status");
        let discount = document.getElementById("productDiscountInput");

        name.value = undoChangesInfo.name;
        jQuery(Select2Element).val(tagNames).trigger('change');
        Price.value = undoChangesInfo.price;
        description.value = undoChangesInfo.description;
        status.checked = undoChangesInfo.status;
        discount.value = undoChangesInfo.discount_percentage;
        checkboxLabel = undoChangesInfo.status == "1" ? true : false;
    }

    function updateSelection()
    {
        let form = document.querySelector("#productsForm");
        let inputContainer = form.querySelector("#basic-input-container");
        let updateItems = inputContainer.querySelectorAll(".product-form-input");
        let inputObject = {};

        updateItems.forEach((item) => 
        {   
            let itemValue = item.value;
            if(item.name === "status")
            {
                console.log("yes: " , item);
                itemValue = item.checked ? "1" : "0";
            }
            if(item.name === "tags")
            {
                itemValue = jquery(Select2Element).val();
                console.log("TAGS: ", tags);
                console.log("RAAAAGH: ", jquery(Select2Element).val());
            }
            console.log(itemValue);
            inputObject[item.name] = itemValue ?? "";
        });
        let formatedArray = [];
        formatedArray.push(JSON.parse(JSON.stringify(inputObject)));
        console.log("FORMATED ARRAY: ", formatedArray);

        jquery.ajax({
            url: 'changeProducts',
            method:'PATCH',
            dataType: 'json',
            data: JSON.stringify(formatedArray),
            contentType: 'application/json',

            success: function(response)
            {
                let message = response.message;
                notify(message);
                inputObject.images = product?.images;
                updateProducts(inputObject);
            },
            error: function(jqXHR)
            {
                let message = jqXHR.responseJSON.message;
                notify(message);
            }
        })
    }
</script>

{#key mode}
    {#if mode === 'edit'}
    <div id="adminForm" class="w-100 min-vh-100 d-flex flex-row justify-content-center align-items-stretch gap-3 p-3">
        <form on:submit|preventDefault={addItem} class="flex-grow-1 d-flex justify-content-center flex-row gap-3 m-0" id="productsForm" method="post" enctype="multipart/form-data">

            <div id="basic-input-container" class="d-flex flex-column justify-content-start align-items-center h-100 w-50 p-3 shadow-sm bg-white bg-gradient rounded-2 border">
                <input name="id" class="product-form-input d-none" value={product.id}>

                <div class="w-100 border-0 rounded-0 border-bottom mb-3 pb-2 d-flex justify-content-between align-items-center">
                    <span class="fs-5 fw-semibold text-blue-gray d-inline-flex align-items-center gap-2"><i class="fa-solid fa-box-open opacity-75"></i><span>Product Details</span></span>
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis border">Editing</span>
                </div>

                <div class="mb-3 w-100 fs-6 fw-semibold text-blue-gray">
                    <i class="fa-solid fa-signature mx-1 fs-5"></i><label for="productNameInput" class="form-label">Product Name</label>
                    <input type="text" id="productNameInput" name="name" class="product-form-input form-control" placeholder="Name…" required minlength="2" maxlength="80" value={product.name}>
                </div>

                <div class="mb-3 w-100 fs-6 fw-semibold text-blue-gray">
                    <i class="fa-solid fa-tags mx-1 fs-5"></i><label for="tagsSelection" class="form-label">Tags</label>
                    <select class="product-form-input border border-opacity-25 rounded-1 w-100" name="tags" id="tagsSelection" multiple bind:this={Select2Element}>
                        {#each $tagsStore as tag}
                            <option value={tag.id}>{tag.name}</option>
                        {/each}
                    </select>
                </div>

                <div class="w-100 fs-6 fw-semibold text-blue-gray">
                    <i class="fa-solid fa-money-bill mx-1 fs-5"></i><label for="productPriceInput" class="text-start form-label">Price</label>
                </div>

                <div id="productPriceDecreas" class="mb-3 d-flex flex-row w-100 input-group shadow-sm">
                    <span class="fw-semibold input-group-text text-blue-gray">€</span>
                    <input name="price" id="productPriceInput" class="product-form-input form-control text-start no-focus-outline no-spin" bind:value={cost} min="0.01" step="0.01" type="number" required>
                    <button class="btn btn-outline-success" type="button" on:click|preventDefault={() => cost = Number((cost + 0.01).toFixed(2))} title="Increase price"><i id="priceActionIcon" class="fa-solid fa-plus"></i></button>
                </div>

                <div class="w-100 fs-6 fw-semibold text-blue-gray">
                    <i class="fa-solid fa-piggy-bank mx-1 fs-5"></i><label for="productDiscountInput" class="text-start form-label">Discount Percentage</label>
                </div>

                <div class="mb-3 d-flex flex-row w-100 input-group shadow-sm">
                    <span class="fw-bold input-group-text text-blue-gray">%</span>
                    <input bind:value={discount} id="productDiscountInput" name="discount_percentage" type="number" min="0" max="99.9" step="0.01" class="product-form-input form-control text-start no-focus-outline no-spin">
                </div>

                <div class="mb-3 w-100 text-dark p-0 rounded-2 border shadow-sm overflow-hidden flex-grow-1 d-flex flex-column">
                    <div class="w-100 d-flex justify-content-start align-items-center text-blue-gray p-2 bg-light bg-gradient border-bottom flex-shrink-0">
                        <i class="fa-solid fa-book me-2 fs-5"></i><label for="productDescriptionInput" class="form-label m-0 fw-semibold">Description</label>
                    </div>

                    <div class="p-2 flex-grow-1">
                        <textarea id="productDescriptionInput" name="description" class="product-form-input form-control border-0 no-focus-outline p-0 w-100 h-100" placeholder="Description…" bind:value={product.description}></textarea>
                    </div>
                </div>

                <div class="w-100 fs-6 text-blue-gray fw-semibold">
                    <i class="fa-solid fa-clipboard-check fs-5 mx-1"></i><label class="text-start form-label">Product Status</label>
                </div>

                <div class="w-100 fs-6 fw-semibold text-blue-gray form-check form-switch d-flex align-items-center gap-2">
                    <input id="status" checked={status == "1" || status == 1} on:change={(e) => syncCheckbox(e.currentTarget)} type="checkbox" name="status" class="rounded-1 form-check-input product-form-input">
                    <span>Status:</span>
                    <span class={checkboxLabel === true ? 'text-success' : 'text-danger'}>{checkboxLabel === true ? 'Enabled' : 'Disabled'}</span>
                </div>

            </div>

            <div class="d-flex flex-column justify-content-start align-items-center h-100 w-25 gap-3">

                <button type="button" data-bs-toggle="modal" data-bs-target="#EditorModalWindow" class="flex-grow-1 p-0 m-0 no-focus-outline btn w-100">
                    <div class="shadow-sm h-100 w-100 rounded-2 overflow-hidden border bg-white">
                        <div class="fs-6 text-light d-flex justify-content-center align-items-center flex-row custom-blue h-25 w-100 fw-semibold gap-2">
                            <i class="fa-solid fa-image"></i><span>Product Images</span>
                        </div>

                        <div class="p-2 h-75 w-100 bg-white">
                            <div class="w-100 h-100 rounded-2 border border-dashed border-success border-opacity-25 overflow-hidden d-flex justify-content-center align-items-center bg-light">
                                <img class="w-100 h-100 object-fit-cover" alt="Product preview" src={product?.images?.[0]?.img ? 'images/productsImages/' + product?.images?.[0]?.img : 'images/defaultImage.png'}>
                            </div>
                        </div>
                    </div>
                </button>

                <button type="button" class="w-100 btn btn-success shadow-sm fw-semibold rounded-2 d-inline-flex justify-content-center align-items-center gap-2" on:click|preventDefault={() => updateSelection()}>
                    <i class="fa-solid fa-floppy-disk"></i><span>Save Changes</span>
                </button>

                <div id="undo-changes-container" class="input-group w-100 shadow-sm">
                    <span class="input-group-text p-2 bg-light"><i id="undoChangesIcon" class="fa-solid fa-rotate-left"></i></span>
                    <button type="button" id="undoChangesButton" on:click|preventDefault={() => undoChanges()} class="btn btn-outline-secondary border-dashed flex-fill fw-semibold">Undo Changes</button>
                </div>

                <a href="adminPanel" class="w-100 text-decoration-none mt-auto">
                    <button type="button" class="w-100 shadow-sm gradient-custom-red border-0 rounded-2 text-light fw-semibold d-inline-flex justify-content-center align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i><span>Return</span>
                    </button>
                </a>

            </div>
        </form>
    </div>
    {:else}
    {/if}
{/key}


<div id="EditorModalWindow" class="modal" role="button" on:click|self={() => restoreModal()}>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editor</h5>
        <button on:click|preventDefault={() => restoreModal()} type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modal-body-inner" bind:this={modalContainer} class="row align-items-stretch">

            <div class="product-id d-none" data-item-id={product.id}></div>
            {#if product}
                {#each product.images ?? [] as item (item.id ?? item.img)}

                <div class="product-existing-image product-image-item p-2 gap-2 col-sm-6 col-md-4 d-flex flex-column align-items-end">
                    <div class="product-data d-none" data-row-id={item.id} data-item-id={item.item_id} data-img={item.img} data-slot={item.slot}></div>
                    <button on:click|preventDefault={(e) => closeImageSection(e.currentTarget)} type="button" class="btn-close" aria-label="Close"></button>
                    <button class="w-100 h-100 border border-1 btn btn-light shadow d-flex flex-column justify-content-center gap-2" style="min-height: 15vh;">
                        <img class="rounded-1" src={'images/productsImages/' + item.img}>
                    </button>
                    <input type="file" name="productImage" class="shadow form-control" accept="image/*">
                    <select class="img-slot-selection form-select" bind:value={item.slot}>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        <option value="4">Four</option>
                        <option value="5">Five</option>
                        <option value="6">Six</option>
                    </select>
                </div>

                {/each}
            {/if}

            {#if imageItemsCount < 6}
            <div id="image-addition-button" class="p-2 col-sm-6 col-md-4 d-flex">
                <button on:click|preventDefault={(e) => imageSection(e.currentTarget, $baseURLStore)} class="w-100 h-100 border border-1 btn btn-light shadow d-flex flex-column justify-content-center gap-2" style="min-height: 15vh;">
                    <i class="fs-1 text-secondary text-shadow fa-solid fa-plus"></i>
                    <span class="fw-light">Add Image</span>
                </button>
            </div>
            {/if}

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" on:click|preventDefault={() => updateImages()}>Save changes</button>
      </div>
    </div>
  </div>
</div>