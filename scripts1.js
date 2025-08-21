document.getElementById('signinForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const FirstName = document.getElementById('FirstName').value;
    const LastName = document.getElementById('LastName').value;
    const Password = document.getElementById('password').value;

    
    fetch('registration.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ FirstName, LastName, Password })
    })
    
    .then(response => response.json())
.then(data => {
    alert(data.message);
    if (data.message === 'User registered successfully') {
        window.location.href = 'forms.html';
    }
})
.catch(error => console.error('Error:', error));

const signupButton=document.getElementById(signupButton);
const signInButton=document.getElementById(SigninButton);
signupButton.addEventListener('click',function(){
    signinForm.style.display="none";
    signupForm.style.display="block";
})
signInButton.addEventListener('click',function(){
    signinForm.style.display="block";
    signupForm.style.display="none";
})

