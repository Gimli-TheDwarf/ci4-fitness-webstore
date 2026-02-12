import { mount, unmount } from 'svelte';
import AdminHome from './AdminHome.svelte';
import AdminPanel from './AdminPanel.svelte';
import AdminProducts from './AdminProducts.svelte';
import AdminSales from  './AdminSales.svelte';
import AdminUsers from './AdminUsers.svelte';
import AdminTags from './AdminTags.svelte';
import AdminSpecificProduct from './ProductListing.svelte';
import { initialiseAdminStore, productsStore } from './stores/store-products.js';

const comps = { home: AdminPanel, products: AdminProducts, sales: AdminSales, users: AdminUsers, tags: AdminTags, specificProduct: AdminSpecificProduct };
let component;
let location = null;
let bootData = {};

const adminPanel = document.getElementById("admin-panel-container") || null;
const baseDataContainer = document.getElementById("admin-boot-data") || null;

if (baseDataContainer)
{
  bootData = JSON.parse(baseDataContainer.textContent);
}

initialiseAdminStore(
  bootData.users,
  bootData.products,
  bootData.tags,
  bootData.baseURL
);

function handleSelection(locationSelect, mode = null, passedProduct = null)
{

  location = comps[locationSelect];
  unmount(component);
  let props = { locationSelection: handleSelection };

  if(mode)
  {
    props.mode = mode;
  }

  if(passedProduct)
  {
    props.passedProduct = passedProduct;
    console.log("PROPS PRODUCT: " , props.passedProduct);
  }

  component = mount(location, 
  {
    target: adminPanel,
    props
  });
} 

if(adminPanel)
{

  component = mount(AdminHome,
  {
    target: adminPanel,
    props: 
    {
      locationSelection: handleSelection
    }, 
  });
}