const player = new Plyr("#player");
const dismissAlert = document.querySelector("#dismiss-alert");
function dismiss() {
    if (!dismissAlert) return;
    setTimeout(() => {
        dismissAlert.remove();
    }, 1000);
}
dismiss();
