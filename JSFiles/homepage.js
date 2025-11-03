document.addEventListener("DOMContentLoaded", function() {
  const numbers = document.querySelectorAll(".numbers span");
  const mainImage = document.getElementById("mainImage");

  console.log("numbers found:", numbers.length);
  console.log("mainImage found:", !!mainImage);

  numbers.forEach(num => {
    num.addEventListener("mouseenter", () => {
      const newImg = num.getAttribute("data-img");
      console.log("Hover on:", num.textContent, "->", newImg);
      mainImage.src = newImg;
      mainImage.classList.add("pop");
    });

    num.addEventListener("mouseleave", () => {
      mainImage.classList.remove("pop");
    });
  });
});
