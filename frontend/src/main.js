import { mount } from 'svelte';
import Product from './Products.svelte';
import Tags from './Tags.svelte';
import CartItems from './Cart.svelte';
import SearchBar from './SearchBar.svelte';
import InfoWindow from './ModalWindow.svelte';
import Checkout from './Checkout.svelte';
import Delivery from './Delivery.svelte';

document.addEventListener('DOMContentLoaded', () => 
{
  
  const tagsContainer = document.getElementById('tags-list') || null;
  const productsContainer = document.getElementById('product-list') || null;
  const CartContainer = document.getElementById("cart-container") || null;
  const searchBar = document.getElementById("search-bar-container") || null;
  const modalWindow = document.getElementById("global-modal-window") || null;
  const checkoutContainer = document.getElementById("checkout-container") || null;
  const deliveryContainer = document.getElementById("delivery-container") || null;

  // DATA START
  const baseURL = document.getElementById("base-url-container").dataset.baseurl || null;
  const rawLoadData = document.getElementById('boot-data')?.textContent;
  const parsedData = rawLoadData ? JSON.parse(rawLoadData) : null;
  const productsData = parsedData ? parsedData.products : null;
  const tagsData = parsedData ? parsedData.tags : null;
  // DATA END

  let ProductMount; 

  let rawSearchData = sessionStorage.getItem('searchResults'); //retrieve search results for searchbar 
  let newData = rawSearchData ? JSON.parse(rawSearchData) : null;
  let infoData = Array.isArray(newData) && newData.length > 0 ? newData : productsData; //save the search results here
  sessionStorage.removeItem('searchResults'); //on new page remove the session for search results

  function handleSelection(input)
  {
    if(!productsContainer)
    {
      sessionStorage.setItem('searchResults', JSON.stringify(input));
      window.location.href = "/homepage";
      return;
    }

    infoData = input.length === 0 ? productsData : input;
    ProductMount.info = infoData;
  }

  if (tagsContainer && productsContainer) //if homepage
  {
    console.log("products data: ", productsData)

    ProductMount = mount(Product, 
    {
      target: productsContainer,
      props:  { info: infoData, baseURL: baseURL},
    });

    mount(Tags, 
      {
      target: tagsContainer,
      props: 
      {
        tagsInfo: tagsData,
        TagsAreSelected: selected => 
        {
          const filtered = productsData.filter(p => selected.length === 0 || selected.every(tag => p.tags?.includes(tag)));
          ProductMount.info = filtered; //FIX THIS LATER
          console.log('filtered products:', filtered);
        }
      }
    });
  }

  else if (CartContainer)
  {
    const cartData = JSON.parse(document.getElementById('cart-items').textContent);

    mount(CartItems, 
      {
        target: CartContainer,
        props: { ItemsInCart : cartData },
      }
    );
  }

  if(searchBar)
  {
    mount(SearchBar, 
    {
      target: searchBar,
      props:
      {
        itemsList: productsData ?? [],
        SelectedItems: handleSelection
      }
    });
  }

  if(modalWindow)
  {
    mount(InfoWindow, 
    {
      target: modalWindow
    });
  }

  if(checkoutContainer)
  {
    mount(Checkout,
      {
        target: checkoutContainer
      }
    )
  }

  if(deliveryContainer)
  {
    mount(Delivery,
      {
        target: deliveryContainer
      }
    )
  }
  
});