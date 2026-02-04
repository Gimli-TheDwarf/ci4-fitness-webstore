<script>
    export let info = [];
    export let baseURL;
    import {addToFavorites, decrease, increase, addToCart} from './favorites.js';

    console.log("INFO: " , info);

    function showModal(item, eventT)
    {
        const BootstrapModal = document.getElementById("ImageModal");

        let eventTargetSrc = eventT.currentTarget.src;

        BootstrapModal.querySelector(".modal-title").textContent = item.name;
        BootstrapModal.querySelector(".modalImage").src = eventTargetSrc;
        BootstrapModal.querySelector(".modaliMAGE").alt = item.name;
        BootstrapModal.querySelector("#modalText").textContent = item.description;

        const newModal = new bootstrap.Modal(BootstrapModal);
        newModal.show();
    }   
    
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

</script>

<main id="SvelteProductsContainer"class="bg-secondary-subtle bg-gradient border rounded-0 shadow-sm overflow-hidden row g-0 row-cols-1 row-cols-md-5 flex-grow-1 p-2 p-md-3">
    {#if !info || info.length === 0}
        <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 20vh;">
            <p class="m-0 w-100 opacity-75 text-center fw-light fs-4">No Items That Match Selected Tags</p>
        </div>
    {:else}
        {#each info as item} 
            <div class="col p-3">
                <div class="d-flex shadow-sm flex-column justify-content-between align-items-center rounded-4 overflow-hidden border bg-light bg-gradient p-3" style="height: 600px;">

                    <div class="w-100 h-50 overflow-hidden bg-blue-gray overflow-auto d-flex justify-content-center rounded-4 p-05 position-relative">
                        <img on:click|preventDefault={(e) => showModal(item, e)} id={item.name + "-main-image"} class="SvelteImage rounded-1 cursor-pointer overflow-hidden" src="{item?.images[0]?.img ? baseURL + 'images/productsImages/' + item?.images[0].img : baseURL + 'images/defaultImage.png'}" alt={item.src}>
                        {#if item.discount_percentage > 0}
                            <span class="badge d-inline-flex align-items-center gap-1 position-absolute top-0 start-0 z-1 m-2 px-2 py-1 rounded-pill bg-orange bg-gradient shadow-sm fw-semibold text-uppercase">{item.discount_percentage}% <span class="small opacity-75">OFF</span></span>
                        {/if}
                    </div>

                    <div id="{item.name} + '_container'" class="w-100 h-50 d-flex flex-column justify-content-start align-items-start bg-light py-2">

                        <div class="h-25 w-100 d-flex flex-nowrap align-items-center thin-scrollbar-x overflow-y-hidden p-05 gap-1">
                            {#each item.images as imageItem}
                                <button on:click|preventDefault={() => changeImage(imageItem.img, item.name)}  class="hover-transform flex-shrink-0 d-flex justify-content-center border border-1 border-orange rounded-2 w-25 h-100">
                                    <img class="rounded-1 h-100" src="{imageItem?.img ? baseURL + 'images/productsImages/' + imageItem.img : baseURL + 'images/defaultImage.png'}" cursor="pointer" alt={imageItem.img}>
                                </button>
                            {/each}
                        </div>

                        <p class="fs-6 fw-semibold m-0 my-1">{item.name}</p>
                        <p class="opacity-75 fs-7 overflow-y-auto no-scrollbar">
                        {item.description}
                        {#if item.tags.array?.length}
                            {#each item.tags as tag}
                                <span class="fw-semibold text-center text-orange fs-7 p-05 border rounded-4 mx-1">{tag}</span>
                            {/each}
                        {:else}
                        {/if}
                        </p>


                        <div class="d-flex flex-row justify-content-between align-items-center w-100 h-25 gap-2">
                            <button on:click|preventDefault={() => addToCart(item)} class="rounded-1 text-light fw-semibold btn-secondary fs-7 btn">ADD TO CART</button>
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


    <div id="ImageModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-light bg-gradient border-0 rounded-4 overflow-hidden shadow-lg">

                <div class="modal-header border-0 px-4 py-3">
                    <div class="d-flex flex-column">
                    <h5 class="modal-title fw-semibold mb-0">Preview</h5>
                    <small class="opacity-75">Click outside to close</small>
                    </div>
                    <button type="button" class="btn-close shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="bg-blue-gray rounded-4 d-flex justify-content-center align-items-center p-2">
                    <img src="" alt="preview" class="modalImage rounded-3 shadow-sm">
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <div class="w-100 bg-light bg-gradient border rounded-4 p-3 shadow-sm">
                    <p class="m-0 fw-semibold text-dark" id="modalText"></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>