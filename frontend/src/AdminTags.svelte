<script>
    import jquery from 'jquery';
    import {tagsStore, productsStore} from './stores/store-products.js';
    import { onMount } from 'svelte';

    let { locationSelect } = $props();
    let searchText = $state("");
    let tags = $state();

    let secondaryContainerInfo = $state("");
    let itemNameInput = $state("");
    let itemTags = $state("");
    let productsMode = $state(true);
    let productStatus = $state(true);
    let genArrayed = [1,2,3,4,5,6];

    onMount(() => 
    {
        console.log($productsStore);
        tags = $tagsStore;
    });

    function removeTag(tagButton)
    {
        let tagId = tagButton.id;

        jquery.ajax({
            url: 'removeTag',
            method: 'DELETE',
            content: JSON.stringify({id: tagId}),
            dataType: 'json',
            contentType: 'application/json',

            success: function(response)
            {
                notify(response.message);
            },
            error: function(jqXHR)
            {
                let message = jqXHR.responseJSON.message;
                notify(message)
            }
        });
    }

    function searchInput()
    {
        tags = $tagsStore.filter(tag => tag.name.toLowerCase().includes(searchText.toLowerCase()));
    }

    function searchItemTags(item)
    {
        let message = null;
        let matchingItemId = item.id;

        jquery.ajax({
            url: 'GetItemTags',
            method: 'GET',
            dataType: 'json',
            contentType: 'application/json',
            data: {item_id : matchingItemId},

            success: function(success)
            {
                console.log(success.data);
                productsMode = false;
                itemTags = success.data;
                message = success.message;
                notify(message);
            },
            error: function(jqXHR)
            {
                message = jqXHR.responseJSON.message;
                notify(message)
            }
        });
    }

    function searchMatchingItems()
    {
        itemTags = "";
        productsMode = true;
        secondaryContainerInfo = $productsStore.filter(product => product.name.toLowerCase().includes(itemNameInput.toLowerCase()));
    }

    $effect(() => 
    {
        if(itemNameInput == "")
        {
            secondaryContainerInfo = "";
            console.log("products: " , secondaryContainerInfo);
        }
    });

    $effect(() => 
    {
        if(searchText == "")    
        {
            tags = $tagsStore;
        }
    });

    function previewImage(e)
    {
        let input = e.target;
        console.log(input);
        let image = input.files?.[0];
        console.log(image);
        let nearestImage = input.closest("div");
        nearestImage = nearestImage.querySelector("img.img-fluid");
        let previewImageFile = URL.createObjectURL(image);

        if (!image) 
        {
            notify("No image was provided for the preview");
            return;
        }
        nearestImage.src = previewImageFile
    }

    function createProduct(e)
    {
        e.preventDefault(); 
        const form = e.target; //target form
        const formData = new FormData(form); 
        const imageCheck = form.querySelectorAll('input[type="file"][name^="image-"]'); //query selecting all file type inputs who's names start with 'image-'
        
        imageCheck.forEach(element => 
        {
            let file = element.files?.[0]; 
            if(file?.size === 0 || !file || file?.name === "" || file === null)
            {
                formData.delete(element.name);
                return;
            }
            formData.delete(element.name);
            formData.append('images[]', file, file.name);
        });
        const parsedformData = Object.fromEntries(formData.entries());
        console.log(formData.getAll('images[]'));
        console.log("PARSED FORM DATA: " , parsedformData);

        jquery.ajax(
        {
            url: 'AddNewProduct',
            method: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,

            success: function(success)
            {
                notify(success.message);
            },
            error: function(jqXHR)
            {
                notify(jqXHR.responseJSON.message);
            },
        })
    }   

</script>

<div class="d-flex flex-column justify-content-start align-items-center w-100 gap-2">
    <div class="bg-white bg-gradient p-2 rounded-1 border border-dashed w-75 d-flex align-items-center justify-content-start flex-column" style="max-height: 40vh">

        <div class="w-100 d-flex justify-content-center align-items-center flex-column">
            <div class="w-100 border-0 rounded-0 border-bottom mb-2 d-flex justify-content-center">
                <span class="text-center fs-5 fw-semibold p-2 text-blue-gray">Available Tags</span>
            </div>

            <div class="d-flex w-100 justify-content-start align-items-center">
                <div class="input-group mb-2 w-25">
                    <span class="input-group-text shadow-sm fs-5 text-blue-gray fw-semibold"><i class="bi bi-database-add"></i></span>
                    <button type="button" class="shadow-sm form-control btn btn-light border text-start text-secondary" data-bs-target="#global-modal-window" data-bs-toggle="modal" data-mode="add-tag" data-data="none" data-info="Create a New Tag?">Create new tag...</button>
                </div>
            </div>

            <div class="input-group mb-2">
                <span class="text-blue-gray shadow-sm fs-5 input-group-text"><i class="fa-solid fa-tags"></i></span>
                <input placeholder="Name of the tag..." type="text" class="shadow-sm form-control" bind:value={searchText} on:input={() => searchInput()}>
            </div>
        </div>

        <div class="w-100 overflow-auto row d-flex justify-content-center rounded">
            {#if tags?.length != 0}
                {#each tags as tag}
                <div class="d-flex col-12 col-lg-4">
                    <div class="d-flex p-2 fs-5 flex-grow-1 h-100">
                        <button id={tag.id} type="button" class="shadow-sm rounded-end-0 btn btn-danger" data-bs-target="#global-modal-window" data-bs-toggle="modal" data-mode="delete-tag" data-data={tag.id} data-info={`Remove "` + tag.name + `" tag from the database?`}><i class="fw-semibold fa-solid fa-circle-xmark"></i></button>
                        <button id={tag.id +'_'+ tag.name} type="button "class="shadow-sm rounded-start-0 btn btn-light border border-danger text-center flex-grow-1" data-bs-target="#global-modal-window" data-bs-toggle="modal" data-mode="tag-change-name" data-data={tag.id} data-info={`Change Tag's "` + tag.name +`" Name?`}>{tag.name}</button>
                    </div>
                </div>
                {/each}
            {:else}
                <span class="text-center text-dark col-lg-12 col-12 bg-light rounded">No tags available in the database.</span> 
            {/if}
        </div>
    </div>

    <div class="bg-white bg-gradient rounded-1 border w-75 d-flex justify-content-center flex-column">
        
        <div class="w-100 d-flex justify-content-start align-items-center border-bottom mb-3">
            <div class="input-group w-100 p-2">
                <span class="shadow-sm input-group-text fs-5 fw-semibold text-blue-gray"><i class="fa-solid fa-list"></i></span>
                <input placeholder="Item name..." bind:value={itemNameInput} on:input={() => searchMatchingItems()} type="text" class="shadow-sm form-control">
            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center w-100 p-2 gap-2">
            {#key productsMode}
                <div id="additionalContainer" class="m-0 w-100 h-100 overflow-auto d-flex justify-content-center align-items-center row">

                    {#if productsMode == true}
                        {#if secondaryContainerInfo != ""}
                            {#each secondaryContainerInfo as product}
                                <div class="d-flex justify-content-center align-items-center col-6 col-lg-3">
                                    <button id={product.id} on:click={(e) => (searchItemTags(e.currentTarget))} class="w-100 h-100 btn btn-outline-success border-2 fw-semibold">{product.name}</button>
                                </div>
                            {/each}
                        {:else}
                            <span class="text-center text-dark col-lg-12 col-12 bg-light rounded">Enter a name of a product who's tags you want to be listed.</span>
                        {/if}

                    {:else if productsMode == false}
                        {#if itemTags != ""}
                            {#each itemTags as tag}
                                <span class="text-center text-dark col-lg-3 col-6 bg-light rounded border-1 border-dark border-dashed m-2">{tag}</span>
                            {/each}
                        {:else}
                            <span class="text-center text-dark col-lg-12 col-12 bg-light rounded">Product does not contain any tags.</span>
                        {/if}
                    {/if}

                </div>
            {/key}
        </div>

    </div>

    <div class="bg-white bg-gradient p-2 rounded-1 border border-dashed w-75 d-flex justify-content-center flex-column">
        <div class="w-100 border-0 rounded-0 border-bottom mb-2 d-flex justify-content-center">
            <span class="text-center fs-5 fw-semibold p-2 text-blue-gray">Product Additions</span>
        </div>
        <form enctype="multipart/form-data" on:submit={(e) => createProduct(e)} class="w-100 d-flex flex-column justify-content-center align-items-center m-0">

            <div class="w-100 d-flex justify-content-center align-items-center mb-4 flex-row gap-4">
                <div class="d-flex flex-column w-50 h-100 justify-content-center align-items-start gap-2">
                    <div class="w-100 border-0 rounded-0 mb-2 d-flex gap-4 justify-content-center align-items-center">
                    
                        <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                            <label for="product-name-input">Product Name</label>
                            <div id="product-name-input" class="input-group w-100">
                                <span class="shadow-sm input-group-text fs-5 fw-semibold text-blue-gray"><i class="bi bi-box-seam"></i></span>
                                <input maxlength="255" minlength="3" name="name" required placeholder="Product name..." type="text" class="shadow-sm form-control">
                            </div>    
                        </div>

                        <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                            <label for="product-discount-input">Discount Percentage</label>
                            <div id="product-discount-input" class="input-group w-100">
                                <span class="shadow-sm input-group-text fs-5 fw-semibold text-blue-gray"><i class="bi bi-cash-coin"></i></span>
                                <input min="0" max="99.9" step="0.01" name="discount_percentage" required placeholder="Discount Percentage..." type="number" class="no-spin shadow-sm form-control">
                            </div>    
                        </div>

                    </div>

                    <div class="w-100 border-0 rounded-0 d-flex justify-content-between flex-row gap-4">

                        <div class="w-35 d-flex flex-column justify-content-between align-items-start">
                            <label for="product-price-input">Price</label>
                            <div id="product-price-input" class="input-group w-100">
                                <span class="shadow-sm input-group-text fs-5 fw-semibold text-blue-gray"><i class="bi bi-currency-euro"></i></span>
                                <input min="0" step="0.01" inputmode="decimal" name="price" required placeholder="Product cost..." type="number" class="shadow-sm form-control" >
                            </div>   
                        </div>

                        <div class="d-flex flex-column justify-content-between align-items-start">
                            <label for="product-status">Market Status</label>
                            <div id="product-status" class="m-0 p-0 w-100 fw-semibold text-blue-gray form-switch">
                                <span class="fs-7" class:text-success={productStatus} class:text-danger={!productStatus}>{productStatus ? "Available" : "Unavailable"}</span>
                                <input type="checkbox" value="1" name="status" bind:checked={productStatus} class="w-100 m-0 rounded-1 shadow-sm border-dashed rounded-1 form-check-input product-form-input">
                            </div>
                        </div>

                        <div class="d-flex flex-column justify-content-between align-items-start">
                            <label for="product-status">Product Submission</label>
                            <button type="submit" class="rounded-1 btn btn-outline-primary fw-semibold shadow-sm">Submit Product</button>
                        </div>

                    </div>

                    <div class="w-100 rounded-0 d-flex justify-content-center align-items-center">
                        <span class="fs-7 text-start opacity-75">Please make sure you've filled out all the required input fields before submitting the new product.</span>
                    </div>
                </div>

                <div class="d-flex flex-column w-50 h-100 justify-content-center align-items-start">
                    <label for="product-status">Product Description</label>
                    <div class="w-100 h-100 m-0 p-2 d-flex justify-content-center align-items-center rounded-1 shadow-sm border border-dashed">
                            <textarea maxlength="2000" minlength="5" name="description" class="form-control h-100 m-0 p-1" required rows="4"></textarea>
                    </div>
                </div>
            </div>

            <div class="w-100 d-flex justify-content-center align-items-center flex-column gap-2 border-top">
                <h5 class="w-100 text-center border-bottom py-2">Images</h5>
                <div class="w-100 row align-items-stretch">
                {#each genArrayed as number}
                <div class="col-4 col-md-2 p-2 d-flex">
                    <div class="rounded-1 shadow border px-2">
                        <img src='images/defaultImage.png' class="img-fluid border border-dashed rounded-1 p-1 mt-3">
                        <input data-slot={number} class="form-control my-3 w-100" type="file" id={'product-image-' + number} on:change={(e) => previewImage(e)} name={'image-' + number} accept="image/*" >
                        <p class="opacity-75 p-1 bg-secondary-subtle rounded-1 text-center shadow-sm">Image Slot -  <span class="fw-semibold opacity-100">{number}</span></p>
                    </div>
                </div>
                {/each}
                <span class="fs-7 text-start">Uploaded images <span class="fw-semibold">must</span> be 400x600px large. Images who's size does not match the given parameters will not be uploaded.</span>
                <span class="fs-7 text-start opacity-75">Given image slots correspond to the order in which the images will be displayed - one to six. In order to avoid clustering and potential technical issues, the maximum number of images has been limited to six.</span>
                </div>
            </div>

        </form>
    </div>
</div>