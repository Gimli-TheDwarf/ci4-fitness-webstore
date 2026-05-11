<div class="container-lg px-0">
<div class="shadow-sm d-flex overflow-hidden w-100 bg-light rounded-2 border flex-column justify-content-start align-items-center">
    <div id="page-container" class="p-3 p-md-4 d-flex flex-column flex-md-row gap-3 justify-content-center align-items-stretch w-100 bg-white border-bottom">

        <div class="opacity-75 p-3 px-4 border rounded-2 bg-body text-center d-flex flex-column align-items-center gap-2 shadow-sm flex-fill">
            <i class="fs-2 bi bi-1-square"></i>
            <p class="mb-0 fw-semibold">Delivery</p>
            <small class="text-body-secondary">Choose shipping options</small>
        </div>

        <div class="opacity-75 p-3 px-4 border rounded-2 bg-body text-center d-flex flex-column align-items-center gap-2 shadow-sm flex-fill">
            <i class="fs-2 bi bi-2-square"></i>
            <p class="mb-0 fw-semibold">Billing</p>
            <small class="text-body-secondary">Provide billing information</small>
        </div>

        <div class="opacity-75 p-3 px-4 border rounded-2 bg-body text-center d-flex flex-column align-items-center gap-2 shadow-sm flex-fill">
            <i class="fs-2 bi bi-3-square"></i>
            <p class="mb-0 fw-semibold">Confirmation</p>
            <small class="text-body-secondary">Confirm order information</small>
        </div>
    </div>
    
    <div class="w-100 flex-fill bg-light d-flex flex-column justify-content-center align-items-center p-3 p-md-4">
        <slot name="main" />
    </div>
</div>
</div>
