// MOBILE NAVBAR
let icon = document.querySelectorAll(".fas");

const toggleMobileMenu = (element) => {
    if(element.nextElementSibling.classList.contains("open")){
        
        element.nextElementSibling.classList.remove("open");
        element.classList.remove("fa-times");
        element.classList.add("fa-bars");
    }else{
        element.classList.remove("fa-bars");
        element.classList.add("fa-times");
        element.nextElementSibling.classList.add("open");
    }
}
// USER NAVBAR
let userNav = document.querySelector(".user-menu .user-menu-icon");
let arrowUser = document.querySelector(".user-menu .user-menu-icon #lg-arrow-user");
let userNavMenu = document.querySelector(".user-menu .user-config");

    const toggleUserMenu = (element) => {
        if(element.nextElementSibling.classList.contains("close-user-menu")){
            userNavMenu.classList.remove("close-user-menu");
            userNavMenu.style.display = 'flex';
            arrowUser.style.transform = "rotate(180deg)";
        }else if (!element.nextElementSibling.classList.contains("close-user-menu")){
            userNavMenu.classList.add("close-user-menu");
            userNavMenu.style.display = 'none';
            arrowUser.style.transform = "rotate(0deg)";
        }
    }


// FOOTER
const currentYear = new Date().getFullYear();
let displayedYear = document.querySelector(".year").innerHTML = currentYear;