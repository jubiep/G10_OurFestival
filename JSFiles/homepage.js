    const numbers = document.querySelectorAll('.numbers span');
    const mainImage = document.getElementById('mainImage');

    numbers.forEach(num => {
      num.addEventListener('mouseenter', () => {
        const newImg = num.getAttribute('data-img');
        mainImage.src = newImg;
      });
    });