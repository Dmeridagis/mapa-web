const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

// Set the minimum and maximum allowed dates for the date of birth input
const fechaNacInput = document.querySelector("#fecha_nac");
if (fechaNacInput) {
  const today = new Date();
  const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate()).toISOString().split('T')[0];
  const minDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate()).toISOString().split('T')[0];
  
  fechaNacInput.setAttribute("max", maxDate);
  fechaNacInput.setAttribute("min", minDate);
}

// Add event listener to the domicilio select element
const domicilioSelect = document.querySelector("#domicilio");
if (domicilioSelect) {
  domicilioSelect.addEventListener("change", function() {
    if (this.value) {
      console.log("Selected domicilio:", this.value);
      // You can add more functionality here if needed
    }
  });
}

