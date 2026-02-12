<script>
    import { onMount } from 'svelte';
    import {addToFavorites, decrease, increase, addToCart} from './favorites.js';
    let { products } = $props();
    let favorites = $state([]);

    onMount(() => 
    {
    let list = products?.array ?? products ?? [];

    for (let element of list) 
        {
            if (element?.favorite === true) 
            {
            favorites.push(element);
            }
        }
    });

    function manageFavorites(params)
    {
        addToFavorites(params)
    }
</script>

<div class="min-vh-100 w-100 p-2 d-flex justify-content-center ">
    <div class="w-50 bg-secondary-subtle bg-gradient border rounded-0 shadow-sm overflow-hidden d-flex flex-column">
    {#if !favorites || favorites.length === 0}
        <p class="w-100 opacity-75 text-center fw-light my-4">No Favorites Yet</p>
    {:else}
        {#each favorites as item}
        <div class="w-100 p-2 d-flex justify-content-center align-items-center" style="height: 25vh;">
            <div class="d-flex justify-content-start align-items-center w-100 h-100 bg-light bg-gradient border rounded-4 p-3 gap-3">

                <div class="w-25 h-100 overflow-hidden bg-blue-gray overflow-auto d-flex justify-content-center rounded-4 p-05 position-relative">
                    <img class="rounded-1 overflow-hidden" src="{item?.images[0]?.img ? 'images/productsImages/' + item?.images[0].img : 'images/defaultImage.png'}" alt={item.src}>
                    {#if item.discount_percentage > 0}
                        <span class="badge d-inline-flex align-items-center gap-1 position-absolute top-0 start-0 z-1 m-2 px-2 py-1 rounded-pill bg-orange bg-gradient shadow-sm fw-semibold text-uppercase">{item.discount_percentage}% <span class="small opacity-75">OFF</span></span>
                    {/if}
                </div>

                <div class="w-75 h-100 overflow-hidden d-flex flex-column justify-content-center align-items-start">
                    <p class="fs-6 fw-semibold m-0 my-1">{item.name}</p>
                    <p class="opacity-75 fs-7 overflow-y-auto no-scrollbar">
                        {item.description}                    
                        {#each item.tags as tag}
                            <span class="fw-semibold text-center text-orange fs-7 p-05 border rounded-4 mx-1">{tag}</span>
                        {/each}
                    </p>

                    <div class="d-flex flex-row justify-content-between align-items-center w-100 h-25 gap-2">
                        <button disabled={item.status !== "1"} on:click|preventDefault={() => addToCart(item)} class="rounded-1 text-light fw-semibold btn-secondary fs-7 btn">ADD TO CART</button>
                        <div class="input-group input-group-sm flex-nowrap shadow-sm rounded-2 overflow-hidden qty-group h-100">
                            <button type="button" disabled={item.status !== "1"} on:click|preventDefault={() => decrease('quantity-input-' + item.name)} class="btn btn-outline-secondary px-2" aria-label="Decrease quantity"><i class="bi bi-dash-lg"></i></button>
                            <input disabled={item.status !== "1"} id={'quantity-input-' + item.name} class="form-control no-spin text-center border-0" type="number" min="0" step="1" placeholder={item.status === "1" ? "Qty" : "Out of stock"}>
                            <button type="button" disabled={item.status !== "1"} on:click|preventDefault={() => increase('quantity-input-' + item.name)} class="btn btn-outline-secondary px-2" aria-label="Increase quantity"><i class="bi bi-plus-lg"></i></button>
                        </div>
                        <button data-itemid={item.id} class="fs-3 rounded-1 text-orange btn h-100 border" class:btn-orange={item.favorite} class:text-light={item.favorite} class:text-orange={!item.favorite} class:btn-light={!item.favorite} on:click|preventDefault={() => manageFavorites(item)}><i class="bi bi-bookmark-heart"></i></button>
                    </div>  
                </div>


            </div>
        </div>
        {/each}
    {/if}
    </div>
</div>