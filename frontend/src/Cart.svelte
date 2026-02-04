<script>
    import {baseURLStore} from './stores/store-products.js'
    export let baseURL;
    export let ItemsInCart = [];
    console.log(ItemsInCart);

    $: cartLength = ItemsInCart.length;

    $: TotalPrice = Number(ItemsInCart.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.quantity) || 0), 0).toFixed(2));
    //reduces to the array to a single vlaue by this formula - (sum + item.price * item.quantity), initial sum value is 0
    console.log("AAAAAAAAAA", ItemsInCart);

    function removeItem(item)
    {
        jQuery.ajax({
            url: 'RemoveItem/' + item.id,
            method: 'POST',
            dataType: 'json',
            
            success: function(response)
            {
                ItemsInCart = ItemsInCart.filter(i => i.id !== item.id);
                document.getElementById("cartItemsCountIcon").innerHTML = response.cartCount;
                console.log("RESPONSE TEST:", response.cartCount);

                notify(item.name + ' was removed from your cart.')
            },

            error: function(jqXHR) 
            {
                console.log(jqXHR.responseJSON.message);
            }
        });
    }

    function Change(item, count)
    {
      jQuery.ajax({
        url: 'quantityChange/' + item.id + '/' + count,
        method: 'PATCH',
        dataType: 'json',
        
        success: function(response)
        {
          console.log(response.message);
          ItemsInCart = ItemsInCart.map(CartItem => CartItem.id === item.id ? { ...CartItem, quantity: response.quantity} : CartItem) 
          notify(item.name + `'s quantity was changed to ` + response.quantity);
        },
        error: function(jqXHR)
        {
          console.log("Error");
          console.log(jqXHR.responseJSON.message)
        }
      })
    };
</script>

<div id="cart-container-wrapper" class="w-100 d-flex align-items-stretch gap-3 px-3 py-2">

  <div id="cart-items-wrapper" class="flex-grow-1 bg-secondary-subtle bg-gradient border rounded-4 shadow-sm overflow-auto p-2">
    <div class="row g-3">
      {#each ItemsInCart as item}
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden">
            <img src={item?.images[0]?.img ? 'images/productsImages/' + item?.images[0].img : 'images/defaultImage.png'} class="card-img-top" alt={item.name} />

            <div class="card-body text-center bg-light">
              <p class="m-0 fw-semibold">{item.name}</p>
              <p class="m-0 small opacity-75">{item.description}</p>
            </div>

            <div class="card-footer bg-white border-0 pt-2">
              <div class="small d-flex flex-column gap-1">
                <div class="d-flex justify-content-between"><span class="opacity-75">Price:</span><span class="fw-semibold">€{item.price}</span></div>
                <div class="d-flex justify-content-between"><span class="opacity-75">Qty:</span><span class="fw-semibold">{item.quantity}</span></div>
                <div class="d-flex justify-content-between"><span class="opacity-75">Total:</span><span class="fw-semibold">€{(Number(item.price) * Number(item.quantity)).toFixed(2)}<br/></span></div>
              </div>

              <div class="mt-2 d-flex justify-content-center" id={'cart-controls-wrapper-' + item.name}>
                <div class="input-group input-group-sm w-75 shadow-sm rounded-2 overflow-hidden">
                  {#if item.quantity === "0"}
                    <button on:click|preventDefault={() => removeItem(item)} class="btn btn-outline-danger px-2" type="button"><i class="bi bi-trash3"></i></button>
                  {:else if item.quantity > 0}
                    <button on:click|preventDefault={() => Change(item, -1)} class="btn btn-outline-secondary px-2" type="button"><i class="bi bi-dash-lg"></i></button>
                  {/if}

                  <input id={'quantity-input-' + item.name} class="form-control text-center border-start-0 border-end-0 no-spin no-focus-outline" type="number" min="0" step="1" bind:value={item.quantity} />

                  <button on:click|preventDefault={() => Change(item, 1)} class="btn btn-outline-secondary px-2" type="button"><i class="bi bi-plus-lg"></i></button>
                </div>
              </div>
            </div>

          </div>
        </div>
      {/each}
    </div>
  </div>

  <div class="flex-shrink-0 bg-secondary-subtle bg-gradient border rounded-4 shadow-sm p-3" style="width: 320px;">
    <div id="imageContainer" class="bg-dark bg-gradient rounded-4 p-3 text-center mb-3">
      <p class="m-0 fw-semibold text-white text-shadow">Adverts Will Go Here Later</p>
      <img class="rounded-3 img-fluid border mt-2" src={'images/' + 'defaultImage.png'} alt="Advert placeholder" />
    </div>

    <div class="d-flex justify-content-between align-items-center">
      <span class="fw-semibold">Subtotal</span>
      <span class="small opacity-75">({cartLength} items)</span>
    </div>

    <div class="mt-1 d-flex justify-content-between align-items-center">
      <span class="opacity-75 small">Total</span>
      <span class="fw-bold text-orange shadow-sm px-2 rounded bg-light border text-center">€{TotalPrice}</span>
    </div>

    <button disabled={!cartLength} on:click={() => (window.location.href = "checkout")} class="btn btn-secondary fw-semibold shadow-sm rounded-2 w-100 mt-3 d-inline-flex justify-content-center align-items-center gap-2">
      <i class="bi bi-credit-card"></i><span>PROCEED TO CHECKOUT</span>
    </button>
  </div>

</div>
<style>
  .card-title 
  {
    font-size: clamp(0.8rem, 2vw, 1.25rem);
  }
</style>
