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

    // Alternar modo escuro
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');

    });
    document.getElementById("languageButton").addEventListener("click", function() {
        // Alternar entre los elementos en inglés y en español
        document.querySelectorAll("[data-lang]").forEach(element => {
            if (element.style.display === "none") {
                element.style.display = "";
            } else {
                element.style.display = "none";
            }
        });
    });
    document.getElementById("signUp").addEventListener("click", function() {
    document.querySelector(".container").classList.add("right-panel-active");
});
});