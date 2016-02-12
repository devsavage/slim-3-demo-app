function reload(time) {
    setTimeout(function() {
        window.location.reload();
    }, time);
}
// Don't use this.
function redirectToUserList() {
    window.location = "http://127.0.0.1/demoapp/public/admin/users";
}

$(function() {
    console.log("Loaded App -> Ready");
});
