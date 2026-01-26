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

function affirmSelection(target)
{
    let parentDiv = target.closest("#base-info")
    let inputs = [...parentDiv.querySelectorAll('input')].filter(inp => inp !== target);
    console.log("inputs:" , inputs)
    let isEnabled = target.checked;

    inputs.forEach(element => 
    {
        element.disabled = isEnabled;
    });
}

</script>

<CheckoutLayout>
    <div class="w-100 flex-fill d-flex justify-content-center align-items-start " slot="main">
        <div id="base-info" class="d-flex flex-column justify-content-start align-items-center w-75 h-50 gap-1">

            <div class="flex-column w-100 gap-2 pt-4 text-blacker">
                <h1 class="w-100 text-start">Delivery</h1>
                <h5 class="w-100 text-dark text-start">Choose a delivery point</h5>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-4 border-bottom border-secondary pb-4">
                <input class="fs-3 m-0 form-check-input" type="checkbox" id="checkbox-option1" on:click={(e) => affirmSelection(e.currentTarget)}>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option1">Delivery to an Omniva parcel locker: 2-3 days.</label>
                <span class="ms-auto">2.99 €</span>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 border-bottom border-secondary py-4">
                <input class="fs-3 m-0 form-check-input" type="checkbox" id="checkbox-option2" on:click={(e) => affirmSelection(e.currentTarget)}>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option2">Delivery to a DPD parcel locker: 2-3 days.</label>
                <span class="ms-auto">2.99 €</span>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 border-bottom border-secondary py-4">
                <input class="fs-3 m-0 form-check-input" type="checkbox" id="checkbox-option3" on:click={(e) => affirmSelection(e.currentTarget)}>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option3">Delivery to a SmartPosti parcel locker: 2-3 days.</label>
                <span class="ms-auto">4.00 €</span>
            </div>

            <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 border-bottom border-secondary py-4 mb-4">
                <input class="fs-3 m-0 form-check-input" type="checkbox" id="checkbox-option4" on:click={(e) => affirmSelection(e.currentTarget)}>
                <label class="d-flex h-100 text-center align-items-center" for="checkbox-option4">Home delivery with a DPD courier in 2-3 days.</label>
                <span class="ms-auto">5.59 €</span>
            </div>

            <div class="d-flex w-100 flex-column justify-content-start align-items-center gap-4 mt-4">
                <h5 class="w-100 text-dark text-start">Please confirm the delivery details</h5>

                <div class="d-flex w-100 flex-column justify-content-center align-items-start gap-3 mb-4">
                    <div class="w-100 d-flex flex-column justify-content-center align-items-start">
                        <label class="d-flex h-100 text-center align-items-center" for="info-input1">City</label>
                        <select name="City" class="m-0 form-select" id="info-input1" data-placeholder="City"  required>
                            <option class="form-option" value=""></option>
                            <option class="form-option" value="WIP">WIP</option>
                        </select>
                    </div>

                    <div class="w-100 d-flex flex-column justify-content-center align-items-start">
                        <label class="d-flex h-100 text-center align-items-center" for="info-input2">Terminal</label>
                        <select name="City" class="p-0 m-0 form-select" id="info-input2" data-placeholder="Terminal" required>
                            <option class="form-option" value=""></option>
                            <option class="form-option" value="WIP">WIP</option>
                        </select>
                    </div>


                    <div class="d-flex w-100 justify-content-center align-items-center gap-4"> 
                        <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                            <label for="name-input">Name</label>
                            <input type="text" class="form-control" id="name-input" required>
                        </div>

                        <div class="w-50 d-flex flex-column justify-content-center align-items-start">
                            <label for="surname-input">Surname</label>
                            <input type="text" class="form-control" id="surname-input" required>
                        </div>
                    </div>
                    
                    <div class="w-100 d-flex flex-column justify-content-center align-items-start">
                        <label for="phone-input">Phone Number</label>
                        <input type="tel" class="form-control" id="phone-input" required>
                    </div>
                </div>
            </div>

            <ManeuverButtons prev={() => onPrev('./cart')} next={() => onNext('./delivery')}>
            </ManeuverButtons>
        </div>
    </div>
</CheckoutLayout>   