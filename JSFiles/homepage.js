document.addEventListener("DOMContentLoaded", function() {
  const numbers = document.querySelectorAll(".numbers span");
  const mainImage = document.getElementById("mainImage");

  numbers.forEach(num => {
    num.addEventListener("mouseenter", () => {
      const newImg = num.getAttribute("data-img");
      mainImage.src = newImg;
      mainImage.classList.add("pop");
    });

    num.addEventListener("mouseleave", () => {
      mainImage.classList.remove("pop");
    });
  });
});
