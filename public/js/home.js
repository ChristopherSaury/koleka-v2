// BOUTTON SCROLL UP
let calcScrollValue = () =>{
    // scrollProgress est la variable qui va nous permettre d'accéder à progress qui est la div container principal de notre bouton
      let scrollProgress = document.getElementById("progress");
      // je vais stocker dans la variable pos ma position sur la page par 
      //rapport au nombre de pixels sur lesquels nous avons défilé verticalement depuis le haut de page
      let pos = document.documentElement.scrollTop;
      // Dans la variable calcHeight je vais stocker le résultat du calcul qui représente la hauteur de ma page
      // je vais pouvoir me situer sur la page en prenant en compte la partie de ma page qui n'est plus visible à cause du scroll
      let calcHeight = 
      // par la propriété scrollHeight je vais avoir la hauteur total de ma page
      document.documentElement.scrollHeight -
      // a la hauteur totale de ma page je vais soustraire la partie (la hauteur) qui est visible pour déterminer ma position sur la page
      document.documentElement.clientHeight;
      // Dans la variable scrollValue la valeur que je vais obtenir va représenter le pourcentage de progression sur ma page en partant de 0 tout en haut à 100 tout en bas
      let scrollValue = Math.round((pos * 100) / calcHeight)
      if(pos > 100){
          scrollProgress.style.display = "grid";
      }else{
          scrollProgress.style.display = "none";
      }
      scrollProgress.addEventListener("click", () =>{
          document.documentElement.scrollTop = 0;
      });
      scrollProgress.style.background = `conic-gradient(#e4c252 ${scrollValue}% , #d7d7d7 ${scrollValue}%)` ;
  }
  
  window.onscroll = calcScrollValue;
  window.onload = calcScrollValue;
  
  window.addEventListener("scroll", reveal);
  // TEXT REVEAL
  function reveal() {
    let reveals = document.querySelectorAll(".reveal");
    for (let i = 0; i < reveals.length; i++) {
      let windowHeight = window.innerHeight;
      let revealTop = reveals[i].getBoundingClientRect().top;
      let revealPoint = 150;
  
      if (revealTop < windowHeight - revealPoint) {
        reveals[i].classList.add("active");
      } else {
        reveals[i].classList.remove("active");
      }
    }
  }