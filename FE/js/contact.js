document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the default form s

        // Get form values
        const name = form.elements["name"].value;
        const email = form.elements["email"].value;
        const subject = form.elements["subject"].value;
        const message = form.elements["message"].value;

        // Simple validation
        if (name && email && subject && message) {
            // Create an object to hold the form data
            const formData = {
                name: name,
                email: email,
                subject: subject,
                message: message
            };

            // Log the form data to the console
            console.log("Form submitted:", formData);

            // Clear the form fields
            form.reset();

            // Show a success message
            alert("Thank you for your message! We will get back to you soon.");
        } else {
            // Show an error message if validation fails
            alert("Please fill in all fields before submitting the form.");
        }
    });
});