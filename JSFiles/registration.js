document.getElementById("CafeForm").addEventListener("submit", function(e){
    e.preventDefault();

    let data = {
        firstname: document.getElementById("firstname").value,
        lastname: document.getElementById("lastname").value,
        email: document.getElementById("email").value,
        phone: document.getElementById("phone").value,
        usertype: document.getElementById("usertype").value,
        interest: [
            document.getElementById("news").checked ? "ข่าวสาร" : null,
            document.getElementById("event").checked ? "กิจกรรม" : null
        ].filter(Boolean),
        message: document.getElementById("message").value
    };

    fetch("PHPFiles/save_registration.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
});