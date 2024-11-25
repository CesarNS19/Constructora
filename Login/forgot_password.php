<!doctype html>
<html lang="es">
<head>
  <title>Login | Family</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../Css/styl.css">

</head>
    <style>
        .cancel-btn {
        background-color: #dc3545;
        color: white;
        }
        .alert {
        width: 100%;
        margin-bottom: 15px;
        }
    </style>
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
                    <h4 class="mb-4 pb-3">Forgot Password</h4>
                    <form action="recover_password.php" method="POST" onsubmit="return confirmSubmit()">
                      <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                          ¡Correo de recuperación enviado con éxito! Por favor, revisa tu bandeja de entrada.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <script>
                          setTimeout(function() {
                            var alert = document.querySelector('#alert-success');
                            alert.classList.remove('show');
                            alert.classList.add('fade');
                            resetForm();
                          }, 5000);
                        </script>
                      <?php elseif (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-error">
                          <?php echo htmlspecialchars($_GET['error']); ?>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <script>
                          setTimeout(function() {
                            var alert = document.querySelector('#alert-error');
                            alert.classList.remove('show');
                            alert.classList.add('fade');
                            resetForm();
                          }, 5000);
                        </script>
                      <?php endif; ?>
                      
                      <div class="form-group">
                        <input id="email" type="email" name="email" class="form-style" placeholder="Email" required>
                        <i class="input-icon uil uil-at"></i>
                      </div>
                      <a href="login.php" class="btn mt-4 cancel-btn">Back</a>
                      <button type="submit" class="btn mt-4 login-btn" onclick="showConfirmation()">Send</button>
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
    return confirm("¿Estás seguro de que deseas recuperar la contraseña?");
  }

  function resetForm() {
    document.getElementById('email').value = '';
    var successAlert = document.querySelector('#alert-success');
    var errorAlert = document.querySelector('#alert-error');

    if (successAlert) {
      successAlert.remove();
    }
    if (errorAlert) {
      errorAlert.remove();
    }
  }
</script>

</body>
</html>
