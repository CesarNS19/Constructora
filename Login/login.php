<!doctype html>
<html lang="es">
<head>
  <title>Login | Family </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../Css/styl.css">
</head>
<body>

<div class="section">
  <div class="container">
    <div class="row full-height justify-content-center">
      <div class="col-12 text-center align-self-center py-5">
        <div class="section pb-5 pt-5 pt-sm-2 text-center">
          <div id="login-form" class="card" style="width: 100%; max-width: 400px; margin: 0 auto;">
            <div class="card-body">
              <h4 class="mb-4 pb-3 text-center">Log In</h4>
              <form action="login_process.php" method="POST">
                <div class="form-group">
                  <input type="email" name="email" class="form-style form-control" placeholder="Email" required>
                  <i class="input-icon uil uil-at"></i>
                </div>  
                <div class="form-group mt-2">
                  <input type="password" name="password" class="form-style form-control" placeholder="Password" required>
                  <i class="input-icon uil uil-lock-alt"></i>
                </div>
                <button type="submit" class="btn-login btn-primary mt-4 w-100 d-flex justify-content-center">Login</button>
                <p class="mb-0 mt-4 text-center"><a href="forgot_password.php" class="link">Forgot your password?</a></p>
                <p class="mb-0 mt-4 text-center"><a href="javascript:void(0);" id="go-to-register" class="link">No tienes una cuenta? Crea una</a></p>
              </form>
            </div>
          </div>

          <div id="register-form" class="card" style="width: 100%; max-width: 400px; margin: 0 auto; display: none;">
            <div class="card-body">
              <h4 class="mb-3 pb-3 text-center">Sign Up</h4>
              <form action="register_process.php" method="POST">
                <div class="form-group">
                  <input type="text" name="nombre_cliente" class="form-style form-control" placeholder="Nombre" required>
                  <i class="input-icon uil uil-user"></i>
                </div>  
                <div class="form-group mt-2">
                  <input type="text" name="apellido_paterno" class="form-style form-control" placeholder="Apellido Paterno" required>
                  <i class="input-icon uil uil-user"></i>
                </div>  
                <div class="form-group mt-2">
                  <input type="text" name="apellido_materno" class="form-style form-control" placeholder="Apellido Materno (opcional)">
                  <i class="input-icon uil uil-user"></i>
                </div>  
                <div class="form-group mt-2">
                  <input type="text" name="genero_cliente" class="form-style form-control" placeholder="Genero">
                  <i class="input-icon uil uil-user"></i>
                </div> 
                <div class="form-group mt-2">
                  <input type="tel" name="telefono_personal" class="form-style form-control" placeholder="Teléfono Personal" required>
                  <i class="input-icon uil uil-phone"></i>
                </div>  
                <div class="form-group mt-2">
                  <input type="email" name="correo_electronico" class="form-style form-control" placeholder="Correo Electrónico" required>
                  <i class="input-icon uil uil-at"></i>
                </div>
                <div class="form-group mt-2">
                  <input type="number" name="edad" class="form-style form-control" placeholder="Edad">
                  <i class="input-icon uil uil-user"></i>
                </div> 
                <div class="form-group mt-2">
                  <input type="password" name="contrasena" class="form-style form-control" placeholder="Contraseña" required>
                  <i class="input-icon uil uil-lock-alt"></i>
                </div>
                <div class="form-group mt-2">
                  <input type="password" name="confirmar_contrasena" class="form-style form-control" placeholder="Confirmar Contraseña" required>
                  <i class="input-icon uil uil-lock-alt"></i>
                </div>
                <button type="submit" class="btn-sign btn-primary mt-4 register-btn w-100 d-flex justify-content-center">Register</button>
                <p class="mb-0 mt-4 text-center"><a href="javascript:void(0);" id="go-to-login" class="link">Ya tienes una cuenta? Inicia sesión</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Incluye los estilos necesarios aquí */
  @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900');
  .link {
    color: #007bff;
  }
  .link:hover {
    color: #0056b3;
  }
  .section, .full-height, .form-group, .form-style, .input-icon, .btn {
    /* tus estilos existentes */
  }
  .card-body{
    background: #2b2e38;
  }
  .btn-login {
      border-radius: 4px;
      height: 44px;
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      transition: all 200ms linear;
      padding: 0 30px;
      letter-spacing: 1px;
      display: inline-flex;
      align-items: center;
      color: #000000; /* Texto negro */
      background: ;
      border: none;
    }


.btn-sign{  
  border-radius: 4px;
  height: 44px;
  font-size: 13px;
  font-weight: 600;
  text-transform: uppercase;
  -webkit-transition : all 200ms linear;
  transition: all 200ms linear;
  padding: 0 30px;
  letter-spacing: 1px;
  display: -webkit-inline-flex;
  display: -ms-inline-flexbox;
  display: inline-flex;
  align-items: center;
  color: #000000;
}
</style>

<script>
  document.getElementById('go-to-register').addEventListener('click', function() {
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('register-form').style.display = 'block';
  });

  document.getElementById('go-to-login').addEventListener('click', function() {
    document.getElementById('register-form').style.display = 'none';
    document.getElementById('login-form').style.display = 'block';
  });
</script>

</body>
</html>
