let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("demo");
  let captionText = document.getElementById("caption");
  let captionDesc = document.getElementById("caption-desc");
  let labels = document.getElementsByClassName("product-label");
  
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
    labels[i].className = labels[i].className.replace(" active-label", "");
  }
  
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  labels[slideIndex-1].className += " active-label";

  captionText.innerHTML = dots[slideIndex-1].alt;
  captionDesc.innerHTML = dots[slideIndex-1].getAttribute("data-description");

//rating-bar
  const sweetnessBar = document.getElementById('sweetness-rating');
  const bitternessBar = document.getElementById('bitterness-rating');
  
  const selectedDot = dots[slideIndex - 1];
  

  if (sweetnessBar && selectedDot.hasAttribute('data-sweetness')) {
      const sweetnessValue = parseInt(selectedDot.getAttribute('data-sweetness'));
      const maxValue = parseInt(sweetnessBar.getAttribute('data-max')) || 10;
      const sweetPercent = (sweetnessValue / maxValue) * 100;

      sweetnessBar.style.setProperty('--rating-width', sweetPercent + '%');
  }

  if (bitternessBar && selectedDot.hasAttribute('data-bitterness')) {
      const bitternessValue = parseInt(selectedDot.getAttribute('data-bitterness'));
      const maxValue = parseInt(bitternessBar.getAttribute('data-max')) || 10;
      const bitterPercent = (bitternessValue / maxValue) * 100;

      bitternessBar.style.setProperty('--rating-width', bitterPercent + '%');
  }
}