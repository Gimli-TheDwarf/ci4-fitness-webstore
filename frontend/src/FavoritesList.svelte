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

    function visibleTags(tags)
    {
        return Array.isArray(tags) ? tags.map(tag => tag == null ? "" : String(tag).trim()).filter(tag => tag !== "") : [];
    }
</script>

<div class="w-100 d-flex flex-column gap-3">
    <div class="homepage-filter-toolbar bg-white border shadow-sm rounded-2 p-3 mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 w-100">
        <div class="d-flex align-items-center gap-2 text-blue-gray">
            <span class="admin-icon-box d-inline-flex justify-content-center align-items-center rounded-2 bg-orange text-white shadow-sm fs-5">
                <i class="bi bi-bookmark-heart-fill"></i>
            </span>
            <div>
                <h1 class="h5 fw-semibold mb-0">Favorites</h1>
                <span class="small text-secondary">Saved products, ready for quick review.</span>
            </div>
        </div>
        <span class="badge rounded-pill bg-light text-blue-gray border px-3 py-2">{favorites.length} saved</span>
    </div>

    <div class="bg-light bg-gradient border rounded-2 shadow-sm overflow-hidden row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xxl-4 flex-grow-1 p-2 p-md-3 w-100 mx-0">
    {#if !favorites || favorites.length === 0}
        <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 20vh;">
            <p class="m-0 w-100 opacity-75 text-center fw-light fs-4">No Favorites Yet</p>
        </div>
    {:else}
        {#each favorites as item}
            <div class="col d-flex">
                <article class="product-card d-flex shadow-sm flex-column align-items-stretch rounded-2 overflow-hidden border bg-white w-100">

                    <div class="product-card-media w-100 overflow-hidden bg-blue-gray d-flex justify-content-center align-items-center position-relative">
                        <img class="SvelteImage w-100 h-100 object-fit-contain" src="{item?.images[0]?.img ? 'images/productsImages/' + item?.images[0].img : 'images/defaultImage.png'}" alt={item.name}>
                        {#if item.discount_percentage > 0}
                            <span class="badge d-inline-flex align-items-center gap-1 position-absolute top-0 start-0 z-1 m-2 px-2 py-1 rounded-pill bg-orange bg-gradient shadow-sm fw-semibold text-uppercase">{item.discount_percentage}% <span class="small opacity-75">OFF</span></span>
                        {/if}
                    </div>

                    <div class="w-100 flex-grow-1 d-flex flex-column justify-content-start align-items-start bg-white p-3 gap-3">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-start gap-3 w-100">
                                <h3 class="product-card-title fs-6 fw-semibold text-blue-gray m-0">{item.name}</h3>
                                <span class="fw-semibold text-orange text-nowrap">&euro;{item.price}</span>
                            </div>

                            {#if item.status !== "1"}
                                <span class="badge bg-danger-subtle text-danger-emphasis border mt-2">Out of stock</span>
                            {:else}
                                <span class="badge bg-success-subtle text-success-emphasis border mt-2">In stock</span>
                            {/if}
                        </div>

                        <p class="product-card-description text-secondary small m-0">
                            {item.description}
                        </p>

                        {#if visibleTags(item.tags).length}
                        <div class="d-flex flex-wrap justify-content-start align-items-center gap-1">
                            {#each visibleTags(item.tags) as tag}
                                <span class="fw-semibold text-center text-orange fs-7 px-2 py-1 border rounded-pill bg-light">{tag}</span>
                            {/each}
                        </div>
                        {/if}

                        <div class="product-card-actions d-flex flex-row justify-content-between align-items-stretch w-100 gap-2 mt-auto pt-2 border-top">
                            <button disabled={item.status !== "1"} on:click|preventDefault={() => addToCart(item)} class="rounded-1 text-light fw-semibold btn-success btn btn-sm d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-cart-plus"></i><span>Add</span>
                            </button>

                            <div class="input-group input-group-sm flex-nowrap shadow-sm rounded-2 overflow-hidden qty-group">
                                <button type="button" disabled={item.status !== "1"} on:click|preventDefault={() => decrease('quantity-input-' + item.name)} class="btn btn-outline-secondary px-2" aria-label="Decrease quantity"><i class="bi bi-dash-lg"></i></button>
                                <input disabled={item.status !== "1"} id={'quantity-input-' + item.name} class="form-control no-spin text-center border-0" type="number" min="0" step="1" placeholder={item.status === "1" ? "Qty" : "Out of stock"}>
                                <button type="button" disabled={item.status !== "1"} on:click|preventDefault={() => increase('quantity-input-' + item.name)} class="btn btn-outline-secondary px-2" aria-label="Increase quantity"><i class="bi bi-plus-lg"></i></button>
                            </div>

                            <button data-itemid={item.id} class="rounded-1 text-orange btn border d-inline-flex align-items-center justify-content-center" class:btn-orange={item.favorite} class:text-light={item.favorite} class:text-orange={!item.favorite} class:btn-light={!item.favorite} on:click|preventDefault={() => manageFavorites(item)} aria-label="Toggle favorite"><i class="bi bi-bookmark-heart"></i></button>
                        </div>
                    </div>
                </article>
            </div>
        {/each}
    {/if}
    </div>
</div>
