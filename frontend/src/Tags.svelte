<script>
    export let tagsInfo = [];
    export let TagsAreSelected;
    import { onMount, onDestroy } from 'svelte';
    import 'select2';

    let Select2Element;

    onMount(() => 
    {
        console.log("dom loaded, mounting stuff rn");
        jQuery(Select2Element).select2(
        {
            placeholder: "Select a filter option",
            width: '100%'
        });
    }); 

    onDestroy(() => 
    {
        console.log("destroying stuff, remove select2")
        jQuery(Select2Element).select2('destroy');
    });

    function Apply()
    {
        const value = jQuery(Select2Element).val() || [];
        console.log(value);
        TagsAreSelected(value);
    }

</script>

<div id="TagsContainer" class="d-flex justify-content-center align-items-center">
    <div class="m-2 shadow w-75" id="tagsSelect2Container">
        <select multiple bind:this={Select2Element}>
            {#each tagsInfo as tag}
                <option value={tag.name}>{tag.name}</option>
            {/each}
        </select>
    </div>
    <button on:click={Apply} class="m-2 shadow rounded h-100 shadow-0 border-0 btn btn-success">Apply Filters</button>
</div>

<style>

</style>