document.addEventListener('DOMContentLoaded', function() {
    const fadeInElements = document.querySelectorAll('.fade-in');
    fadeInElements.forEach(element => {
        element.style.opacity = 0;
        element.style.transition = 'opacity 1s ease-in-out';
        setTimeout(() => {
            element.style.opacity = 1;
        }, 100);
    });

    const buttons3D = document.querySelectorAll('.button-3d');
    buttons3D.forEach(button => {
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'translateY(-5px)';
            button.style.boxShadow = '0 8px 12px rgba(0, 0, 0, 0.3)';
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translateY(0)';
            button.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.2)';
        });
    });

    // Alternar modo oscuro
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });

    // Alternar idioma
    const languageButton = document.getElementById("languageButton");
    let currentLang = "en";  // Idioma predeterminado

    languageButton.addEventListener("click", function() {
        currentLang = currentLang === "en" ? "es" : "en";
        
        document.querySelectorAll("[data-lang='en']").forEach(element => {
            element.style.display = currentLang === "en" ? "block" : "none";
        });
        document.querySelectorAll("[data-lang='es']").forEach(element => {
            element.style.display = currentLang === "es" ? "block" : "none";
        });
    });

    document.getElementById("signUp").addEventListener("click", function() {
        document.querySelector(".container").classList.add("right-panel-active");
    });
});
