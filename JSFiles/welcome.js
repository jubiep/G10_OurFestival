document.addEventListener("DOMContentLoaded", function() {
    const countdown = document.getElementById("countdown");
    if (countdown) {
        let timeLeft = 10;

        const timer = setInterval(() => {
            timeLeft--;
            if (countdown) {
                countdown.textContent = timeLeft;
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = 'Homepage.html';
            }
        }, 1000);
    }
});