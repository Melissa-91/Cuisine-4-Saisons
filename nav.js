/* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
let menuOpen = false;

function burgerToCrossToBurger() { 
  if(menuOpen == false){
      $('#burg1').fadeOut(1, "", "");
      $('#burg2').fadeOut(1, "", "");
      $('#burg3').fadeOut(1, "", "");
      $('#cross').fadeIn(400, "", "");
      $('#navMobile').fadeIn(400, "", "");
      menuOpen = true;

  }
  else if (menuOpen == true){
      $('#burg1').fadeIn(400, "", "");
      $('#burg2').fadeIn(400, "", "");
      $('#burg3').fadeIn(400, "", "");
      $('#cross').fadeOut(1, "", "");
      $('#navMobile').fadeOut(400, "", "");
      menuOpen = false;

  }

}

document.querySelector("#menuOpen").addEventListener("click", burgerToCrossToBurger);
