<script>
import jquery from "jquery";
import { updateTags, removeProduct as removeProductFromStore } from "./stores/store-products";
import { onMount } from "svelte";

let modal;
let modalBootstrap;
let modalInput;

onMount(() => 
{
    modal = document.getElementById("global-modal-window");
    modalBootstrap = new bootstrap.Modal(modal);

    modal.addEventListener('show.bs.modal', (e) => 
    {
        console.log("HELP");
        let triggerElement = e.relatedTarget;
        let modalMode = triggerElement?.getAttribute('data-mode') ?? null;
        let modalInfo = triggerElement?.getAttribute('data-info') ?? null;
        let modalData = triggerElement?.getAttribute('data-data') ?? null;

        console.log(modalMode);

        console.log(modalMode, " ", modalInfo);
        
        let modalTitle = modal.querySelector(".modal-title");
        let modalBody = modal.querySelector(".modal-text");
        let modalImage = modal.querySelector(".modal-image");
        modalInput = modal.querySelector(".form-control");
        modalInput.classList.remove("d-flex");
        modalInput.classList.add("d-none");
        modalInput.value = "";

        let modalButton1 = modal.querySelector(".modal-button-1");
        let modalButton2 = modal.querySelector(".modal-button-2");
        jquery(modalButton1).off();
        
        switch (modalMode)
        {
            case 'delete-product':
                modalTitle.innerHTML = "Product Removal";
                modalBody.innerHTML = modalInfo;
                modalImage.src = "images/windowImages/deleteItemIcon.png";

                modalButton1.textContent = "Remove Product";
                jquery(modalButton1).off();
                jquery(modalButton1).on("click", () =>
                {
                    deleteProduct(modalData);
                });
                break;
            case 'delete-tag':

                modalTitle.innerHTML = "Tag Removal";
                modalBody.innerHTML = modalInfo;
                modalImage.src = "images/windowImages/deleteItemIcon.png";

                modalButton1.textContent = "Remove Tag";
                jquery(modalButton1).off();
                jquery(modalButton1).on("click",() => 
                {
                    removeTag(modalData);
                }); 
                break;
            case 'tag-change-name':
                console.log("CHANGE TAG NAME CASE");
                modalTitle.innerHTML = "Tag Name Change";
                modalBody.innerHTML = modalInfo;
                modalImage.src = "images/windowImages/changeNameIcon.png";
                modalInput.classList.remove('d-none');
                modalInput.classList.add('d-flex');
                
                modalButton1.textContent = "Change Name";
                jquery(modalButton1).off();
                jquery(modalButton1).on("click", () => 
                {
                    changeTagName(modalData ,modalInput.value);
                });
                
                break;
            case 'add-tag':
                console.log("ADD TAG CASE");
                modalTitle.innerHTML = "New Tag Addition";
                modalBody.innerHTML = modalInfo;
                modalButton1.innerHTML = "Insert Tag"
                modalImage.src = "images/windowImages/insertItemIcon.png";
                modalInput.classList.remove('d-none');
                modalInput.classList.add('d-flex');
                modalInput.placeholder = "Insert Name of Tag...";

                jquery(modalButton1).on("click", () => 
                {
                    if(!modalInput.value || modalInput.value.trim().length === 0)
                    {
                        notify("Insert a valid tag name");
                        return;
                    }
                    console.log("Input value:", modalInput.value);
                    uploadTag(modalInput.value);
                });
            break;

            default: 
                break;
        }
    });
});

let responseMessage;


function deleteProduct(productId)
{
    modalBootstrap.hide();

    jquery.ajax({
        url: "removeProduct",
        method: "DELETE",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify({ product_id: productId }),

        success: function (response)
        {
            notify(response.message);
            removeProductFromStore(productId);
        },
        error: function(jqXHR)
        {
            responseMessage = jqXHR.responseJSON?.message ?? "Failed to delete product.";
            notify(responseMessage);
        }
    });
}


function uploadTag(name)
{
    modalInput.value = "";
    modalBootstrap.hide();
    jquery.ajax(
        {
        url: "addNewTag",
        method: 'POST',
        contentType: "application/json",
        dataType: 'json',
        data: JSON.stringify({tag_name: name}),

        success: function(success)
        {
            responseMessage = success.message;
            updateTags(success.tags);
            notify(responseMessage);
        },
        error: function(jqXHR)
        {
            responseMessage = jqXHR.responseJSON.message;
            notify(responseMessage);
        }
    });
}

function changeTagName(id, name)
{
    modalBootstrap.hide();
    jquery.ajax({
        url: "ChangeTag",
        method: 'PATCH',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({id: id, name: name}),
        
        success: function(response)
        {
            notify(response.message);
            updateTags(response.tags);
        },
        error: function(jqXHR)
        {
            responseMessage = jqXHR.responseJSON.message;
            notify(responseMessage);
        }
    });
}

function removeTag(item)
{
    modalBootstrap.hide();
    jquery.ajax({
        url: "removeTag",
        method: 'DELETE',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({tag_id: item}),

        success: function(response)
        {
            notify(response.message);
            updateTags(response.tags);
        },
        error: function(jqXHR)
        {
            responseMessage = jqXHR.responseJSON.message;
            notify(responseMessage);
        }
    });
};
</script>

<div class="modal-dialog">

    <div class="modal-content bg-weird-white rounded-1 border-0">

        <div class="modal-header border-0 p-2 m-0">
            <button type="button" class="hover-fx-1 btn btn-dark text-secondary m-0 ms-auto p-0 fs-4 bg-transparent border-0 rounded-0" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-square"></i></button>
        </div>

        <div class="modal-body d-flex justify-content-center border-bottom">
            <img class="modal-image w-50 h-auto" alt="imageIcon" src="images/windowImages/defaultIcon.png">
        </div>

        <div class="d-flex flex-column align-items-center justify-content-center border-0 modal-footer">

            <span class="modal-title fw-bold fs-5">Default Window</span>
            <text class="modal-text text-dark text-center">Insert paragraph here.</text>

            <div class="p-2 m-0 w-100 d-flex justify-content-center align-items-center gap-3">
                <button type="button" class="modal-button-1 w-35 shadow-sm btn btn-outline-success rounded-1">Confirm</button>
                <button data-bs-dismiss="modal" type="button" class="modal-button-2 w-35 shadow-sm btn btn-outline-primary border-dashed  rounded-1">Cancel</button>
            </div>

            <div class="d-flex w-75 p-2 m-0 justify-content-center align-items-center">
                <input class="shadow-sm d-none form-control w-100" id="modal-window-input-field" type="text" placeholder="New tag name...">
            </div>

        </div>

    </div>
</div>