import { writable, get } from 'svelte/store';
export const productsStore = writable([]);
export const tagsStore = writable([]);
export const usersStores = writable([]);
export const baseURLStore = writable('');
// export async function initialiseViaController()
// {
//     const retrieveStuff = await fetch('retrieveBasicInfo', 
//     {
//         method: "GET",
//         headers: 
//         {
//             "Content-Type": "application/json",
//         },
//     });

//     const response = await(retrieveStuff)
//     if(!response.ok)
//     {
//         notify(response.status);
//     }
//     else
//     {
//         let users = response.users;
//         let products = response.products;
//         let tags = response.tags;
//         let baseURL = 
//     }
// }

export function initialiseAdminStore(users, products, tags, baseURL)
{
    console.log(get(productsStore));
    usersStores.set(Array.isArray(users) ?  users : []);
    productsStore.set(Array.isArray(products) ?  products : []);
    tagsStore.set(Array.isArray(tags) ? tags : []);
    baseURLStore.set(baseURL);
}

export function initialiseProducts(list, listName)
{
    console.log("INITIALISE");
    listName.set(Array.isArray(list) ? list : []);
}

// .set(newValue) → replace the value.

// .update(fn) → change the value based on its current value.

// .subscribe(fn) → run fn every time the value changes.

export function updateProducts(newProducts)
{
    console.log("new products: ", newProducts);
    const incomingData = Array.isArray(newProducts) ? newProducts : newProducts ? [newProducts] : [];

    if(incomingData.length === 0)
    {
        console.log("failed check");
        return;
    };

    const mapProducts = new Map(incomingData.map(dataCell => [String(dataCell.id), dataCell]));
    //basically stringifying the p.id and setting it as the key whilst the rest becomes the value.
    productsStore.update(storeItems => 
    {
        const newArray = storeItems.map(item => 
        {
            return mapProducts.get(String(item.id)) ?? item;
        });

        console.log("updated products: " , newArray);
        return newArray;
    });
}

export function retrieveItem(itemId, itemStore)
{
    let store = get(itemStore);
    
    let returnValue = store.find(item => item.id === itemId) ?? null; 
    if(!returnValue) notify("Nothing was found");
    return returnValue;
}

export function removeProduct(itemId, productImageName = null)
{
    productsStore.update(list => 
    {
        console.log("old list: " , get(productsStore));
        if(productImageName === null)
        {
            console.log("no image provided, removing item from store");
            return list.filter(listItem => String(listItem.id) !== String(itemId));
        }
        else
        {
            return list.map(listItem => 
            {
                if(String(listItem.id) !== String(itemId))
                {
                    console.log("Didnt find a matching list item yet");
                    return listItem;
                }
                else
                {
                    let newImages = listItem.images.filter(image => 
                    {
                        console.log(String(image.img), "___", String(productImageName));
                        return String(image.img) != String(productImageName);
                        //return is important, otherwise array will ALWAYS be empty
                    });
                    console.log("updated images: " , newImages);
                    return { ...listItem, images: newImages };
                };
            });
        };
    });
}

export function updateTags(tags)
{
    console.log("old tags: ", tagsStore);
    tagsStore.set(Array.isArray(tags) ? tags : []);
    console.log("new tags: ", tagsStore);
}