<script>
let { accountInformation } = $props();
import {onMount} from 'svelte';

onMount(() => 
{
    console.log("account information", accountInformation);
})

function updateUserInfo() 
{
    const parentContainer = document.getElementById("parent-container");
    const data = {};

    parentContainer.querySelectorAll("input[data-target]").forEach((el) => 
    {
        data[el.dataset.target] = el.value;
    });

    console.log(data);
    jQuery.ajax(
    {
        url: 'alter-account',
        method: 'PATCH',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(data),

        success:function(response)
        {
            notify(response.message);
        },
        error:function(jqXHR)
        {
            notify(jqXHR.responseJSON.message);
        }
    })
}

</script>
<div class="w-50 h-auto bg-secondary-subtle bg-gradient flex-column d-flex justify-content-start align-items-center rounded-2 shadow-sm border p-2">
    <h5 class="my-2 fw-light d-flex align-items-center gap-2">
        <i class="bi bi-person-lines-fill opacity-75"></i><span>Account Information</span>
    </h5>

    <div id="parent-container" class="d-flex flex-column justify-content-center align-items-center w-100 h-50 bg-light rounded-1 shadow-sm ">

        <div class="d-flex flex-row gap-4 justify-content-center align-items-center w-100 p-4 pb-2">

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="username-input" class="m-0 form-label fw-light d-flex align-items-center gap-2"><i class="bi bi-person-badge opacity-75"></i><span>Username</span></label>
                <input data-target="username" value={accountInformation.username} placeholder={accountInformation?.username ? accountInformation.username : "No username provided"} class:opacity-75={!accountInformation.username} id="username-input" class="rounded-1 border form-control" type="text">
            </div>

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="email-input" class="m-0 form-label fw-light d-flex align-items-center gap-2"><i class="bi bi-envelope-at opacity-75"></i><span>Email</span></label>
                <input data-target="email" value={accountInformation.email} placeholder={accountInformation?.email ? accountInformation.email : "No email provided"} class:opacity-75={!accountInformation.email} id="email-input" class="rounded-1 border form-control" type="text">
            </div>

        </div>

        <div class="d-flex flex-row gap-4 justify-content-center align-items-center w-100 px-4 pb-2">

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="phone-input" class="m-0 form-label fw-light d-flex align-items-center gap-2"><i class="bi bi-telephone opacity-75"></i><span>Phone</span></label>
                <input data-target="phone" value={accountInformation.phone} placeholder={accountInformation?.phone ? accountInformation.phone : "No phone provided"} class:opacity-75={!accountInformation.phone} id="phone-input" class="rounded-1 border form-control" type="text">
            </div>

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="full-name-input" class="m-0 form-label fw-light d-flex align-items-center gap-2"><i class="bi bi-card-text opacity-75"></i><span>Full name</span></label>
                <input data-target="full_name" value={accountInformation.full_name} placeholder={accountInformation?.full_name ? accountInformation.full_name : "No full name provided"} class:opacity-75={!accountInformation.full_name} id="full-name-input" class="rounded-1 border form-control" type="text">
            </div>

        </div>

        <div class="d-flex flex-row gap-4 justify-content-center align-items-center w-100 px-4 pb-3">

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="address-input" class="m-0 form-label fw-light d-flex align-items-center gap-2"><i class="bi bi-geo-alt opacity-75"></i><span>Address</span></label>
                <input data-target="address" value={accountInformation.address} placeholder={accountInformation?.address ? accountInformation.address : "No address provided"} class:opacity-75={!accountInformation.address} id="address-input" class="rounded-1 border form-control" type="text">
            </div>

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label class="m-0 form-label fw-light d-flex align-items-center gap-2"><i class="bi bi-shield-lock opacity-75"></i><span>Role</span></label>
                <div class="w-100 rounded-1 border bg-light d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="small opacity-75">Current role</span>
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis border">{accountInformation?.role ?? 'Uknown'}</span>
                </div>
            </div>

        </div>

        <div class="w-100 px-4 pb-3 d-flex justify-content-end align-items-center gap-2">
            <a href="homepage"class="btn btn-outline-secondary fw-semibold shadow-sm rounded-2 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-x-circle"></i><span>Return</span></a>
            <button on:click|preventDefault={() => updateUserInfo()} class="btn btn-success fw-semibold shadow-sm rounded-2 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-check2-circle"></i><span>Save changes</span></button>
        </div>

    </div>
</div>
