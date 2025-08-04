document.addEventListener("DOMContentLoaded", function () {
    const joinBtn = document.getElementById("join-btn");
    const modal = document.getElementById("join-modal");
    const closeBtn = document.querySelector(".close-btn");
    const joinForm = document.getElementById("join-form");
  
    // Open modal
    joinBtn.addEventListener("click", function() {
      modal.style.display = "block";
      document.body.style.overflow = "hidden"; // Prevent scrolling
    });
  
    // Close modal
    closeBtn.addEventListener("click", function() {
      modal.style.display = "none";
      document.body.style.overflow = "auto";
    });
  
    // Close when clicking outside modal
    window.addEventListener("click", function(e) {
      if (e.target === modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
      }
    });
  
  });