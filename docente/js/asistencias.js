document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".asistencias-form");
    form.addEventListener("submit", (e) => {
      const campos = form.querySelectorAll("select, input");
      for (let campo of campos) {
        if (!campo.value) {
          alert("Por favor completa todos los campos antes de continuar.");
          e.preventDefault();
          return;
        }
      }
    });
  });
  