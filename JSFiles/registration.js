document.getElementById("cafeForm").addEventListener("submit", function(e){
    e.preventDefault();

    //Email pattern validation
    const email = document.getElementById("email").value;
    const emailpattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailpattern.test(email)) {
        alert("Invalid Email Pattern!");
        return;
    }

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
    
    // put to PHP
    fetch("PHPFiles/save_registration.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        document.getElementById('cafeForm').reset();
        alert(result.message);
    })
    .catch(error => {
        console.log('error' + error);
        alert('Failed to save the data.');
    });
});