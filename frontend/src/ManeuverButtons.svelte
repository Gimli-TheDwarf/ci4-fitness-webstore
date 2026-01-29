<script>
  export let prev;
  export let next;
  export let nextType;
</script>


<div class="w-100 d-flex justify-content-between align-items-center mb-4">
    <button type="button" id="maneuver-button-1" class="w-25 btn btn-secondary" on:click={prev}>
            <i class="bi bi-arrow-left fs-5 align-middle me-1"></i>
            <span class="align-middle">Previous</span>
    </button>
    <button type={nextType} id="maneuver-button-2" class="w-25 btn btn-dark" on:click={next}>
            <span class="align-middle">Next</span>
            <i class="bi bi-arrow-right fs-5 align-middle ms-1"></i>
    </button>
</div>