<script>
    import {baseURLStore} from './stores/store-products.js'
    export let baseURL;
    export let ItemsInCart = [];
    console.log(ItemsInCart);

    $: cartLength = ItemsInCart.length;

    $: TotalPrice = ItemsInCart.reduce( (sum, item) => sum + parseFloat(item.price * item.quantity|| 0), 0);
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

<div id="cart-container-wrapper" class="ms-4 me-2 mt-2 mb-2 gap-4 d-flex justify-content-center align-items-center w-100 flex-fill">
  <div id="cart-items-wrapper" class="w-75 bg-gradient border border-2 rounded h-100" style="overflow-y: auto; overflow-x: hidden;">
    <div class="row align-items-stretch m-2">
      {#each ItemsInCart as item}
        <div class="col-12 col-sm-6 col-md-3 my-2">
          <div class="card flex-fill h-100 d-flex flex-column overflow-hidden">
            <img src={ item?.images[0]?.img ? 'images/productsImages/' + item?.images[0].img : 'images/defaultImage.png'  } class="card-img-top" alt={item.name}/>
            <div class="card-body text-center bg-success bg-gradient">
              <h5 class="card-title fw-bold">{item.name}</h5>
              <p class="card-text">{item.description}</p>
            </div>
            <div class="card-footer mt-auto text-center bg-dark text-light">
              <strong>Price per Piece:</strong> {item.price}<br/>
              <strong>Total: </strong> {item.price * item.quantity}<br/>
              <div class="w-100 d-flex justify-content-center" id={'cart-controls-wrapper-' + item.name}>
                <div class="d-flex flex-row w-50 input-group">
                  {#if item.quantity === "0" }
                    <button on:click|preventDefault={() => removeItem(item)} class="btn btn-danger border border-end-0 border-dark w-25"><i class="fa-solid fa-trash"></i></button>
                  {:else if item.quantity > 0}
                    <button on:click|preventDefault={() => Change(item, -1)} class="btn btn-danger border border-end-0 border-dark w-25"><i class="fa-solid fa-minus"></i></button>
                  {/if}
                    <input id={'quantity-input-' + item.name} class="ps-2 w-50 no-focus-outline no-spin border border-dark" type="number" min="0" step="1" bind:value={item.quantity}>
                    <button on:click|preventDefault={() => Change(item, 1)} class="btn btn-success border border-start-0 border-dark w-25"><i class="fa-solid fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      {/each}
    </div>
  </div>

  <div class="d-flex flex-column justify-content-center align-items-center border border-2 w-25 h-100 bg-success bg-gradient rounded flex-shrink-0 ">
    <div id="imageContainer" class="m-1">
        <h5 class="text-center fw-bold text-light text-shadow">Adverts Will Go Here Later</h5>
        <img class="rounded img-fluid border border-2" src={'images/' + 'defaultImage.png'}>
    </div>
    <span class="fw-bold">Subtotal ({cartLength}) items: <span class="text-warning text-shadow">€{TotalPrice}</span></span>
    <a class="btn btn-lng btn-danger mt-4" href="checkout">Proceed to Checkout</a>
  </div>
</div>

<style>
  .card-title 
  {
    font-size: clamp(0.8rem, 2vw, 1.25rem);
  }
</style>
