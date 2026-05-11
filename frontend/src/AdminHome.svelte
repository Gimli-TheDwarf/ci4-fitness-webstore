<script>
    export let locationSelection = () => {};

    function ChangeSelection(selected, mode = null)
    {
        locationSelection(selected, mode ?? '');
        console.log("INSIDE ADMINHOME", selected, " ", mode);
    }
</script>

<div id="adminNavigation" class="w-100">
    <div class="homepage-filter-toolbar bg-white border shadow-sm rounded-2 p-3 mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 w-100">
        <div class="d-flex align-items-center gap-2 text-blue-gray">
            <span class="admin-icon-box d-inline-flex justify-content-center align-items-center rounded-2 bg-orange text-white shadow-sm fs-5">
                <i class="fa-solid fa-gauge-high"></i>
            </span>
            <div>
                <h1 class="h5 fw-semibold mb-0">Administrator Panel</h1>
                <span class="small text-secondary">Manage catalog data with the same structure customers see.</span>
            </div>
        </div>
    </div>

    <div class="row g-3 overflow-y-auto no-scrollbar">

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button on:click|preventDefault={() => ChangeSelection('tags')} class="btn btn-light overflow-hidden w-100 p-0 input-group d-flex flex-column border rounded-2 shadow-sm">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-blue-gray m-0 p-3 text-start fw-semibold">Create Products & Configure Tags</h5>
                    <i class="w-25 border-start h-100 d-flex justify-content-center align-items-center p-2 text-orange fs-2 fa-solid fa-tags"></i>
                </div>
                <span class="p-2 text-secondary bg-transparent border-top small">Add new products and configure existing tags</span>
            </button>
        </div>

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button on:click|preventDefault={() => ChangeSelection('products')} class="btn btn-light overflow-hidden w-100 p-0 input-group d-flex flex-column border rounded-2 shadow-sm">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-blue-gray m-0 p-3 text-start fw-semibold">Manage & Configure Products</h5>
                    <i class="w-25 border-start h-100 d-flex justify-content-center align-items-center p-2 text-orange fs-2 fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="p-2 text-secondary bg-transparent border-top small">Configure and manage existing products on the market</span>
            </button>
        </div>

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button disabled on:click|preventDefault={() => ChangeSelection('sales')} class="opacity-50 overflow-hidden w-100 p-0 input-group d-flex flex-column border rounded-2 shadow-sm bg-white">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-blue-gray m-0 p-3 text-start fw-semibold">Sales Info Menu</h5>
                    <i class="w-25 border-start h-100 d-flex justify-content-center align-items-center p-2 text-orange fs-2 fa-solid fa-scale-unbalanced-flip"></i>
                </div>
                <span class="p-2 text-secondary bg-light border-top small">Manage and view current sales and market information</span>
            </button>
        </div>

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button on:click|preventDefault={() => ChangeSelection('users')} class="btn btn-light overflow-hidden w-100 p-0 input-group d-flex flex-column border rounded-2 shadow-sm">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-blue-gray m-0 p-3 text-start fw-semibold">Users Configuration Menu</h5>
                    <i class="w-25 border-start h-100 d-flex justify-content-center align-items-center p-2 text-orange fs-2 fa-solid fa-users"></i>
                </div>
                <span class="p-2 text-secondary bg-transparent border-top small">Configure and manage existing users and their permissions</span>
            </button>
        </div>

    </div>
</div>
