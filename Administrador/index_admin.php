<?php require '../Administrador/superior_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <!-- Secciones en inglés y español -->
    <section class="hero" data-lang="en">
        <div class="hero-content fade-in">
            <h1>Welcome Admin</h1>
            <p>Discover our services and offerings.</p>
            <button id="learnMoreButton" class="button-3d">Learn More</button>
        </div>
    </section>

    <section class="hero" data-lang="es" style="display:none;">
        <div class="hero-content fade-in">
            <h1>Bienvenido Admin</h1>
            <p>Descubre nuestros servicios y ofertas.</p>
            <button id="learnMoreButton" class="button-3d">Aprender Más</button>
        </div>
    </section>
   
    <footer data-lang="en">
        <p>© Family Drywall. All rights reserved.</p>
    </footer>
    
    <footer data-lang="es" style="display:none;">
        <p>© Family Drywall. Todos los derechos reservados.</p>
    </footer>  

    <script src="../Js/script.js"></script>
</body>
</html>