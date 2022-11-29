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
// STANDARD MOBILE NAVBAR
let standardUserNav = document.querySelector('#mobile-nav .standard-user-toggle');
let arrowStandardNav = document.querySelector('#mobile-nav .mobile-list #standard-user-arrow');
let userStandardMobileLink = document.querySelectorAll('#mobile-nav .mobile-list .standard-mobile-link');

const toggleStandardMobile = (element) =>{
    if(element.nextElementSibling.classList.contains('closed-std-user-mobile')){
        arrowStandardNav.style.transform = "rotate(180deg)";
        userStandardMobileLink.forEach(element =>{
            element.classList.remove('closed-std-user-mobile');
        })
    }else if(!element.nextElementSibling.classList.contains('closed-std-user-mobile')){
        arrowStandardNav.style.transform = "rotate(0deg)";
        userStandardMobileLink.forEach(element =>{
        element.classList.add('closed-std-user-mobile');
        })
    }
}

// USER NAVBAR MOBILE
let userNavMobileBtn = document.querySelector('#mobile-nav .user-toggle');
let arrowUserMobile = document.querySelector('#mobile-nav .mobile-list #user-arrow')
let userMobileLink = document.querySelectorAll('#mobile-nav .mobile-list .user-mobile-link');

const toggleUserMobileNav = (element) =>{
    if(element.nextElementSibling.classList.contains('closed-user-mobile')){
        arrowUserMobile.style.transform = "rotate(180deg)";
        userMobileLink.forEach(element =>{
            element.classList.remove('closed-user-mobile');
        })
    }else if(!element.nextElementSibling.classList.contains('closed-user-mobile')){
        arrowUserMobile.style.transform = "rotate(0deg)";
        userMobileLink.forEach(element =>{
        element.classList.add('closed-user-mobile');
        })
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