const signUpButton=document.getElementById('signUpButton');
const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpForm=document.getElementById('signup');

signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})
signInButton.addEventListener('click', function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
})
// Google Login Initialization
window.onload = function () {
    google.accounts.id.initialize({
        client_id: "YOUR_GOOGLE_CLIENT_ID",
        callback: handleGoogleResponse
    });
    google.accounts.id.renderButton(
        document.querySelector('.g_id_signin'),
        { theme: "outline", size: "large" }
    );
    google.accounts.id.prompt(); // Automatically show the prompt
};

// Google Login Callback
function handleGoogleResponse(response) {
    const data = JSON.parse(atob(response.credential.split('.')[1]));
    console.log("Google User Info:", data);

    // Send data to the server for validation and login
    fetch("register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: data.email, name: data.name, source: "google" })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = "dashboard.php";
            } else {
                alert("Login failed. Please try again.");
            }
        });
}

// Facebook SDK Initialization
window.fbAsyncInit = function () {
    FB.init({
        appId: 'YOUR_FACEBOOK_APP_ID',
        cookie: true,
        xfbml: true,
        version: 'v15.0'
    });
};

// Facebook Login Function
function facebookLogin() {
    FB.login(function (response) {
        if (response.authResponse) {
            FB.api('/me', { fields: 'name,email' }, function (userInfo) {
                console.log("Facebook User Info:", userInfo);

                // Send data to the server for validation and login
                fetch("register.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email: userInfo.email, name: userInfo.name, source: "facebook" })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "dashboard.php";
                        } else {
                            alert("Login failed. Please try again.");
                        }
                    });
            });
        } else {
            alert("Facebook login failed.");
        }
    }, { scope: 'email' });
}
