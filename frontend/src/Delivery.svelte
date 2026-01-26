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

</script>

<CheckoutLayout>
    <div class="w-100 flex-fill d-flex justify-content-center align-items-start " slot="main">
        <ManeuverButtons prev={() => onPrev('./checkout')} next={() => onNext('---')}>
        </ManeuverButtons>
    </div>
</CheckoutLayout>