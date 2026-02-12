<script>
    export let itemsList = [];
    export let SelectedItems;

    $: displayedItems = searchText ? itemsList.filter(item => item.name.toLowerCase().includes(searchText.toLowerCase())) : []; 
    // if searchText filter items that have matching letters with the searched text, otherwise return empty array

    let searchText = "";


    function activateSearch()
    {
        SelectedItems(displayedItems);
        displayedItems = [];
        searchText = "";
    }
</script>

<div id="searchBarWrapper" class="d-flex flex-column w-100 position-relative">

    <form on:submit|preventDefault={() => activateSearch()} class="m-0 w-100 d-flex flex-row justify-content-center">
        <input id="searchBarInput" class:rounded-start={displayedItems.length === 0} bind:value={searchText} type="search" class="rounded-top rounded-0 rounded-end-0 no-focus-outline border-0 p-2" style="width: 90%;" placeholder="Search...">
        <button type="button" on:click={() => activateSearch()} class:rounded-bottom={displayedItems.length === 0} class="rounded-0 rounded-top rounded-start-0 btn border-0 btn-warning" style="width: 10%;">
            <i class="text-light text-shadow fa fa-search"></i>
        </button>
    </form>

    <div id="searchMenuList" class="position-absolute w-100 top-100 overflow-y-auto no-scrollbar rounded-0 rounded-bottom" style="max-height: 8rem; z-index: 2000;">
        {#each displayedItems as item}
            <button on:click={() => searchText = item.name} type="button" class="text-start top-100 btn btn-light w-100 rounded-0">{item.name}</button>
        {/each}
    </div>
</div>  