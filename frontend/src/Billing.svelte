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
let BillingInfo = {};
let DeliveryInfo = {};

onMount(() => 
{
    divItems = document.querySelectorAll('#page-container div');
    item = divItems[2];
    item.classList.remove("opacity-75");

    BillingInfo = JSON.parse(sessionStorage.getItem("Billing")) ?? {};
    DeliveryInfo = JSON.parse(sessionStorage.getItem("Delivery")) ?? {};
    console.log(BillingInfo);
});

onDestroy(() => 
{
    item.classList.add("opacity-75");
});

function handleSubmit(e)
{
    e.preventDefault();
    console.log("aaa");
    onNext('./checkout');
}

</script>

<CheckoutLayout>
    <div class="w-100 flex-fill flex-column d-flex justify-content-start align-items-center"  slot="main">
        <form on:submit={handleSubmit} id="base-info" class="d-flex flex-column justify-content-start align-items-center w-75 h-50 gap-1">
            <div class="w-100 d-flex gap-4 justify-content-center align-items-center my-4">
                <div class="w-50 h-100 d-flex flex-column justify-content-start p-4 align-items-start rounded-2 border shadow-sm bg-light">
                    <h5 class="fw-bold">Billing Information</h5>
                    {#each Object.entries(BillingInfo) as [key, value]}

                        <div class="d-flex flex-column gap-1 justify-content-center align-items-start my-2">
                            <label class="d-flex h-100 text-center fw-bold">{key}:</label>
                            <span class="d-flex h-100 text-center">{value}</span>
                        </div>

                    {/each}
                    <a class="mt-auto link-opacity-100-hover link-offset-2 fw-bold" href="./billing">Edit</a>
                </div>

                <div class="w-50 h-100 d-flex flex-column justify-content-start p-4 align-items-start rounded-2 border shadow-sm bg-light">
                    <h5 class="fw-bold">Delivery Information</h5>
                    {#each Object.entries(DeliveryInfo) as [key, value]}

                        <div class="d-flex flex-column gap-1 justify-content-center align-items-start my-2">
                            <label class="d-flex h-100 text-center fw-bold">{key}:</label>
                            <span class="d-flex h-100 text-center">{value}</span>
                        </div>

                    {/each}
                    <a class="mt-auto link-opacity-100-hover link-offset-2 fw-bold" href="/.delivery">Edit</a>
                </div>
            </div>

            <div class="d-flex flex-column justify-content-center align-items-center rounded-2 border shadow-sm w-100 bg-light p-4 mb-4">
                <h5 class="fw-bold">Payment Method</h5>
                <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-4 border-bottom border-secondary pb-4">
                    <input name="Payment Method" class="input-checkbox fs-3 m-0 form-check-input" type="checkbox" value="Omniva Parcel" id="payment-method">
                    <label class="d-flex h-100 text-center align-items-center" for="payment-method">Electronic Payment</label>
                </div>
            </div>
            <ManeuverButtons prev={() => onPrev('delivery')} nextType="submit">
            </ManeuverButtons>
        </form>
    </div>           
</CheckoutLayout>