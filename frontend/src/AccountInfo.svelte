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
    const firstInvalidInput = parentContainer.querySelector("input[data-target]:invalid");

    if (firstInvalidInput)
    {
        firstInvalidInput.reportValidity();
        return;
    }

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
<div class="w-100 d-flex flex-column gap-3">
    <div class="homepage-filter-toolbar bg-white border shadow-sm rounded-2 p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 w-100">
        <div class="d-flex align-items-center gap-2 text-blue-gray">
            <span class="admin-icon-box d-inline-flex justify-content-center align-items-center rounded-2 bg-orange text-white shadow-sm fs-5">
                <i class="bi bi-person-lines-fill"></i>
            </span>
            <div>
                <h1 class="h5 fw-semibold mb-0">Account Information</h1>
                <span class="small text-secondary">Review and update your profile details.</span>
            </div>
        </div>

        <a class="admin-action-button shadow-sm border-0 bg-orange text-light d-inline-flex align-items-center justify-content-center btn-orange rounded-circle text-decoration-none" title="Log Out" aria-label="Log Out" href="logout">
            <i class="fs-4 fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>

    <div id="parent-container" class="bg-light bg-gradient border rounded-2 shadow-sm overflow-hidden p-2 p-md-3 w-100">
        <div class="bg-white rounded-2 shadow-sm border overflow-hidden">
            <div class="bg-blue-gray text-white px-4 py-3 d-flex justify-content-between align-items-center gap-3">
                <div>
                    <span class="small text-uppercase opacity-75 fw-semibold">Profile</span>
                    <h2 class="h6 fw-semibold mb-0">{accountInformation?.username ?? 'Account'}</h2>
                </div>
                <span class="badge rounded-pill bg-orange text-white border-0">{accountInformation?.role ?? 'Unknown'}</span>
            </div>

            <div class="row g-3 p-4">
                <div class="col-12 col-md-6">
                    <label for="username-input" class="form-label small fw-semibold text-blue-gray d-flex align-items-center gap-2"><i class="bi bi-person-badge opacity-75"></i><span>Username</span></label>
                    <input data-target="username" value={accountInformation.username} placeholder={accountInformation?.username ? accountInformation.username : "No username provided"} class:opacity-75={!accountInformation.username} id="username-input" class="rounded-1 border form-control shadow-sm" type="text" autocomplete="username" minlength="3" maxlength="50">
                </div>

                <div class="col-12 col-md-6">
                    <label for="email-input" class="form-label small fw-semibold text-blue-gray d-flex align-items-center gap-2"><i class="bi bi-envelope-at opacity-75"></i><span>Email</span></label>
                    <input data-target="email" value={accountInformation.email} placeholder={accountInformation?.email ? accountInformation.email : "No email provided"} class:opacity-75={!accountInformation.email} id="email-input" class="rounded-1 border form-control shadow-sm" type="email" autocomplete="email" maxlength="254">
                </div>

                <div class="col-12 col-md-6">
                    <label for="phone-input" class="form-label small fw-semibold text-blue-gray d-flex align-items-center gap-2"><i class="bi bi-telephone opacity-75"></i><span>Phone</span></label>
                    <input data-target="phone" value={accountInformation.phone} placeholder={accountInformation?.phone ? accountInformation.phone : "No phone provided"} class:opacity-75={!accountInformation.phone} id="phone-input" class="rounded-1 border form-control shadow-sm" type="tel" autocomplete="tel" inputmode="tel" minlength="7" maxlength="20" pattern="[+]?[0-9][0-9 ().-]{6,19}" title="Use a valid phone number, for example +371 20000000.">
                </div>

                <div class="col-12 col-md-6">
                    <label for="full-name-input" class="form-label small fw-semibold text-blue-gray d-flex align-items-center gap-2"><i class="bi bi-card-text opacity-75"></i><span>Full name</span></label>
                    <input data-target="full_name" value={accountInformation.full_name} placeholder={accountInformation?.full_name ? accountInformation.full_name : "No full name provided"} class:opacity-75={!accountInformation.full_name} id="full-name-input" class="rounded-1 border form-control shadow-sm" type="text" autocomplete="name" minlength="3" maxlength="100" pattern="[A-Za-zÀ-ž' -]{3,100}" title="Use 3 to 100 letters.">
                </div>

                <div class="col-12 col-md-6">
                    <label for="address-input" class="form-label small fw-semibold text-blue-gray d-flex align-items-center gap-2"><i class="bi bi-geo-alt opacity-75"></i><span>Address</span></label>
                    <input data-target="address" value={accountInformation.address} placeholder={accountInformation?.address ? accountInformation.address : "No address provided"} class:opacity-75={!accountInformation.address} id="address-input" class="rounded-1 border form-control shadow-sm" type="text" autocomplete="street-address" minlength="3" maxlength="120">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label small fw-semibold text-blue-gray d-flex align-items-center gap-2"><i class="bi bi-shield-lock opacity-75"></i><span>Role</span></label>
                    <div class="w-100 rounded-1 border bg-light d-flex justify-content-between align-items-center px-3 py-2 shadow-sm">
                        <span class="small opacity-75">Current role</span>
                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis border">{accountInformation?.role ?? 'Unknown'}</span>
                    </div>
                </div>
            </div>

            <div class="w-100 px-4 pb-4 d-flex flex-column flex-md-row justify-content-end align-items-stretch align-items-md-center gap-2">
                <a href="homepage"class="btn btn-outline-secondary fw-semibold shadow-sm rounded-2 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-x-circle"></i><span>Return</span></a>
                <button on:click|preventDefault={() => updateUserInfo()} class="btn btn-orange fw-semibold shadow-sm rounded-2 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-check2-circle"></i><span>Save changes</span></button>
            </div>
        </div>
    </div>
</div>
