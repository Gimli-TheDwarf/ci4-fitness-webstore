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

const requiredDeliveryFields = ['Delivery Type', 'City', 'Terminal', 'Name', 'Surname', 'Phone Number'];

onMount(() => 
{
    divItems = document.querySelectorAll('#page-container div');
    item = divItems[0];
    item.classList.remove("opacity-75");

    jQuery('.form-select').each(function () 
    {
        console.log("ra");
        let placeholder = this.dataset.placeholder
        console.log(placeholder)

        jQuery(this).select2(
        {
            theme: 'bootstrap-5',
            width: '100%',
            placeholder,
            closeOnSelect: true,
            minimumResultsForSearch: Infinity
        });
    })
});

onDestroy(() => 
{
    item.classList.add("opacity-75");
});

function handleSubmit(e)
{
    e.preventDefault();
    let form = e.currentTarget;
    let selectedDeliveryType = form.querySelector('input[name="Delivery Type"]:checked');

    if (!selectedDeliveryType)
    {
        form.querySelector('input[name="Delivery Type"]')?.reportValidity();
        return;
    }

    let formData = {};

    jQuery(form).find('input, select').each(function() 
    {
        let name = this?.name; 
        let value = this?.value;

        if ((this.type === 'checkbox' || this.type === 'radio') && !this.checked) return
        if (!name || !value) return

        formData[name] = value
    });

    let missingField = requiredDeliveryFields.find(field => !formData[field]);

    if (missingField)
    {
        form.querySelector(`[name="${missingField}"]`)?.reportValidity();
        return;
    }

    sessionSet('Delivery', formData)
    onNext('./delivery');
}

</script>

<CheckoutLayout>
    <div class="w-100 flex-fill d-flex justify-content-center align-items-start" slot="main">
        <form on:submit={handleSubmit} id="base-info" class="d-flex flex-column justify-content-start align-items-center w-100 gap-1">

            <div class="flex-column w-100 gap-2 text-blacker">
                <h1 class="w-100 text-start fw-semibold mb-1">Delivery</h1>
                <h5 class="w-100 text-dark text-start fw-normal opacity-75">Choose a delivery point</h5>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-4 border rounded-2 p-3 bg-white shadow-sm">
                <input name="Delivery Type" class="input-checkbox fs-3 m-0 form-check-input" type="radio" value="Omniva Parcel" id="checkbox-option1" required>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option1">Delivery to an Omniva parcel locker: 2-3 days.</label>
                <span class="ms-auto">2.99 €</span>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-2 border rounded-2 p-3 bg-white shadow-sm">
                <input name="Delivery Type" class="input-checkbox fs-3 m-0 form-check-input" type="radio" value="DPD Parcel" id="checkbox-option2" required>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option2">Delivery to a DPD parcel locker: 2-3 days.</label>
                <span class="ms-auto">2.99 €</span>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-2 border rounded-2 p-3 bg-white shadow-sm">
                <input name="Delivery Type" class="input-checkbox fs-3 m-0 form-check-input" type="radio" value="SmartPosti Parcel" id="checkbox-option3" required>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option3">Delivery to a SmartPosti parcel locker: 2-3 days.</label>
                <span class="ms-auto">4.00 €</span>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-2 border rounded-2 p-3 bg-white shadow-sm mb-4">
                <input name="Delivery Type" class="input-checkbox fs-3 m-0 form-check-input" type="radio" value="DPD Courier Delivery" id="checkbox-option4" required>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option4">Home delivery with a DPD courier in 2-3 days.</label>
                <span class="ms-auto">5.59 €</span>
            </div>

            <div class="d-flex w-100 flex-column justify-content-start align-items-center gap-4 mt-4">
                <h5 class="w-100 text-dark text-start">Please confirm the delivery details</h5>

                <div class="d-flex w-100 flex-column justify-content-center align-items-start gap-3 mb-4 bg-white border rounded-2 shadow-sm p-3">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-start">
                        <label class="d-flex h-100 text-center align-items-center" for="info-input1">City</label>
                        <select name="City" class="m-0 form-select" id="info-input1" data-placeholder="City"  required>
                            <option class="form-option" value=""></option>
                            <option class="form-option" value="WIP">WIP</option>
                        </select>
                    </div>

                    <div class="w-100 d-flex flex-column justify-content-center align-items-start">
                        <label class="d-flex h-100 text-center align-items-center" for="info-input2">Terminal</label>
                        <select name="Terminal" class="p-0 m-0 form-select" id="info-input2" data-placeholder="Terminal" required>
                            <option class="form-option" value=""></option>
                            <option class="form-option" value="WIP">WIP</option>
                        </select>
                    </div>


                    <div class="d-flex w-100 flex-column flex-md-row justify-content-center align-items-center gap-4">
                        <div class="flex-fill w-100 d-flex flex-column justify-content-center align-items-start">
                            <label for="name-input">Name</label>
                            <input name="Name" type="text" class="form-control" id="name-input" autocomplete="given-name" minlength="2" maxlength="50" pattern="[A-Za-zÀ-ž' -]{2,50}" title="Use 2 to 50 letters." required>
                        </div>

                        <div class="flex-fill w-100 d-flex flex-column justify-content-center align-items-start">
                            <label for="surname-input">Surname</label>
                            <input name="Surname" type="text" class="form-control" id="surname-input" autocomplete="family-name" minlength="2" maxlength="50" pattern="[A-Za-zÀ-ž' -]{2,50}" title="Use 2 to 50 letters." required>
                        </div>
                    </div>
                    
                    <div class="w-100 d-flex flex-column justify-content-center align-items-start">
                        <label for="phone-input">Phone Number</label>
                        <input name="Phone Number" type="tel" class="form-control" id="phone-input" autocomplete="tel" inputmode="tel" minlength="7" maxlength="20" pattern="[+]?[0-9][0-9 ().-]{6,19}" title="Use a valid phone number, for example +371 20000000." required>
                    </div>
                </div>
            </div>

            <ManeuverButtons prev={() => onPrev('./cart')} nextType="submit">
            </ManeuverButtons>
        </form>
    </div>
</CheckoutLayout>
