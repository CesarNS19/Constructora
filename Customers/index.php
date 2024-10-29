<?php require '../Customers/superior_customer.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <section class="hero" data-lang="en">
        <div class="hero-content fade-in">
            <h1>Welcome to Our Website</h1>
            <p>Discover our services and offerings.</p>
            <button id="learnMoreButton" class="button-3d">Learn More</button>
        </div>
    </section>

    <section class="hero" data-lang="es" style="display:none;">
        <div class="hero-content fade-in">
            <h1>Bienvenido a Nuestro Sitio Web</h1>
            <p>Descubre nuestros servicios y ofertas.</p>
            <button id="learnMoreButton" class="button-3d">Aprender Más</button>
        </div>
    </section>

    <section class="plans" data-lang="en">
        <h2>Our Services</h2>
        <div class="plan-cards">
            <div class="card fade-in">
                <i class="fas fa-star"></i>
                <h3>Service 1</h3>
                <p>Service Description.</p>
                <a href="https://www.example.com" target="_blank">
                    <button>Learn More</button>
                </a>
            </div>
            <div class="card fade-in">
                <i class="fas fa-rocket"></i>
                <h3>Service 2</h3>
                <p>Service Description.</p>
                <a href="https://www.example.com" target="_blank">
                    <button>Learn More</button>
                </a>
            </div>
            <div class="card fade-in">
                <i class="fas fa-gem"></i>
                <h3>Service 3</h3>
                <p>Service Description.</p>
                <a href="https://www.example.com" target="_blank">
                    <button>Learn More</button>
                </a>
            </div>
        </div>
    </section>
    
    <section class="plans" data-lang="es" style="display:none;">
        <h2>Servicios</h2>
        <div class="plan-cards">
            <div class="card fade-in">
                <i class="fas fa-star"></i>
                <h3>Servicio 1</h3>
                <p>Descripción del servicio.</p>
                <a href="https://www.example.com" target="_blank">
                    <button>Saber Más</button>
                </a>
            </div>
            <div class="card fade-in">
                <i class="fas fa-rocket"></i>
                <h3>Servicio 2</h3>
                <p>Descripción del servicio.</p>
                <a href="https://www.example.com" target="_blank">
                    <button>Saber Más</button>
                </a>
            </div>
            <div class="card fade-in">
                <i class="fas fa-gem"></i>
                <h3>Servicio 3</h3>
                <p>Descripción del servicio.</p>
                <a href="https://www.example.com" target="_blank">
                    <button>Saber Más</button>
                </a>
            </div>
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