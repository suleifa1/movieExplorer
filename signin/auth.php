<?php 
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Проверка наличия значения в сессии, указывающего на то, что пользователь уже вошел
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    // Перенаправляем пользователя на главную страницу
    header("Location: /");
    exit;  // Важно вызвать функцию exit после header для завершения выполнения скрипта
}

// Оставшийся код страницы входа
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Sliding Login Form</title>
        <link rel="stylesheet" type="text/css" href="../css/signin.css">
        <link rel="stylesheet" type="text/css" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    </head>
    <body>
        <div class="container" id="main">
            <div class="sign-up">
                <form action="" method="post">
                    <h1>Create Account</h1>
                    <div class="social-container ">
                        <a href="#" class="social"><i class="uil uil-facebook"></i></a>
                        <a href="#" class="social"><i class="uil uil-google"></i></a>
                        <a href="#" class="social"><i class="uil uil-user-square"></i></a>
                    </div>
                    <p>or use your email for registration</p>
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button>Sign Up</button>
                </form>
            </div>
            <div class="sign-in">
                <form action="" method="post">
                    <h1>Sign In</h1>
                    <div class="social-container">
                        <a href="#" class="social"><i class="uil uil-facebook"></i></a>
                        <a href="#" class="social"><i class="uil uil-google"></i></a>
                        <a href="#" class="social"><i class="uil uil-user-square"></i></a>
                    </div>
                    <p>or use your account</p>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <a href="#">Forger yout Password?</a>
                    <button>Sign In</button>
                </form>
            </div>
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-left">
                        <h1>Wellcome Back!</h1>
                        <p>To keep connected with us please login with your personal info</p>
                        <button id="signIn">Sign In</button>
                    </div>
                    <div class="overlay-right">
                        <h1>Hello, Friend</h1>
                        <p>Enter your personal details and start journey with us</p>
                        <button id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const registrationForm = document.querySelector(".sign-up form");
                const authenticationForm = document.querySelector(".sign-in form");

                registrationForm.addEventListener("submit", function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);
                    const formDataObj = Object.fromEntries(formData.entries());
                    const jsonData = JSON.stringify(formDataObj);

                    fetch("/api/registration", {  // обновите URL здесь
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: jsonData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert((data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
                });

                authenticationForm.addEventListener("submit", function(event) {
                    event.preventDefault();

                    const formData = new FormData(this);
                    const formDataObj = Object.fromEntries(formData.entries());
                    const jsonData = JSON.stringify(formDataObj);

                    fetch("/api/auth", { // обновите URL здесь
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: jsonData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert((data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
                });


                const signUpButton = document.getElementById('signUp');
                const signInButton = document.getElementById('signIn');
                const main = document.getElementById('main');

                signUpButton.addEventListener('click', () =>{
                    main.classList.add("right-panel-active");
                });
                signInButton.addEventListener('click', () =>{
                    main.classList.remove("right-panel-active");
                });
                })

            
            
        </script>
    </body>
</html>