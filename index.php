<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: portal.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";


 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Ingrese su correo institucional.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Ingrese su clave.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        //$sql = "SELECT id, username, password FROM users WHERE username = ?";
        $sql = "SELECT fun_run AS id, fun_correo AS username, fun_clave AS password FROM funcionario WHERE fun_correo = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: portal.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Usuario o clave inválidos.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Usuario o clave inválidos.";
                }
            } else{
                echo "Algo salió mal, intente más tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon" />
    <title>CES - Gestión de Talentos</title>
    <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js" integrity="sha256-a2yjHM4jnF9f54xUQakjZGaqYs/V1CYvWpoqZzC2/Bw=" crossorigin="anonymous"></script>

    <!--
      <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js" integrity="sha512-z4OUqw38qNLpn1libAN9BsoDx6nbNFio5lA6CuTp9NlK83b89hgyCVq+N5FdBJptINztxn1Z3SaKSKUS5UP60Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="assets/js/particles.js"></script>
      <script src="assets/js/main.js"></script>
    -->



	  <script src="https://kit.fontawesome.com/997c4473bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="assets/css/main.css">
    





    <style>
      .fondo {
        background-color: #ffff00;
        background: linear-gradient(-45deg, #ee7752, #FFD500, #23a6d5, #012B53);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
        
        
      }

      @keyframes gradient {
          0% {
              background-position: 0% 50%;
          }
          50% {
              background-position: 100% 50%;
          }
          100% {
              background-position: 0% 50%;
          }
      }
      canvas {
        display: block;
        width: 100vw;
        height: 100vh;
      }

      .container {
      position: static;
      }    
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
    (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
        const $notification = $delete.parentNode;

        $delete.addEventListener('click', () => {
        //$notification.parentNode.removeChild($notification);
        removeFadeOut($notification, 500);
        });
    });
    });

    function removeFadeOut( el, speed ) {
    var seconds = speed/1000;
    el.style.transition = "opacity "+seconds+"s ease";

    el.style.opacity = 0;
    setTimeout(function() {
        el.parentNode.removeChild(el);
    }, speed);
}

    </script>  

</head>
  <body>
    <div class="columns is-vcentered">
      <div class="login column is-4 ">
         <section class="section">
          <h1 class="title has-text-centered">Gestión de Talentos</h1>
          <div class="has-text-centered">
              <img alt="CES-Logo" class="login-logo" src="assets/img/logo.png">
          </div>
          
          <?php 
          //Si login_err no esta vacio, es que hay un error, mostrarlo.
          if(!empty($login_err)){
              echo '<div class="notification is-warning"><button class="delete"></button>' . $login_err . '</div>';
          }
          ?>          

          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="field">
            <label class="label">Usuario</label>
            <div class="control has-icons-right">
              <input name="username" class="input" type="email" placeholder="usuario@espiritusanto.cl">
              <span class="icon is-small is-right">
                <i class="fa-solid fa-user"></i>
              </span>
            </div>
            <p class="help has-text-grey-light w-100">Su correo electrónico institucional.</p>
          </div>

          <div class="field">
            <label class="label">Clave</label>
            <div class="control has-icons-right">
              <input name="password" class="input" type="password" placeholder="12345678">
              <span class="icon is-small is-right">
                <i class="fa-solid fa-key"></i>
              </span>
            </div>
            <p class="help has-text-grey-light w-100">Su RUT sin puntos, guion o digito verificador.</p>
          </div>
          <div class="has-text-centered">
            <input class="button is-vcentered is-info is-outlined" type="submit" value="Ingresar">              
          </div>
        </section>
      </div>
      <div id="fondo" class="fondo interactive-bg column is-8">
      </div>
    </div>
  </body>
    </html>