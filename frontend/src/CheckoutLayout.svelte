<div class="shadow-lg d-flex m-4 overflow-auto w-50 bg-weird-white rounded-1 flex-column justify-content-start align-items-center">
    <div id="page-container" class="p-4 d-flex flex-row gap-5 justify-content-center align-items-center w-100">

        <div class="opacity-75 p-3 px-4 border rounded-4 bg-body text-center d-flex flex-column align-items-center gap-2 shadow-sm">
            <i class="fs-2 bi bi-1-square"></i>
            <p class="mb-0 fw-semibold">Delivery</p>
            <small class="text-body-secondary">Choose shipping options</small>
        </div>

        <div class="opacity-75 p-3 px-4 border rounded-4 bg-body text-center d-flex flex-column align-items-center gap-2 shadow-sm">
            <i class="fs-2 bi bi-2-square"></i>
            <p class="mb-0 fw-semibold">Billing</p>
            <small class="text-body-secondary">Provide billing information</small>
        </div>

        <div class="opacity-75 p-3 px-4 border rounded-4 bg-body text-center d-flex flex-column align-items-center gap-2 shadow-sm">
            <i class="fs-2 bi bi-3-square"></i>
            <p class="mb-0 fw-semibold">Confirmation</p>
            <small class="text-body-secondary">Confirm order information</small>
        </div>
    </div>
    
    <div class="w-100 flex-fill bg-success-subtle d-flex flex-column justify-content-center align-items-center p-2">
        <slot name="main" />
    </div>
</div>