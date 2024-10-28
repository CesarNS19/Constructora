<!doctype html>
<html lang="en">
<head>
  <title>Login | Webleb</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="styl.css">
</head>
<body>
	<div class="section">
		<div class="container">
			<div class="row full-height justify-content-center">
				<div class="col-12 text-center align-self-center py-5">
					<div class="section pb-5 pt-5 pt-sm-2 text-center">
						<h6 class="mb-0 pb-3"><span>Log In</span><span>Sign Up</span></h6>
			          	<input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
			          	<label for="reg-log"></label>
						<div class="card-3d-wrap mx-auto">
							<div class="card-3d-wrapper">
								<!-- Login Form -->
								<div class="card-front">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Log In</h4>
											<form action="login_process.php" method="POST">
												<div class="form-group">
													<input type="email" name="email" class="form-style" placeholder="Email" required>
													<i class="input-icon uil uil-at"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="password" name="password" class="form-style" placeholder="Password" required>
													<i class="input-icon uil uil-lock-alt"></i>
												</div>
												<button type="submit" class="btn mt-4">Login</button>
												<p class="mb-0 mt-4 text-center"><a href="forgot_password.php" class="link">Forgot your password?</a></p>
											</form>
				      					</div>
			      					</div>
			      				</div>
								<!-- Register Form -->
								<div class="card-back">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-3 pb-3">Sign Up</h4>
											<form action="register_process.php" method="POST">
												<div class="form-group">
													<input type="text" name="nombre_cliente" class="form-style" placeholder="Nombre" required>
													<i class="input-icon uil uil-user"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="text" name="apellido_paterno" class="form-style" placeholder="Apellido Paterno" required>
													<i class="input-icon uil uil-user"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="text" name="apellido_materno" class="form-style" placeholder="Apellido Materno (opcional)">
													<i class="input-icon uil uil-user"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="tel" name="telefono_personal" class="form-style" placeholder="Teléfono Personal" required>
													<i class="input-icon uil uil-phone"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="email" name="correo_electronico" class="form-style" placeholder="Correo Electrónico" required>
													<i class="input-icon uil uil-at"></i>
												</div>
												<div class="form-group mt-2">
													<input type="password" name="contrasena" class="form-style" placeholder="Contraseña" required>
													<i class="input-icon uil uil-lock-alt"></i>
												</div>
												<div class="form-group mt-2">
													<input type="password" name="confirmar_contrasena" class="form-style" placeholder="Confirmar Contraseña" required>
													<i class="input-icon uil uil-lock-alt"></i>
												</div>
												<button type="submit" class="btn mt-4">Register</button>
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

  <style>
    /* Include your CSS styles here */
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900');
    body { /* your existing body styles */ }
    .link { color: #ffeba7; }
    .link:hover { color: #c4c3ca; }
    .section, .full-height, .checkbox, .card-3d-wrap, .card-3d-wrapper, .card-front, .card-back, .center-wrap, .form-group, .form-style, .input-icon, .btn {
      /* all existing styles here */
    }
  </style>

</body>
</html>
