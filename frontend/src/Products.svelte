<script>
    export let info = [];
    export let baseURL;

    console.log("INFO: " , info);

    function showModal(item)
    {
        const BootstrapModal = document.getElementById("ImageModal");

        BootstrapModal.querySelector(".modal-title").textContent = item.name;
        BootstrapModal.querySelector(".modalImage").src = baseURL +  'images/' + (item?.images[0]?.img ? 'productsImages/' + item?.images[0]?.img : 'defaultImage.png');
        BootstrapModal.querySelector(".modaliMAGE").alt = item.name;
        BootstrapModal.querySelector("#modalText").textContent = item.description;

        const newModal = new bootstrap.Modal(BootstrapModal);
        newModal.show();
    }   

    function addToCart(item)
    {
        let letter = '';
        let quantity = parseInt(document.getElementById('quantity-input-' + item.name).value);
        console.log("QUANTITY: ", quantity);

        if(quantity === 1)
        {
            letter = ' was';
        }
        else
        {
            letter = ' were';
        }

        if(quantity != 0)
        {
            jQuery.ajax({
                url: baseURL + 'AddItem/' + item.id + '/' + quantity,
                method: 'POST',
                dataType: 'json',
                
                success: function(response)
                {
                    document.getElementById("cartItemsCountIcon").innerHTML = response.cartCount;
                    notify(quantity + 'x ' + item.name + letter + ' added to your Cart.');
                },

                error: function(jqXHR) 
                {
                    console.log(jqXHR.responseJSON.message);
                }
            });
        }
    }

    function increase(inputId)
    {
        let inputItem = document.getElementById(inputId);
        inputItem.stepUp();
    }

    function decrease(inputId)
    {
        let inputItem = document.getElementById(inputId);
        inputItem.stepDown();
    }

</script>

<main id="SvelteProductsContainer" class="bg-secondary rounded bg-gradient row gx-0 row-cols-1 row-cols-md-4 shadow flex-grow-1" style="border-radius: 0px !important;">
    {#each info as item} 
    <div class="col p-2" >
        <div class="d-flex flex-column rounded overflow-hidden border border-2 shadow bg-secondary" style="height: 400px;">
            <div class="overflow-auto d-flex flex-row g-3 p-2 rounded">
                <img on:click|preventDefault={() => showModal(item)} class="SvelteImage border border-1 rounded cursor-pointer" src="{item?.images[0]?.img ? baseURL + 'images/productsImages/' + item?.images[0].img : baseURL + 'images/defaultImage.png'}"  alt={item.src}/>
                <div id="{item.name} + '_container'" class="h-100 w-100 d-flex flex-column p-2 overflow-y-auto no-scrollbar">
                    <p><strong>{item.name}</strong></p>
                    <p><strong>Description:</strong> {item.description}</p>
                    <p><strong>PRICE: </strong> {item.price}</p>
                    <p><strong>TAGS:</strong> {item.tags}</p>
                </div>
            </div>
            <div id={'cart-control-section-' + item.name} style="border-top: 0.05rem solid #212529;" class="p-2 bg-dark-subtle">
                <div class="mt-auto d-flex flex-column justify-content-center align-items-center">
                    <div class="controls-container w-100 d-flex flex-column align-items-center">

                        <div class="d-flex flex-row w-100 input-group">
                            <button disabled={item.status !== "1"} on:click|preventDefault={() => decrease('quantity-input-' + item.name)} class="btn btn-danger border border-end-0 border-dark w-25"><i class="fa-solid fa-minus"></i></button>
                            <input disabled={item.status !== "1"} id={'quantity-input-' + item.name} class="ps-2 w-50 no-focus-outline no-spin border border-dark" type="number" min="0" step="1" placeholder={item.status === "1" ? " Enter the Quantity." : " Item out of Stock."}>
                            <button disabled={item.status !== "1"} on:click|preventDefault={() => increase('quantity-input-' + item.name)} class="btn btn-success border border-start-0 border-dark w-25"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <div id={'additional-info-' + item.name} class="w-100 d-flex justify-content-between">
                            <span class="text-shadow" class:text-success={item.status === "1"} class:text-danger={item.status !== "1"}>{item.status === "1" ? "In Stock" : "Out of Stock"}</span>
                        </div>

                    </div>
                    <button on:click|preventDefault={() => addToCart(item)} class="w-100 btn btn-success border border-1 border-dark bg-gradient text-shadow"><strong>Add To Cart</strong></button>
                </div>
            </div>       
        </div>
    </div>
    {/each}
<div id="ImageModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Default</h5>
                <button type="button" class=" btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <img src="" alt="default" class="modalImage border border-1 rounded">
            </div>
            <div class="bg-success bg-gradient modal-footer d-flex justify-content-center">
                <p class="text-white fw-normal" id="modalText">
                </p>
            </div>
        </div>
    </div>
</div>
</main>


<style>
    p
    {
        font-size: 14px;
    }
</style>