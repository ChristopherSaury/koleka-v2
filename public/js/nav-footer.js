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

// FOOTER
const currentYear = new Date().getFullYear();
let displayedYear = document.querySelector(".year").innerHTML = currentYear;