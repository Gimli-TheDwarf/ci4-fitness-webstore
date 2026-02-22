<script>
import CheckoutLayout from './CheckoutLayout.svelte';
import ManeuverButtons from './ManeuverButtons.svelte'
import {onMount, onDestroy} from 'svelte';
import jquery from 'jquery'
import 'select2';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';
import {onPrev, onNext} from './ManeuverFunctions.js';

let divItems;
let item;

onMount(() => 
{
    divItems = document.querySelectorAll('#page-container div');
    item = divItems[1];
    item.classList.remove("opacity-75");
});

onDestroy(() => 
{
    item.classList.add("opacity-75");
});

function handleSubmit(e)
{
    e.preventDefault();
    let formData = {};

    jQuery('#base-info').find('select, input').each(function() 
    {
        let name = this.name;
        let value = this.value;
        if (!name || !value) return

        formData[name] = value;
    });
    sessionSet('Billing', formData)
    onNext('./billing');
}

</script>

<CheckoutLayout>
    <div class="w-100 flex-fill flex-column d-flex justify-content-start align-items-center" slot="main">
        <form on:submit={handleSubmit} id="base-info" class="d-flex flex-column justify-content-start align-items-center w-75 h-50 gap-1" autocomplete="on" novalidate={false}>

            <div class="flex-column w-100 gap-2 pt-4 text-blacker">
                <h1 class="w-100 text-start">Your Details</h1>
                <h5 class="w-100 text-dark text-start">Enter the information that will appear on the invoice</h5>
            </div>

            <div class="d-flex w-100 justify-content-center align-items-center gap-4 mt-4"> 
                <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                    <label for="name-input">Name</label>
                    <input name="Name" type="text" class="form-control" id="name-input" required minlength="3" maxlength="40" autocomplete="given-name" inputmode="text" pattern="^[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž][A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž' -]{0,38}[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž]$" title="2–40 characters. Letters only; spaces, hyphens and apostrophes allowed.">
                </div>

                <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                    <label for="surname-input">Surname</label>
                    <input name="Surname" type="text" class="form-control" id="surname-input" required minlength="3" maxlength="50" autocomplete="family-name" inputmode="text" pattern="^[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž][A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž' -]{0,48}[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž]$" title="2–50 characters. Letters only; spaces, hyphens and apostrophes allowed.">
                </div>
            </div>

            <div class="d-flex w-100 flex-column justify-content-center align-items-start pt-4">
                <label for="tel-input">Phone Number</label>
                <input name="Phone Number" type="tel" class="form-control" id="tel-input" required minlength="8" maxlength="20" autocomplete="tel" inputmode="tel" placeholder="+371 2XXXXXXX" pattern="^\+?[0-9][0-9\s-]{6,18}[0-9]$" title="Use digits only (you may include +, spaces, or hyphens). Example: +371 2XXXXXXX">
            </div>
        
            <div class="d-flex w-100 justify-content-center align-items-center gap-4 pt-4"> 
                <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                    <label for="city-input">City</label>
                    <input name="City" type="text" class="form-control" id="city-input" required minlength="2" maxlength="60" autocomplete="address-level2" inputmode="text" pattern="^[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9][A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9 .,'-]{0,58}[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9]$" title="2–60 characters. Letters/numbers; spaces and basic punctuation allowed.">
                </div>

                <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                    <label for="postal-input">Postal Code</label>
                    <input name="Postal Code" type="text" class="form-control" id="postal-input" required minlength="4" maxlength="12" autocomplete="postal-code" inputmode="text" pattern="^[A-Za-z0-9][A-Za-z0-9 -]{2,10}[A-Za-z0-9]$" title="Enter a valid postal code (letters/numbers, spaces or hyphens allowed).">
                </div>
            </div>

            <div class="d-flex w-100 flex-column justify-content-center align-items-start py-4 mb-2">
                <label for="house-input">Address (street, house number)</label>
                <input name="House Address" type="text" class="form-control" id="house-input" required minlength="5" maxlength="120" autocomplete="street-address" inputmode="text" pattern="^[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9][A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9 .,'/#-]{3,118}[A-Za-zĀČĒĢĪĶĻŅŠŪŽāčēģīķļņšūž0-9]$" title="5–120 characters. Letters/numbers and basic address punctuation allowed.">
            </div>

            <ManeuverButtons prev={() => onPrev('./checkout')} nextType="submit">
            </ManeuverButtons>
        </form>

    </div>           
</CheckoutLayout>