export function showProductPreviewModal(modalElement, item, triggerElement)
{
    if(!modalElement || !item || !triggerElement)
    {
        return;
    }

    modalElement.querySelector(".modal-title").textContent = item.name ?? "Preview";
    modalElement.querySelector(".modalImage").src = triggerElement.src;
    modalElement.querySelector(".modalImage").alt = item.name ?? "Product preview";
    modalElement.querySelector("#modalText").textContent = item.description ?? "";

    new bootstrap.Modal(modalElement).show();
}
