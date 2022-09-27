const closeCountryMenu = (element) => {
    if(element.nextElementSibling.classList.contains("close-lg-menu")){
        element.nextElementSibling.classList.remove("close-lg-menu");
        document.getElementById("lg-arrow").style.transform = "rotate(180deg)";
    } else{
        element.nextElementSibling.classList.add("close-lg-menu");
        document.getElementById("lg-arrow").style.transform = "rotate(0deg)";
    }
}