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

function getSessionInfo(name)
{
    try
    {
        return JSON.parse(sessionStorage.getItem(name)) ?? {};
    }
    catch
    {
        return {};
    }
}

function hasDeliverySelection()
{
    return Boolean(DeliveryInfo["Delivery Type"]);
}

onMount(() => 
{
    divItems = document.querySelectorAll('#page-container div');
    item = divItems[2];
    item.classList.remove("opacity-75");

    BillingInfo = getSessionInfo("Billing");
    DeliveryInfo = getSessionInfo("Delivery");

    if (!hasDeliverySelection())
    {
        onNext('./checkout');
    }

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
        <form on:submit={handleSubmit} id="base-info" class="d-flex flex-column justify-content-start align-items-center w-100 gap-1">
            <div class="w-100 d-flex flex-column flex-lg-row gap-4 justify-content-center align-items-stretch my-4">
                <div class="w-100 h-100 d-flex flex-column justify-content-start p-4 align-items-start rounded-2 border shadow-sm bg-white">
                    <h5 class="fw-bold">Billing Information</h5>
                    {#each Object.entries(BillingInfo) as [key, value]}

                        <div class="d-flex flex-column gap-1 justify-content-center align-items-start my-2">
                            <label class="d-flex h-100 text-center fw-bold">{key}:</label>
                            <span class="d-flex h-100 text-center">{value}</span>
                        </div>

                    {/each}
                    <a class="mt-auto link-opacity-100-hover link-offset-2 fw-bold" href="./billing">Edit</a>
                </div>

                <div class="w-100 h-100 d-flex flex-column justify-content-start p-4 align-items-start rounded-2 border shadow-sm bg-white">
                    <h5 class="fw-bold">Delivery Information</h5>
                    {#each Object.entries(DeliveryInfo) as [key, value]}

                        <div class="d-flex flex-column gap-1 justify-content-center align-items-start my-2">
                            <label class="d-flex h-100 text-center fw-bold">{key}:</label>
                            <span class="d-flex h-100 text-center">{value}</span>
                        </div>

                    {/each}
                    <a class="mt-auto link-opacity-100-hover link-offset-2 fw-bold" href="./checkout">Edit</a>
                </div>
            </div>

            <div class="d-flex flex-column justify-content-center align-items-center rounded-2 border shadow-sm w-100 bg-white p-4 mb-4">
                <h5 class="fw-bold">Payment Method</h5>
                <div class="d-flex w-100 flex-row justify-content-start align-items-center gap-2 mt-4 border rounded-2 p-3 bg-light">
                    <input name="Payment Method" class="input-checkbox fs-3 m-0 form-check-input" type="checkbox" value="Omniva Parcel" id="payment-method">
                    <label class="d-flex h-100 text-center align-items-center" for="payment-method">Electronic Payment</label>
                </div>
            </div>
            <ManeuverButtons prev={() => onPrev('delivery')} nextType="submit">
            </ManeuverButtons>
        </form>
    </div>           
</CheckoutLayout>
