<script>
    export let locationSelection = () => {};

    function ChangeSelection(selected, mode = null)
    {
        locationSelection(selected, mode ?? '');
        console.log("INSIDE ADMINHOME", selected, " ", mode);
    }
</script>

<div id="adminNavigation" class="container-fluid">
    <div class="row g-3 overflow-y-auto no-scrollbar m-1">

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button on:click|preventDefault={() => ChangeSelection('tags')} class="overflow-hidden w-100 p-0 input-group d-flex flex-column border-0 rounded gradient-green">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-light m-0 p-0">Create Products & Configure Tags</h5>
                    <i class="w-25 border-start border-2 h-100 d-flex justify-content-center align-items-center p-2 text-light fs-2 fa-solid fa-tags"></i>
                </div>
                <span class="p-2 text-light bg-transparent border-top border-2">Add new products and configure existing tags</span>
            </button>
        </div>

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button on:click|preventDefault={() => ChangeSelection('products')} class="overflow-hidden w-100 p-0 input-group d-flex flex-column border-0 rounded gradient-black">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-light m-0 p-0">Manage & Configure Products </h5>
                    <i class="w-25 border-start border-2 h-100 d-flex justify-content-center align-items-center p-2 text-light fs-2 fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="p-2 text-light bg-transparent border-top border-2">Configure and manage existing products on the market</span>
            </button>
        </div>

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button disabled on:click|preventDefault={() => ChangeSelection('sales')} class="opacity-25 overflow-hidden w-100 p-0 input-group d-flex flex-column border-0 rounded gradient-pink">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-light m-0 p-0">Sales Info Menu</h5>
                    <i class="w-25 border-start border-2 h-100 d-flex justify-content-center align-items-center p-2 text-light fs-2 fa-solid fa-scale-unbalanced-flip"></i>
                </div>
                <span class="p-2 text-light bg-transparent border-top border-2">Manage and view current sales and market information</span>
            </button>
        </div>

        <div class="col-sm-6 col-lg-3 d-flex" style="min-height: 15vh">
            <button on:click|preventDefault={() => ChangeSelection('users')} class="overflow-hidden w-100 p-0 input-group d-flex flex-column border-0 rounded gradient-peach">
                <div class="w-100 flex-grow-1 d-flex justify-content-between align-items-center">
                    <h5 class="w-75 text-light m-0 p-0">Users Configuration <br> Menu</h5>
                    <i class="w-25 border-start border-2 h-100 d-flex justify-content-center align-items-center p-2 text-light fs-2 fa-solid fa-users"></i>
                </div>
                <span class="p-2 text-light bg-transparent border-top border-2">Configure and manage existing users and their permissions</span>
            </button>
        </div>

    </div>

    <div class="w-100 h-25">
        <div class="bg-light">
            
        </div>
    </div>
</div>