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

<div id="cart-container-wrapper" class="w-100 d-flex flex-column flex-lg-row align-items-stretch gap-3 px-3 py-2 rounded-4" style="background-image: radial-gradient(1200px circle at 10% 0%, rgba(255,255,255,.55), transparent 60%), radial-gradient(900px circle at 95% 15%, rgba(0,0,0,.06), transparent 55%);">

  <div id="cart-items-wrapper" class="flex-grow-1 bg-secondary-subtle border rounded-4 shadow-sm overflow-auto p-3 position-relative" style="backdrop-filter: blur(10px); background-image: radial-gradient(800px circle at 15% 10%, rgba(255,255,255,.65), transparent 60%), radial-gradient(700px circle at 85% 30%, rgba(0,0,0,.05), transparent 58%), linear-gradient(135deg, rgba(255,255,255,.35), rgba(255,255,255,.10));">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 pb-2 border-bottom">
      <div class="d-inline-flex align-items-center gap-2">
        <span class="d-inline-flex justify-content-center align-items-center rounded-3 bg-white border shadow-sm" style="width: 38px; height: 38px;"><i class="bi bi-cart3"></i></span>
        <div class="d-flex flex-column lh-sm">
          <span class="fw-bold text-dark">Shopping Cart</span>
          <span class="small text-muted d-inline-flex align-items-center gap-1"><i class="bi bi-box-seam"></i><span>{cartLength} items</span></span>
        </div>
      </div>
      <div class="d-inline-flex align-items-center gap-2">
        <span class="small text-muted d-inline-flex align-items-center gap-1"><i class="bi bi-shield-check"></i><span>Secure checkout</span></span>
      </div>
    </div>

    <div class="row g-3 g-lg-4">
      {#each ItemsInCart as item}
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
          <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden bg-white">
            <div class="position-relative">
              <img src={item?.images[0]?.img ? 'images/productsImages/' + item?.images[0].img : 'images/defaultImage.png'} class="card-img-top" alt={item.name} style="height: clamp(160px, 18vh, 200px); object-fit: cover;" />
              <span class="position-absolute top-0 end-0 m-2 badge rounded-pill text-bg-light border shadow-sm d-inline-flex align-items-center gap-1"><i class="bi bi-hash"></i><span>{item.id}</span></span>
            </div>

            <div class="card-body text-center bg-white py-3 px-3">
              <p class="m-0 fw-semibold text-dark text-truncate d-inline-flex align-items-center gap-2 justify-content-center"><i class="bi bi-box2-heart text-muted"></i><span>{item.name}</span></p>

              <p class="no-scrollbar m-0 small text-muted d-flex align-items-start gap-2 justify-content-center mt-1 overflow-auto" style="max-height: 54px; overflow-y: auto; overflow-x: hidden; white-space: normal;">
                <i class=" bi bi-info-circle mt-1"></i><span class="text-start">{item.description}</span>
              </p>
            </div>

            <div class="card-footer bg-light border-0 pt-3 pb-3" style="box-shadow: inset 0 1px 0 rgba(0,0,0,.06);">
              <div class="small d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center"><span class="text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-tag"></i><span>Price</span></span><span class="fw-semibold text-dark">€{item.price}</span></div>
                <div class="d-flex justify-content-between align-items-center"><span class="text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-123"></i><span>Qty</span></span><span class="fw-semibold text-dark">{item.quantity}</span></div>
                <div class="d-flex justify-content-between align-items-center"><span class="text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-calculator"></i><span>Total</span></span><span class="fw-bold text-dark">€{(Number(item.price) * Number(item.quantity)).toFixed(2)}<br/></span></div>
              </div>

              <div class="mt-3 d-flex justify-content-center" id={'cart-controls-wrapper-' + item.name}>
                <div class="input-group input-group-sm w-100 shadow-sm rounded-3 overflow-hidden">
                  <span class="input-group-text bg-white border-0 d-inline-flex align-items-center gap-2"><i class="bi bi-sliders"></i><span class="small text-muted">Qty</span></span>

                  {#if item.quantity === "0"}
                    <button on:click|preventDefault={() => removeItem(item)} class="btn btn-outline-danger px-2" type="button"><i class="bi bi-trash3"></i></button>
                  {:else if item.quantity > 0}
                    <button on:click|preventDefault={() => Change(item, -1)} class="btn btn-outline-secondary px-2" type="button"><i class="bi bi-dash-lg"></i></button>
                  {/if}

                  <input class="form-control text-center border-start-0 border-end-0 bg-white fw-semibold shadow-none" type="number" value={item.quantity} disabled />

                  <button on:click|preventDefault={() => Change(item, 1)} class="btn btn-outline-secondary px-2" type="button"><i class="bi bi-plus-lg"></i></button>
                </div>
              </div>

              <div class="mt-2 d-flex justify-content-center">
                <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-arrow-repeat"></i><span>Update quantity anytime</span></span>
              </div>
            </div>

          </div>
        </div>
      {/each}
    </div>
  </div>

  <div class="flex-shrink-0 border rounded-4 shadow-sm p-3 overflow-hidden position-sticky" style="width: 320px; top: 1rem; backdrop-filter: blur(10px); background-image: radial-gradient(700px circle at 20% 0%, rgba(255,255,255,.55), transparent 60%), radial-gradient(700px circle at 90% 20%, rgba(0,0,0,.05), transparent 58%), linear-gradient(135deg, rgba(255,255,255,.30), rgba(255,255,255,.08));">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="fw-bold text-dark d-inline-flex align-items-center gap-2"><i class="bi bi-receipt-cutoff"></i><span>Summary</span></span>
      <span class="badge rounded-pill text-bg-light border d-inline-flex align-items-center gap-1"><i class="bi bi-box-seam"></i><span>{cartLength}</span></span>
    </div>

    <div id="imageContainer" class="bg-dark bg-gradient rounded-4 p-3 text-center mb-3" style="box-shadow: inset 0 0 0 1px rgba(255,255,255,.08);">
      <p class="m-0 fw-semibold text-white d-inline-flex align-items-center gap-2"><i class="bi bi-megaphone"></i><span>Promotions</span><i class="bi bi-stars"></i></p>
      <p class="m-0 small text-white-50 mt-1 d-inline-flex align-items-center gap-2 justify-content-center"><i class="bi bi-pin-angle"></i><span>Adverts will go here later</span></p>
      <img class="rounded-3 img-fluid border mt-3" src={'images/' + 'defaultImage.png'} alt="Advert placeholder" />
    </div>

    <div class="p-3 bg-white border rounded-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark d-inline-flex align-items-center gap-2"><i class="bi bi-cart-check"></i><span>Subtotal</span></span>
        <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-boxes"></i><span>({cartLength} items)</span></span>
      </div>

      <div class="mt-2 d-flex justify-content-between align-items-center">
        <span class="text-muted small d-inline-flex align-items-center gap-2"><i class="bi bi-cash-coin"></i><span>Total</span></span>
        <span class="fw-bold shadow-sm px-3 py-1 rounded-3 bg-light border text-center d-inline-flex align-items-center gap-2" style="font-size: 1.15rem; letter-spacing: .2px;"><i class="bi bi-currency-euro"></i><span>{TotalPrice}</span></span>
      </div>

      <div class="mt-2 pt-2 border-top d-flex flex-column gap-1">
        <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-truck"></i><span>Shipping calculated at checkout</span></span>
        <span class="small text-muted d-inline-flex align-items-center gap-2"><i class="bi bi-shield-lock"></i><span>Encrypted payment</span></span>
      </div>

      <button disabled={!cartLength || Number(String(TotalPrice).replace('€', '').trim()) <= 0} on:click={() => (window.location.href = "checkout")} class="btn btn-dark fw-semibold shadow-sm rounded-3 w-100 mt-3 d-inline-flex justify-content-center align-items-center gap-2">
        <i class="bi bi-credit-card"></i><span>PROCEED TO CHECKOUT</span><i class="bi bi-arrow-right-short fs-4"></i>
      </button>
    </div>
  </div>

</div>