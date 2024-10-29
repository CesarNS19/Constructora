<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .form-group label {
            font-weight: bold;
        }
        .section-title {
            margin-top: 30px;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Registro de Cliente</h2>
        <form method="POST" action="register_process.php">
            <div class="section-title">Datos Personales</div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nombre_cliente">Nombre</label>
                    <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido_paterno">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido_materno">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="telefono_personal">Teléfono Personal</label>
                    <input type="text" class="form-control" id="telefono_personal" name="telefono_personal" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="correo_electronico">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="confirmar_contrasena">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                </div>
            </div>

            <div class="section-title">Dirección</div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="num_ext">Num. Exterior</label>
                    <input type="text" class="form-control" id="num_ext" name="num_ext" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="num_int">Num. Interior</label>
                    <input type="text" class="form-control" id="num_int" name="num_int">
                </div>
                <div class="form-group col-md-4">
                    <label for="calle">Calle</label>
                    <input type="text" class="form-control" id="calle" name="calle" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="estado">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="codigo_postal">Código Postal</label>
                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
        <p class="text-center mt-3">
            ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
        </p>
    </div>
</body>
</html>
