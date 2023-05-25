const body = document.querySelector('body'),
sidebar = body.querySelector('nav'),
toggle = body.querySelector(".toggle"),
modeSwitch = body.querySelector(".toggle-switch"),
modeText = body.querySelector(".mode-text");
toggle.addEventListener("click" , () =>{
sidebar.classList.toggle("close");
var sidebarValue = document.getElementById('sidebar');
sessionStorage.setItem("sidebarValue",sidebarValue.className);
})
modeSwitch.addEventListener("click" , () =>{
    body.classList.toggle("dark");
    if (body.classList.contains("dark")) {
        modeText.innerText = "Dark Mode";
    } else {
        modeText.innerText = "Light Mode";
    }
    var modeColor = document.getElementById('modeColor');
    sessionStorage.setItem("modeColor",modeColor.className);
});
if (modeSet == "dark") {
    document.querySelector('.mode-text').innerHTML = "Dark Mode";
} else if (modeSet == "") {
    document.querySelector('.mode-text').innerHTML = "Light Mode";
}