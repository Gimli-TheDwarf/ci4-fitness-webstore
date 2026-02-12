<!-- <script>
    export let users = [];
    export let products = [];
    export let tags = [];
    import 'select2';
    import { onMount, onDestroy } from 'svelte';

    let cost = 0.01;
    $: if(cost < 0.01 || !cost) cost = 0.01;
    $: cost = Number(cost.toFixed(2));

    
    let tagInput;
    let tagId;
    let modal;
    let BootstrapModal;
    let Select2Element;
    let modalTitle;
    let removeButton;
    let onSubmit;

    onMount(() => 
    {
        console.log("IM LOSING MY DUMBASS MIND");
        jQuery(Select2Element).select2({
            width: '100%',
            placeholder: 'Select Tags'
        });

        modal = document.getElementById("InfoModal");
        BootstrapModal = new bootstrap.Modal(modal);

        modalTitle = modal.querySelector(".modal-title");
        removeButton = modal.querySelector("#removeTagBtn");
    })

    onDestroy(() => 
    {
        jQuery(Select2Element).select2('destroy');
    });

    function addItem()
    {
        jQuery.ajax({
            url: 'AddNewProduct',
            method: 'POST',
            data: new FormData(document.getElementById("productsForm")),
            dataType: 'json',
            processData: false, //makes jquery not turn the data into a url-encoded string
            contentType: false, //basically stuff we have to use to send form data, otherwise jquery will kill it

            success: function(response)
            {
                console.log(response.message);
                notify(response.message);
                document.getElementById('productsForm').reset();
                jQuery('#tagsSelection').val(null).trigger('change');
                cost = 0.01;
            },
            error: function(jqXHR)
            {
                let msgElement = jqXHR.responseJSON.messages;
                let msg = Object.values(msgElement).flat().join('<br>');
                notify(msg);
            }
        })
    }

    function changeName(name) //change name of a tag
    {
        console.log("CHANGING THE NAME");
        jQuery.ajax({
            url: 'ChangeTag',
            method: 'PATCH',
            data: JSON.stringify({name: name, id: tagId }),
            dataType: 'json',
            contentType: 'application/json',

            success: function(success)
            {
                notify(success.message);
                BootstrapModal.hide();
                tags = success.tags;
                tagInput = '';
            },
            error: function(jqXHR)
            {
                let msg = jqXHR.responseJSON.messages;
                console.log("ERROR");
                notify(msg);
            }
        })
    }

    function openModal(item) //open a modal window for CHANGING TAG NAME OR REMOVING IT
    {
        tagId = item.id;

        modal.querySelector(".modal-title").textContent = item.name;
        onSubmit = () => changeName(tagInput);
        removeButton.style.display = 'block';
        BootstrapModal.show();
    }

    function removeItem() //remove tag function
    {
        jQuery.ajax({
            url: 'removeTag',
            method: 'DELETE',
            data: JSON.stringify({tagId: tagId}),
            dataType: 'json',
            contentType: 'application/json',

            success: function(success)
            {
                notify(success.message);
                BootstrapModal.hide();
                tags = success.tags;
                tagInput = '';
            },
            error: function(jqXHR)
            {
                let msg = jqXHR.responseJSON.messages;
                notify(msg);
            }
        });
    }
    
    function addTagModal() //open modal window for ADDING A TAG
    {

        modalTitle.textContent = "Insert new Tag";
        removeButton.style.display = 'none';
        onSubmit = () => addTag();
        BootstrapModal.show();
    }

    function addTag() //function for ADDING a tag
    {
        console.log('raaaagh');
        jQuery.ajax({
            url: 'addNewTag',
            method: 'POST',
            data: JSON.stringify({tagName: tagInput}),
            dataType: 'json',
            contentType: 'application/json',

            success: function(success)
            {
                notify(success.message);
                BootstrapModal.hide();
                tags = success.tags;
                tagInput = '';
                removeButton.style.display = 'visible';
            },
            error: function(jqXHR)
            {
                let msg = jqXHR.responseJSON.messages;
                notify(msg);
            }
        });
    }
</script>

<div id="adminForm" class="mt-4 w-100 d-flex justify-content-center flex-column align-items-center">
    <form on:submit|preventDefault={addItem} class="w-100 shadow bg-light bg-gradient p-2 rounded" id="productsForm" method="post" enctype="multipart/form-data">
        <h3 class="w-100 d-flex justify-content-center text-center mb-3">Add New Product</h3>

        <div class="mb-3">
            <label for="productNameInput" class="form-label">Product Name</label>
            <input type="text" id="productNameInput" name="productName" class="form-control" placeholder="Name…" required>
        </div>
        
        <div class="mb-3">
            <label for="productDescriptionInput" class="form-label">Description</label>
            <textarea id="productDescriptionInput" name="productDescription" class="form-control" placeholder="Description…" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semi-bold">Tags</label>
            <select name="productTags[]" id="tagsSelection" multiple bind:this={Select2Element}>
                {#each tags as tag}
                    <option value={tag.id}>{tag.name}</option>
                {/each}
            </select>
        </div>

        <label for="productPriceInput" class="form-label">Price</label>
        <div class="mb-3 d-flex flex-row w-100 input-group">
            <button disabled={cost === 0.01} on:click|preventDefault={() => cost -= 0.01} class="btn btn-danger  w-25"><i class="fa-solid fa-minus"></i></button>
            <input name="cost" id="productPriceInput" class="ps-2 w-50 no-focus-outline no-spin border" bind:value={cost} type="number" min="0.01" step="0.01">
            <button on:click|preventDefault={() => cost += 0.01} class="btn btn-success w-25"><i class="fa-solid fa-plus"></i></button>
        </div>
       
        <div class="mb-3">
            <label for="productImageInput" class="form-label">Image</label>
            <input type="file" id="productImageInput" name="productImage" class="form-control" accept="image/*">
        </div>

        
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>

    <div id="tagsInfo" class="p-1 w-100 shadow bg-light bg-gradient rounded">
        <h3 class="w-100 d-flex justify-content-center text-center mb-3">Tags</h3>

        <div class="form-control mb-1 overflow-hidden">
            <div class="w-100 row g-2 overflow-y-auto no-scrollbar" style="max-height: 24vh;">
                {#each tags as tag}
                    <div class="col-3 d-flex">
                        <button type="button" on:click|preventDefault={() => openModal(tag)} class="h-100 w-100 btn btn-primary fs-5">{tag.name}</button>
                    </div>
                {/each}
            </div>
        </div>

        <div class="mb-1">
            <button type="button" on:click|preventDefault={() => addTagModal()} class="btn btn-secondary w-100 fs-5">Add Tag</button>
        </div>
    </div>
</div>


<div id="InfoModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Default</h5>
                <button type="button" class=" btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="tagNameInput" class="form-label">New Tag Name</label>
                    <div class="d-flex justify-content-center gap-2">
                        <input type="text" id="tagNameInput" bind:value={tagInput} class="w-75 form-control" placeholder="Enter a new name">
                        <button on:click|preventDefault={onSubmit}  type="button" class="w-25 btn btn-primary">Submit</button>
                    </div>

                </div>
                <button on:click|preventDefault={removeItem} type="button" class="w-100 btn btn-danger" id="removeTagBtn">Remove Tag</button>
            </div>
        </div>
    </div>
</div> -->



THIS WHOLE THING IS RETIRED, AN OLD VERSION OF A FORM