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

    // block submit unless all filled fields pass type/minlength/pattern/ and so on
    if (!parentContainer.checkValidity())
    {
        parentContainer.classList.add("was-validated");
        parentContainer.reportValidity(); 
        return;
    }

    const data = {};
    parentContainer.querySelectorAll("input[data-target]").forEach((el) => 
    {
        data[el.dataset.target] = (el.value ?? "").trim();
    });

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

<div class="w-50 h-auto bg-body bg-gradient flex-column d-flex justify-content-start align-items-center shadow-sm border border-success-subtle p-3">
    <div class="w-100 d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h5 class="m-0 fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-person-lines-fill text-success-emphasis opacity-75"></i><span>Account Information</span>
        </h5>
        <span class="small text-muted">Edit and save your details</span>
    </div>

    <form id="parent-container" class="d-flex flex-column justify-content-center align-items-center w-100 bg-white rounded-3 shadow-sm border border-secondary-subtle" on:submit|preventDefault={() => updateUserInfo()} novalidate>

        <div class="d-flex flex-row gap-4 justify-content-center align-items-center w-100 p-4 pb-2">

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="username-input" class="m-0 form-label small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-person-badge opacity-75"></i><span>Username</span></label>
                <input required name="Username" data-target="username" value={accountInformation.username} placeholder={accountInformation?.username ? accountInformation.username : "No username provided"} class:opacity-75={!accountInformation.username} id="username-input" class="rounded-2 border border-secondary-subtle form-control form-control-sm bg-white focus-ring focus-ring-success" type="text" autocomplete="username" inputmode="text" minlength="3" maxlength="32" pattern="^[A-Za-z0-9](?:[A-Za-z0-9._-]{1,30}[A-Za-z0-9])?$" title="3–32 characters. Letters/numbers; dots, underscores and hyphens allowed." spellcheck="false">
            </div>

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="email-input" class="m-0 form-label small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-envelope-at opacity-75"></i><span>Email</span></label>
                <input required name="Email" data-target="email" value={accountInformation.email} placeholder={accountInformation?.email ? accountInformation.email : "No email provided"} class:opacity-75={!accountInformation.email} id="email-input" class="rounded-2 border border-secondary-subtle form-control form-control-sm bg-white focus-ring focus-ring-success" type="email" autocomplete="email" inputmode="email" maxlength="254" title="Enter a valid email address." spellcheck="false">
            </div>

        </div>

        <div class="d-flex flex-row gap-4 justify-content-center align-items-center w-100 px-4 pb-2">

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="phone-input" class="m-0 form-label small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-telephone opacity-75"></i><span>Phone</span></label>
                <input name="Phone" data-target="phone" value={accountInformation.phone} placeholder={accountInformation?.phone ? accountInformation.phone : "No phone provided"} class:opacity-75={!accountInformation.phone} id="phone-input" class="rounded-2 border border-secondary-subtle form-control form-control-sm bg-white focus-ring focus-ring-success" type="tel" autocomplete="tel" inputmode="tel" minlength="8" maxlength="20" pattern="^\+?[0-9][0-9\s-]{6,18}[0-9]$" title="Digits only (you may include +, spaces, or hyphens). Example: +371 2XXXXXXX">
            </div>

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="full-name-input" class="m-0 form-label small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-card-text opacity-75"></i><span>Full name</span></label>
                <input name="Full name" data-target="full_name" value={accountInformation.full_name} placeholder={accountInformation?.full_name ? accountInformation.full_name : "No full name provided"} class:opacity-75={!accountInformation.full_name} id="full-name-input" class="rounded-2 border border-secondary-subtle form-control form-control-sm bg-white focus-ring focus-ring-success" type="text" autocomplete="name" inputmode="text" minlength="2" maxlength="80" pattern="^[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž][A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž' -]{0,78}[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž]$" title="2–80 characters. Letters only; spaces, hyphens and apostrophes allowed.">
            </div>

        </div>

        <div class="d-flex flex-row gap-4 justify-content-center align-items-center w-100 px-4 pb-3">

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label for="address-input" class="m-0 form-label small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-geo-alt opacity-75"></i><span>Address</span></label>
                <input name="Address" data-target="address" value={accountInformation.address} placeholder={accountInformation?.address ? accountInformation.address : "No address provided"} class:opacity-75={!accountInformation.address} id="address-input" class="rounded-2 border border-secondary-subtle form-control form-control-sm bg-white focus-ring focus-ring-success" type="text" autocomplete="street-address" inputmode="text" minlength="5" maxlength="120" pattern="^[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9][A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9 .,'/#-]{3,118}[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9]$" title="5–120 characters. Letters/numbers and basic address punctuation allowed.">
            </div>

            <div class="w-50 d-flex flex-column justify-content-center align-items-start h-100">
                <label class="m-0 form-label small fw-semibold text-muted d-flex align-items-center gap-2"><i class="bi bi-shield-lock opacity-75"></i><span>Role</span></label>
                <div class="w-100 rounded-2 border border-secondary-subtle bg-white d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="small text-muted">Current role</span>
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle">{accountInformation?.role ?? 'Unknown'}</span>
                </div>
            </div>

        </div>

        <div class="w-100 px-4 pb-3 d-flex justify-content-end align-items-center gap-2">
            <a href="homepage" class="btn btn-outline-secondary btn-sm fw-semibold shadow-sm rounded-3 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-x-circle"></i><span>Return</span></a>
            <button type="submit" class="btn btn-success btn-sm fw-semibold shadow-sm rounded-3 d-flex justify-content-center align-items-center gap-2"><i class="bi bi-check2-circle"></i><span>Save changes</span></button>
        </div>

    </form>
</div>