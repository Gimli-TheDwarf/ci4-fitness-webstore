<script>
    import {baseURLStore} from './stores/store-products.js'
    export let baseURL;
    export let ItemsInCart = [];
    console.log(ItemsInCart);

    $: cartLength = ItemsInCart.length;

    $: TotalPrice = Number(ItemsInCart.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.quantity) || 0), 0).toFixed(2));
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

<div id="cart-container-wrapper" class="container-xxl px-0 d-flex flex-column gap-3">
  <div class="homepage-filter-toolbar bg-white border shadow-sm rounded-2 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 w-100">
    <div class="d-flex align-items-center gap-2 text-blue-gray">
      <span class="admin-icon-box d-inline-flex justify-content-center align-items-center rounded-2 bg-orange text-white shadow-sm fs-5">
        <i class="bi bi-bag-check-fill"></i>
      </span>
      <div>
        <h1 class="h5 fw-semibold mb-0">Cart</h1>
        <span class="small text-secondary">Review quantities and confirm the order total.</span>
      </div>
    </div>
    <span class="badge rounded-pill bg-light text-blue-gray border px-3 py-2">{cartLength} items</span>
  </div>

  <div class="row g-3 align-items-start">
    <section id="cart-items-wrapper" class="col-12 col-lg-8 col-xl-9">
      <div class="bg-light bg-gradient border rounded-2 shadow-sm overflow-hidden p-2 p-md-3">
      {#if !cartLength}
        <div class="d-flex justify-content-center align-items-center text-center bg-white border rounded-2 min-vh-25">
          <p class="m-0 opacity-75 fs-5">Your cart is empty.</p>
        </div>
      {:else}
        <div class="d-flex flex-column gap-3">
          {#each ItemsInCart as item}
            <article class="row g-0 bg-white border rounded-2 shadow-sm overflow-hidden">
              <div class="col-12 col-md-4 col-xl-3 product-preview-image-panel d-flex justify-content-center align-items-center p-3">
                <div class="ratio ratio-4x3 bg-white rounded-2 shadow-sm w-100">
                  <img src={item?.images[0]?.img ? 'images/productsImages/' + item?.images[0].img : 'images/defaultImage.png'} class="object-fit-contain rounded-2 p-2" alt={item.name} />
                </div>
              </div>

              <div class="col-12 col-md-8 col-xl-9 d-flex flex-column gap-3 p-3 p-lg-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                  <div>
                    <h2 class="h6 fw-semibold text-blue-gray mb-1">{item.name}</h2>
                    <p class="small text-secondary mb-0">{item.description}</p>
                  </div>
                  <span class="fw-semibold text-orange text-nowrap">&euro;{item.price}</span>
                </div>

                <div class="row g-2">
                  <div class="col-12 col-sm-6">
                    <div class="bg-light border rounded-2 px-3 py-2 h-100">
                    <span class="small text-secondary d-block">Quantity</span>
                    <span class="fw-semibold text-blue-gray">{item.quantity}</span>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6">
                    <div class="bg-light border rounded-2 px-3 py-2 h-100">
                    <span class="small text-secondary d-block">Line total</span>
                    <span class="fw-semibold text-blue-gray">&euro;{(Number(item.price) * Number(item.quantity)).toFixed(2)}</span>
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                  <div class="input-group input-group-sm shadow-sm rounded-2 overflow-hidden w-100" id={'cart-controls-wrapper-' + item.name}>
                    {#if item.quantity === "0"}
                      <button on:click|preventDefault={() => removeItem(item)} class="btn btn-outline-danger px-2" type="button" aria-label="Remove item"><i class="bi bi-trash3"></i></button>
                    {:else if item.quantity > 0}
                      <button on:click|preventDefault={() => Change(item, -1)} class="btn btn-outline-secondary px-2" type="button" aria-label="Decrease quantity"><i class="bi bi-dash-lg"></i></button>
                    {/if}

                    <input id={'quantity-input-' + item.name} class="form-control text-center border-start-0 border-end-0 no-spin no-focus-outline" type="number" min="0" step="1" bind:value={item.quantity} />

                    <button on:click|preventDefault={() => Change(item, 1)} class="btn btn-outline-secondary px-2" type="button" aria-label="Increase quantity"><i class="bi bi-plus-lg"></i></button>
                  </div>

                  <button on:click|preventDefault={() => removeItem(item)} class="btn btn-outline-danger btn-sm rounded-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-2" type="button">
                    <i class="bi bi-trash3"></i><span>Remove</span>
                  </button>
                </div>
              </div>
            </article>
          {/each}
        </div>
      {/if}
      </div>
    </section>

    <aside class="col-12 col-lg-4 col-xl-3">
      <div class="bg-white border rounded-2 shadow-sm overflow-hidden sticky-lg-top">
      <div class="product-preview-image-panel px-3 py-4 text-center text-white">
        <span class="small text-uppercase opacity-75 fw-semibold">Order</span>
        <h2 class="h5 fw-semibold mb-0">Summary</h2>
      </div>

      <div class="p-3 p-lg-4 d-flex flex-column gap-3">
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-secondary">Items</span>
          <span class="fw-semibold text-blue-gray">{cartLength}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center border-top pt-3">
          <span class="fw-semibold">Total</span>
          <span class="fw-bold text-orange fs-5">&euro;{TotalPrice}</span>
        </div>

        <button disabled={!cartLength} on:click={() => (window.location.href = "checkout")} class="btn btn-success fw-semibold shadow-sm rounded-2 w-100 d-inline-flex justify-content-center align-items-center gap-2">
          <i class="bi bi-credit-card"></i><span>Proceed to checkout</span>
        </button>
      </div>
      </div>
    </aside>
  </div>
</div>
