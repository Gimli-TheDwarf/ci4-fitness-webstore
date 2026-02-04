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

<div id="TagsContainer" class="bg-light-subtle bg-gradient border shadow-sm p-2 p-md-3 d-flex flex-column flex-md-row justify-content-center align-items-stretch align-items-md-center gap-2">

  <div class="flex-grow-1 min-w-0 shadow-sm" id="tagsSelect2Container">
    <select multiple bind:this={Select2Element} class="form-select border w-100">
      {#each tagsInfo as tag}
        <option value={tag.name}>{tag.name}</option>
      {/each}
    </select>
  </div>

<button on:click={Apply} class="btn btn-sm btn-outline-secondary fw-semibold rounded-2 shadow-sm px-3 d-inline-flex align-items-center gap-2 hover-transform flex-shrink-0">
  <i class="bi bi-funnel-fill"></i><span>APPLY FILTERS</span>
</button>
</div>