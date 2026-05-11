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

<div id="TagsContainer" class="homepage-filter-toolbar bg-white border shadow-sm rounded-2 p-3 mb-3 d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-3 w-100">

  <div class="d-flex align-items-center gap-2 text-blue-gray flex-shrink-0">
    <span class="admin-icon-box d-inline-flex justify-content-center align-items-center rounded-2 bg-orange text-white shadow-sm fs-5 lh-1">
      <i class="bi bi-funnel-fill"></i>
    </span>
    <div>
      <h3 class="h6 fw-semibold mb-0">Filter products</h3>
      <span class="small text-secondary">Choose one or more categories</span>
    </div>
  </div>

  <div class="flex-grow-1 min-w-0" id="tagsSelect2Container">
    <select multiple bind:this={Select2Element} class="form-select border w-100">
      {#each tagsInfo as tag}
        <option value={tag.name}>{tag.name}</option>
      {/each}
    </select>
  </div>

  <button on:click={Apply} class="btn btn-orange fw-semibold rounded-2 shadow-sm px-4 d-inline-flex align-items-center justify-content-center gap-2 hover-transform flex-shrink-0">
    <i class="bi bi-check2"></i><span>Apply</span>
  </button>
</div>
