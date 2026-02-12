export function addToFavorites(targetItem)
{
    let action = targetItem.favorite ? 'delete' : 'populate';
    targetItem.favorite = !targetItem.favorite;

    jQuery.ajax(
    {
        url: 'PopulateWishList/' + action,
        method: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({item_id: targetItem.id}),
        processData: false,
        
        success: function(response)
        {
            notify(response.message);
        },
        error: function(jqXHR)
        {
            notify(jqXHR.responseJSON.message)
        }
    });
}

export function addToCart(item)
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
            url: 'AddItem/' + item.id + '/' + quantity,
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

export function increase(inputId)
{
    let inputItem = document.getElementById(inputId);
    inputItem.stepUp();
}

export function decrease(inputId)
{
    let inputItem = document.getElementById(inputId);
    inputItem.stepDown();
}