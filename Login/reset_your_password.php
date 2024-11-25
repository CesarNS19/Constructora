<!doctype html>
<html lang="es">
<head>
  <title>Reset Password | Family Drywall</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../Css/styl.css">
</head>
<body>

<div class="section">
  <div class="container">
    <div class="row full-height justify-content-center">
      <div class="col-12 text-center align-self-center py-5">
        <div class="section pb-5 pt-5 pt-sm-2 text-center">
          <div class="card-3d-wrap mx-auto">
            <div class="card-3d-wrapper">
              <div class="card-front">
                <div class="center-wrap">
                  <div class="section text-center">
                    <h4 class="mb-4 pb-3">Recuperar Contraseña</h4>
                    <form action="reset_password.php?code=<?php echo $_GET['code']; ?>" method="POST" onsubmit="return confirmSubmit()">
                        <div class="form-group">
                            <input id="code" type="text" name="code" class="form-style" placeholder="Código de Recuperación" required>
                            <i class="input-icon uil uil-lock-alt"></i>
                        </div><br/>
                        <div class="form-group">
                            <input id="password" type="password" name="password" class="form-style" placeholder="Nueva Contraseña" required>
                            <i class="input-icon uil uil-lock-alt"></i>
                        </div>  
                        <br/>
                        <div class="form-group">
                            <input id="confirm_password" type="password" name="confirm_password" class="form-style" placeholder="Confirmar Contraseña" required>
                            <i class="input-icon uil uil-lock-alt"></i>
                        </div>  
                        <button type="submit" class="btn mt-4 login-btn">Restablecer Contraseña</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmSubmit() {
    return confirm("¿Estás seguro de que deseas restablecer la contraseña?");
  }
</script>

</body>
</html>
