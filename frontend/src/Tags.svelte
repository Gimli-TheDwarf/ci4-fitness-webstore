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
<div id="TagsContainer" class="bg-white bg-gradient border border-success-subtle shadow-sm p-2 d-flex flex-column flex-md-row justify-content-center align-items-stretch gap-2">

  <div class="flex-grow-1 min-w-0 border border-success-subtle rounded-3 bg-light shadow-sm overflow-hidden" id="tagsSelect2Container">
    <div class="d-flex justify-content-between align-items-center px-2 py-1 bg-success-subtle border-bottom">
      <span class="small fw-semibold text-dark d-inline-flex align-items-center gap-2"><i class="bi bi-filter text-success"></i><span>Filters</span></span>
      <span class="small text-muted d-none d-sm-inline-flex align-items-center gap-2"><i class="bi bi-info-circle text-success"></i><span>Select one or more</span></span>
    </div>
    <div class="p-2">
      <select multiple bind:this={Select2Element} class="form-select form-select-sm border-0 w-100 bg-white shadow-none">
        {#each tagsInfo as tag}
          <option value={tag.name}>{tag.name}</option>
        {/each}
      </select>
    </div>
  </div>

  <div class="flex-shrink-0 d-flex align-self-stretch">
    <button on:click={Apply} class="btn btn-success btn-sm bg-gradient fw-semibold shadow-sm px-3 d-inline-flex align-items-center justify-content-center gap-2 rounded-3 h-100 w-100">
      <i class="bi bi-funnel-fill"></i><span>APPLY</span><i class="bi bi-arrow-right-short fs-4"></i>
    </button>
  </div>

</div>