// Add event listeners to buttons document.getElementById('About-us-btn).AddEventListener('click', function()
{
    toggleContent('About-us-content');

};
// function to toggleContent visibility function toggleContent(contentId){
    const content=document.getElementById(contentId);
    content.style.display=content.style.display === 'none' ? 'block' : 'none';
   }
function sendMessage() {
    var userInput = document.getElementById("user-input");
    var message = userInput.value.trim();
    
    if (message !== "") {
        displayMessage("user", message);
        userInput.value = "";
        
        // Here you can make a request to your backend to process the message
        // Example: fetch('/send-message', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //     },
        //     body: JSON.stringify({ message: message }),
        // });
    }
}

function displayMessage(sender, message) {
    var chatBox = document.getElementById("chat-box");
    var messageElement = document.createElement("div");
    messageElement.classList.add("message", sender);
    messageElement.innerText = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}
const express = require('express');
const app = express();
const bodyParser = require('body-parser');

app.use(bodyParser.json());

app.post('/send-message', (req, res) => {
    const message = req.body.message;
    // Here you can process the message (e.g., save to database, handle logic)
    console.log("Received message:", message);
    res.sendStatus(200);
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const imageSlides = document.querySelectorAll('.image-slide');
    let currentSlide = 0;
    let currentImageSlide = 0;

    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    function nextImageSlide() {
        imageSlides[currentImageSlide].classList.remove('active');
        currentImageSlide = (currentImageSlide + 1) % imageSlides.length;
        imageSlides[currentImageSlide].classList.add('active');
    }

    // Show the first slides initially
    slides[currentSlide].classList.add('active');
    imageSlides[currentImageSlide].classList.add('active');

    // Transition to the next slide every 5 seconds (5000 milliseconds)
    setInterval(nextSlide, 5000);
    setInterval(nextImageSlide, 5000);
});
prome-consultants/
 backend/ server.js
 package.json
 node_modules/
public/
    index.html
     styles.css
     script.js

