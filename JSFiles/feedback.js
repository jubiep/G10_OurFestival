// author: wirakorn thanabat
// feedback.js
// this script will fake db and receive input from form then fake push to db.

let mockDatabase = []; // fake db

function renderFeedback() {
    const list = document.getElementById("feedback-list");
    list.innerHTML = "";

    if (mockDatabase.length === 0) {
        list.innerHTML = "<p>No feedback yet...</p>";
        return;
    }

    // new first
    mockDatabase.slice().reverse().forEach((fb, index) => {
        const item = document.createElement("div");
        item.classList.add("feedback-item");
        item.innerHTML = `
            <p><strong>Gender:</strong> ${fb.gender}
            &nbsp;<strong>Age:</strong> ${fb.age} </p>
            <p><strong>Booth Ratings:</strong> 
                ${Object.entries(fb.ratings)
                    .map(([booth, rate]) => `${booth}: ${rate}`)
                    .join(", ")}</p>
            <p><strong>Favorite Booth:</strong> ${fb.favorite}</p>
            <p><strong>Comment:</strong> ${fb.comment || "—"}</p>
            <hr>
        `;
        list.appendChild(item);
    });
}

function handleSubmit(event) {
    event.preventDefault(); //

    const gender = document.getElementById("feedback-gender").value;
    const age = document.getElementById("feedback-age").value;
    const favorite = document.getElementById("feedback-favorite-booth").value;
    const comment = document.getElementById("feedback-additional-comment").value;

    const ratings = {}; // sum rating
    for (let i = 1; i <= 4; i++) {
        const checked = document.querySelector(
            `input[name="feedback-booth-${i}-rating"]:checked`
        );
        ratings[`Booth ${i}`] = checked ? checked.value : "-";
    }

    const feedback = {
        gender,
        age,
        ratings,
        favorite: favorite === "0" ? "None" : `Booth ${favorite}`,
        comment,
        timestamp: new Date().toISOString(),
    };

    mockDatabase.push(feedback); //fake db push <--------------------------------------------

    renderFeedback(); //refresh

    event.target.reset(); //clear
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    form.addEventListener("submit", handleSubmit); // bind listener to form
    renderFeedback(); //load feedback
});