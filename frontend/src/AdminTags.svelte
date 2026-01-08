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
</div>
