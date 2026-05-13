<script>
    export let info = [];
    export let baseURL;
    import { onMount, onDestroy } from 'svelte';
    import {addToFavorites, decrease, increase, addToCart} from './favorites.js';
    import {showProductPreviewModal} from './productPreviewModal.js';

    console.log("INFO: " , info);
    let imageModalElement;

    onMount(() =>
    {
        if(imageModalElement && imageModalElement.parentElement !== document.body)
        {
            document.body.appendChild(imageModalElement);
        }
    });

    onDestroy(() =>
    {
        if(imageModalElement?.parentElement === document.body)
        {
            imageModalElement.remove();
        }
    });

    function changeImage(image, name)
    {
        const mainImg = document.getElementById(`${name}-main-image`);
        mainImg.src = `images/productsImages/${image}`;
    }

    function manageFavorites(params)
    {
        info = info;
        addToFavorites(params)
    }

    function visibleTags(tags)
    {
        return Array.isArray(tags) ? tags.map(tag => tag == null ? "" : String(tag).trim()).filter(tag => tag !== "") : [];
    }
</script>

<main id="SvelteProductsContainer" class="bg-light bg-gradient border rounded-2 shadow-sm overflow-hidden row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xxl-4 flex-grow-1 p-2 p-md-3 w-100 mx-0">
    {#if !info || info.length === 0}
        <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 20vh;">
            <p class="m-0 w-100 opacity-75 text-center fw-light fs-4">No Items That Match Selected Tags</p>
        </div>
    {:else}
        {#each info as item}
            <div class="col d-flex">
                <article class="product-card d-flex shadow-sm flex-column align-items-stretch rounded-2 overflow-hidden border bg-white w-100">

                    <div class="product-card-media w-100 overflow-hidden bg-blue-gray d-flex justify-content-center align-items-center position-relative">
                        <img on:click|preventDefault={(e) => showProductPreviewModal(imageModalElement, item, e.currentTarget)} id={item.name + "-main-image"} class="SvelteImage cursor-pointer w-100 h-100 object-fit-contain" src="{item?.images[0]?.img ? baseURL + 'images/productsImages/' + item?.images[0].img : baseURL + 'images/defaultImage.png'}" alt={item.name}>
                        {#if item.discount_percentage > 0}
                            <span class="badge d-inline-flex align-items-center gap-1 position-absolute top-0 start-0 z-1 m-2 px-2 py-1 rounded-pill bg-orange bg-gradient shadow-sm fw-semibold text-uppercase">{item.discount_percentage}% <span class="small opacity-75">OFF</span></span>
                        {/if}
                    </div>

                    <div id="{item.name} + '_container'" class="w-100 flex-grow-1 d-flex flex-column justify-content-start align-items-start bg-white p-3 gap-3">

                        <div class="product-thumb-strip w-100 d-flex flex-nowrap align-items-center thin-scrollbar-x overflow-y-hidden gap-2">
                            {#each item.images as imageItem}
                                <button on:click|preventDefault={() => changeImage(imageItem.img, item.name)} class="product-thumb hover-transform flex-shrink-0 d-flex justify-content-center align-items-center border border-1 border-orange rounded-2 bg-light p-1">
                                    <img class="rounded-1 w-100 h-100 object-fit-contain" src="{imageItem?.img ? baseURL + 'images/productsImages/' + imageItem.img : baseURL + 'images/defaultImage.png'}" alt={imageItem.img}>
                                </button>
                            {/each}
                        </div>

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

    <div bind:this={imageModalElement} id="ImageModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-2 overflow-hidden shadow-lg bg-white">
                <div class="row g-0">
                    <div class="col-12 col-lg-7 bg-blue-gray product-preview-image-panel d-flex justify-content-center align-items-center p-3 position-relative">
                        <span class="position-absolute top-0 start-0 m-3 badge rounded-pill bg-orange text-white shadow-sm">Product preview</span>
                        <img src="" alt="preview" class="modalImage w-100 rounded-2 bg-white object-fit-contain shadow-sm" style="max-height: 52vh; aspect-ratio: 4 / 3;">
                    </div>

                    <div class="col-12 col-lg-5 bg-white d-flex flex-column">
                        <div class="modal-header border-bottom px-4 py-3">
                            <div>
                                <h5 class="modal-title fw-semibold text-blue-gray mb-0">Preview</h5>
                            </div>
                            <button type="button" class="btn-close shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4 d-flex flex-column gap-3">
                            <div class="border rounded-2 bg-light p-3 shadow-sm">
                                <span class="small fw-semibold text-blue-gray d-inline-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-card-text text-orange"></i><span>Description</span>
                                </span>
                                <p class="m-0 text-secondary lh-base" id="modalText"></p>
                            </div>
                        </div>

                        <div class="modal-footer border-top bg-light px-4 py-3 d-flex justify-content-end">
                            <button type="button" class="btn btn-orange rounded-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-dismiss="modal">
                                <i class="bi bi-check2"></i><span>Done</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
