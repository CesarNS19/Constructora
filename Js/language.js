const translations = {
    "Add Service": "Agregar Servicio",
    "Add New Service": "Agregar Nuevo Servicio",
    "Name of Service": "Nombre del servicio",
    "Description of the Service": "Descripción del servicio",
    "Total Service": "Total del Servicio",
    "Service Image": "Imagen del Servicio",
    "Close": "Cerrar",
    "Edit Service": "Editar Servicio",
    "Edit": "Editar",
    "Delete": "Eliminar",
    "No services registered.": "No hay servicios registrados.",
    "Manage Services": "Administrar Servicios",
    "Good Morning": "Buenos días",
    "Good Afternoon": "Buenas tardes",
    "Good Night": "Buenas noches",
    "Log In": "Iniciar sesión",
    "Customers": "Clientes",
    "Employee": "Empleado",
    "Company": "Empresa",
    "Works": "Obras",
    "Budget": "Presupuesto",
    "Employee Payroll": "Nómina de Empleados",
    "Actions" : "Acciones",
    "Good Morning": "Buenos días",
    "Good Afternoon": "Buenas tardes",
    "Good Night": "Buenas noches",
    "Home" : "Inicio",
    "Manage Customers": "Administrar Clientes",
    "Add Customer": "Agregar Cliente",
    "View Addresses" : "Ver Direcciones",
    "Status" : "Estatus",
    "Role" : "Rol",
    "Age" : "Edad",
    "Email" : "Correo Electrónico",
    "Phone" : "Teléfono",
    "Gender" : "Género",
    "Customer Name" : "Nombre del Cliente",
    "Edit Customer": "Editar Cliente",
    "Save Changes": "Guardar Cambios",
    "Add Customer Address": "Añadir Dirección del Cliente",
    "Paternal Surname": "Apellido Paterno",
    "Maternal Surname": "Apellido Materno",
    "Outside Number": "Número Exterior",
    "Inner Number": "Número Interior",
    "Street": "Calle",
    "City": "Ciudad",
    "State": "Estado",
    "Postal Code": "Código Postal",
    "Deactivate Customer": "Desactivar Cliente",
    "Activate Customer": "Activar Cliente",
    "Edit Customer": "Editar Cliente",
    "Delete Customer": "Eliminar Cliente",
    "Add Address": "Agregar Dirección",
    "Password" : "Contraseña",
    "Confirm Password" : "Confirmar Contraseña",
    "Customer" : "Cliente",
    "Edit Customer Address": "Editar Dirección del Cliente",
    "Manage Customers Addresses": "Administrar Direcciones de Clientes",
    "Back" : "Regresar",
    "Add New Customer" : "Agregar Nuevo Cliente",
    "Current Image" : "Imágen Actual",
    "Update Service Image" : "Actualizar imágen del Servicio",
    "Manage Employee": "Administrar Empleados",
    "Position": "Cargo",
    "Activities": "Actividades",
    "Days Worked": "Días Trabajados",
    "Exit Time": "Hora Salida",
    "Entry Time": "Hora Entrada",
    "Employee Name": "Nombre de Empleado",
    "Selected a Company" : "Selecciona una Empresa",
    "Edit Employee": "Editar Empleado",
    "Selected Days Worked" : "Seleccionar Días Trabajados",
    "Monday": "Lunes",
    "Tuesday": "Martes",
    "Wednesday": "Miércoles",
    "Thursday": "Jueves",
    "Friday": "Viernes",
    "Saturday": "Sábado",
    "Sunday": "Domingo",
    "Add Company": "Agregar Empresa",
    "Web Page": "Página web",
    "Company Name": "Nombre de la Empresa",
    "Add Company Address": "Añadir Dirección de la Empresa",
    "Manage Company": "Administrar Empresas",
    "Selected Company": "Empresa Seleccionada",
    "Manage Company Addresses": "Administrar Direcciones de Empresas",
    "Edit Company Address": "Editar Dirección de Empresa",
    "Add Work": "Agregar Obra",
    "Manage Works": "Administrar Obras",
    "Observations": "Observaciones",
    "Total Work": "Total de la Obra",
    "Debit": "Adeudo",
    "Advance Payment": "Anticipo",
    "Start Date": "Fecha Inicio",
    "Customer Address": "Dirección del Cliente",
    "Work ID": "ID Obra",
    "Edit Work Address": "Editar Dirección de Obra",
    "Manage Works Addresses": "Administrar Direcciones de Obras",
    "Add New Work": "Agregar Nueva Obra",
    "Selected a Customer": "Selecciona un Cliente",
    "Edit Work": "Editar Obra",
    "Add Budget": "Agregar Presupuesto",
    "Manage Budgets": "Administrar Presupuestos",
    "Date of Preparation": "Fecha de Elaboración",
    "Edit Budget Address": "Editar Dirección de Presupuesto",
    "Manage Budget Addresses": "Administrar Direcciones de Presupuestos",
    "Add New Budget": "Agregar Nuevo Presupuesto",
    "Edit Budget": "Editar Presupuesto",
    "Add Budget Address": "Añadir Dirección del Presupuesto",
    "Selected Budget": "Presupuesto Seleccionado",
    "Add Work Address": "Añadir Dirección de la Obra",
    "Date": "Fecha",
    "Add Payroll": "Agregar Nómina",
    "Manage Payrolls": "Administrar Nóminas",
    "Daily Salary": "Sueldo Diario",
    "Add New Payroll": "Agregar Nueva Nómina",
    "Selected a Employee" : "Selecciona un Empleado",
    "Edit Payroll": "Editar Nómina",
    "Welcome Admin" : "Bienvenido Administrador",
    "Learn More" : "Leer más",
    "© Family Drywall. All rights reserved" : "© Family Drywall. Todos los Derechos reservados",
    "Discover our services and offerings" : "Descubra nuestros servicios y ofertas",
};


let isSpanish = localStorage.getItem("language") === "es";

function toggleLanguage() {
    isSpanish = !isSpanish;
    localStorage.setItem("language", isSpanish ? "es" : "en");
    translatePage();
}

function translatePage() {
    document.querySelectorAll("*").forEach(element => {
        if (element.children.length === 0) {
            const text = element.innerText.trim();
            if (isSpanish && translations[text]) {
                element.innerText = translations[text];
            } else if (!isSpanish) {
                const originalText = Object.keys(translations).find(
                    key => translations[key] === text
                );
                if (originalText) {
                    element.innerText = originalText;
                }
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    if (isSpanish) translatePage();

    document.getElementById("languageButton").addEventListener("click", toggleLanguage);
});
