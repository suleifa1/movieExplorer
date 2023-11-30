
document.addEventListener("DOMContentLoaded", function() {
  const toggleButton = document.getElementById("toggle-sidebar");
  const toggleButtonNav = document.getElementById("nav-toggle-sidebar");
  const sidebar = document.querySelector(".sidebar");

 
  if(toggleButton ){
    const isSidebarHidden = localStorage.getItem("sidebarHidden");
    if (isSidebarHidden === "true") {
      sidebar.classList.toggle("hidden");
      toggleButton.classList.add("uil-align"); 
    } else {
      toggleButton.classList.add("uil-left-indent"); 
    }
    toggleButton.addEventListener("click", function() {
      toggleButton.classList.toggle("uil-left-indent");
      toggleButton.classList.toggle("uil-align");
      sidebar.classList.toggle("hidden");
      localStorage.setItem("sidebarHidden", sidebar.classList.contains("hidden"));
  
    
    });

  }

  if(toggleButtonNav){
    toggleButtonNav.addEventListener("click", function(){
      sidebar.classList.toggle("hidden");
      localStorage.setItem("sidebarHidden", sidebar.classList.contains("hidden"));
    });
  }

  
  
  

  

  
  const notify = document.querySelector('.notify');
  if(notify){
    notify.addEventListener('click',  function() {
      const notifyBox = this.querySelector('.notifyBox');
      if (getComputedStyle(notifyBox).display === 'none') {    
          notifyBox.style.display = 'block';
      } else {
          notifyBox.style.display = 'none';
      }
    });
  }
  
  var a_buttons = document.querySelectorAll(".dropdown-content a");
  if(a_buttons){
    a_buttons.forEach(function(link) {
      link.addEventListener("click", function() {
        var dropdown = this.closest(".dropdown-content");
        dropdown.classList.remove("show");
        var button = this.closest(".dropdown").querySelector("button");
        button.textContent = this.textContent;
      });
    
    
    });
  }
  
  
  
  var scroll = document.querySelector(".movies_container");
  if(scroll){
    var contentButtons = document.querySelector(".content-buttons");
    var childContentButton1 = document.querySelector(".dropbtn");
    var previousScrollTop = 0;
    var previousTime = null;
    const vh10 = window.innerHeight*0.1;
    const tolerance = 2;
    scroll.addEventListener("scroll", function() {
      var currentTime = Date.now();
      var timeDiff = previousTime ? currentTime - previousTime : 0;
      var scrollDiff = this.scrollTop - previousScrollTop;
      var speed = scrollDiff / timeDiff;
      if (Math.abs(this.scrollTop - vh10) <= tolerance) {
        contentButtons.classList.add("hidden");
        childContentButton1.classList.add("hidden");
      }
    
      if (speed > 0 && this.scrollTop > vh10) {
        if (Math.abs(speed) > 0.5) {
          contentButtons.classList.add("hidden");
          childContentButton1.classList.add("hidden");
        }
      } else {
        if (Math.abs(speed) > 0.5) {
          contentButtons.classList.remove("hidden");
          childContentButton1.classList.remove("hidden");
        }
      }
      if(Math.abs(this.scrollTop - 0) <= tolerance){
        contentButtons.classList.remove("hidden");
        childContentButton1.classList.remove("hidden");
      }
    
      previousTime = currentTime;
      previousScrollTop = this.scrollTop;
    });

  }
  
  
  
  
  
  
  
  document.addEventListener("click", function(event) {
    if (!event.target.matches(".dropbtn")) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        openDropdown.classList.toggle("show");
      }
    }
  });
  
  var sortDropdownButton = document.getElementById("sortDropdownButton");
  if(sortDropdownButton){
    sortDropdownButton.addEventListener("click", function() {
      var dropdown = document.getElementById("sortDropdown");
      dropdown.classList.toggle("show");
    });
  }
  
  


  const add_movie_a = document.querySelector(".add-movie_a");
  if(add_movie_a){
    add_movie_a.addEventListener("click", async function(){
      const htmladdmovie = await getAddForm();
      data = this.dataset.value;
      updateURL(data);
      document.getElementById("moviesContainer").innerHTML  = htmladdmovie;
      if(window.innerWidth <= 850){
        contentButtons.classList.add("hidden");
      }
    })
  }
  
  
  const profile_a = document.querySelector(".profile_a");
  const logout_a = document.querySelector(".logout_a");
  reportWindowSize();
  function reportWindowSize() {
    if(window.innerWidth > 850){
      if(add_movie_a){
        add_movie_a.textContent = "Add movie";
        add_movie_a.classList.remove("uil-import");
      }
      if(profile_a){
        profile_a.textContent = "Profile";
        profile_a.classList.remove("uil-user-square");
      }
      if(logout_a){
        logout_a.textContent = "Logout";
        logout_a.classList.remove("uil-signout");
      }
      
    }
    else{
      if(add_movie_a){
        add_movie_a.textContent = "";
        add_movie_a.classList.add("uil-import");
      }
      if(profile_a){
        profile_a.textContent = "";
        profile_a.classList.add("uil-user-square");
      }
      if(logout_a){
        logout_a.textContent = "";
        logout_a.classList.add("uil-signout");
      }

      
  
    }
  }
  window.onresize = reportWindowSize;


  
});





