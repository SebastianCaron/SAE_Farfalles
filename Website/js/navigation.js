const current = window.location.href;

function goTo(url, new_page = false){
    if(new_page){
        window.open(url, "_blank");
    }else{
        window.location.href = url;
    }

}


var menu_bt = document.getElementById("menu_bt");
var menu_bt_close = document.getElementById("menu_bt_close");

function showNavigationMenu(){
    var navigation = document.getElementsByClassName("navigation")[0];
    navigation.classList.toggle("active");

    menu_bt.classList.toggle("anim");
    menu_bt_close.classList.toggle("anim");
}
