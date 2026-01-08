
let Toast;
let ToastEl = document.querySelector("#ToastInfoContainer .toast");

Toast = new bootstrap.Toast(ToastEl, {autohide: true, delay: 3000});

function notify(message)
{
    const messageBody = ToastEl.querySelector('.toast-body');
    messageBody.innerHTML = message;
    Toast.show();
}