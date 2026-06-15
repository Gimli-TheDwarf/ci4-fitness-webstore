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

function getDeliveryInfo()
{
    try
    {
        return JSON.parse(sessionStorage.getItem("Delivery")) ?? {};
    }
    catch
    {
        return {};
    }
}

function hasDeliverySelection()
{
    return Boolean(getDeliveryInfo()["Delivery Type"]);
}

onMount(() => 
{
    divItems = document.querySelectorAll('#page-container div');
    item = divItems[1];
    item.classList.remove("opacity-75");

    if (!hasDeliverySelection())
    {
        onNext('./checkout');
    }
});

onDestroy(() => 
{
    item.classList.add("opacity-75");
});

function handleSubmit(e)
{
    e.preventDefault();

    if (!hasDeliverySelection())
    {
        onNext('./checkout');
        return;
    }

    let formData = {};

    jQuery(e.currentTarget).find('select, input').each(function() 
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
    <div class="w-100 flex-fill flex-column d-flex justify-content-start align-items-center"  slot="main">
        <form on:submit={handleSubmit} id="base-info" class="d-flex flex-column justify-content-start align-items-center w-100 gap-1">

            <div class="flex-column w-100 gap-2 text-blacker">
                <h1 class="w-100 text-start fw-semibold mb-1">Your Details</h1>
                <h5 class="w-100 text-dark text-start fw-normal opacity-75">Enter the information that will appear on the invoice</h5>
            </div>

            <div class="w-100 bg-white border rounded-2 shadow-sm p-3 mt-4 mb-4">
            <div class="d-flex w-100 flex-column flex-md-row justify-content-center align-items-center gap-4">
                <div class="flex-fill w-100 d-flex flex-column justify-content-center align-items-start">
                    <label for="name-input">Name</label>
                    <input name="Name" type="text" class="form-control" id="name-input" autocomplete="given-name" minlength="2" maxlength="50" pattern="[A-Za-zÀ-ž' -]{2,50}" title="Use 2 to 50 letters." autocomplete="given-name" minlength="3" maxlength="50" pattern="[A-Za-zÀ-ž\s'-]+" required>
                </div>

                <div class="flex-fill w-100 d-flex flex-column justify-content-center align-items-start">
                    <label for="surname-input">Surname</label>
                    <input name="Surname" type="text" class="form-control" id="surname-input" autocomplete="family-name" minlength="2" maxlength="50" pattern="[A-Za-zÀ-ž' -]{2,50}" title="Use 2 to 50 letters." autocomplete="family-name" minlength="3" maxlength="50" pattern="[A-Za-zÀ-ž\s'-]+" required>
                </div>
            </div>

            <div class="d-flex w-100 flex-column justify-content-center align-items-start pt-4">
                <label for="tel-input">Phone Number</label>
                <input name="Phone Number" type="tel" class="form-control" id="tel-input" autocomplete="tel" inputmode="tel" minlength="7" maxlength="20" pattern="[+]?[0-9][0-9 ().-]{6,19}" title="Use a valid phone number, for example +371 20000000." required>
            </div>
        
            <div class="d-flex w-100 flex-column flex-md-row justify-content-center align-items-center gap-4 pt-4">
                <div class="flex-fill w-100 d-flex flex-column justify-content-center align-items-start">
                    <label for="city-input">City</label>
                    <input name="City" type="text" class="form-control" id="city-input" autocomplete="address-level2" minlength="2" maxlength="80" pattern="[A-Za-zÀ-ž' -]{2,80}" title="Use 2 to 80 letters." autocomplete="address-level2" minlength="3" maxlength="80" pattern="[A-Za-zÀ-ž\s'-]+" required>
                </div>

                <div class="flex-fill w-100 d-flex flex-column justify-content-center align-items-start">
                    <label for="postal-input">Postal Code</label>
                    <input name="Postal Code" type="text" class="form-control" id="postal-input" autocomplete="postal-code" minlength="3" maxlength="12" pattern="[A-Za-z0-9 -]{3,12}" title="Use a valid postal code." autocomplete="postal-code" minlength="3" maxlength="12" pattern="[A-Za-z0-9\s-]+" required>
                </div>
            </div>

            <div class="d-flex w-100 flex-column justify-content-center align-items-start py-4 mb-2">
                <label for="house-input">Address (street, house number)</label>
                <input name="House Address" type="text" class="form-control" id="house-input" autocomplete="street-address" minlength="3" maxlength="120" autocomplete="street-address" minlength="5" maxlength="120" required>
            </div>
            </div>

            <ManeuverButtons prev={() => onPrev('./checkout')} nextType="submit">
            </ManeuverButtons>
        </form>

    </div>           
</CheckoutLayout>
