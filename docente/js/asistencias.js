document.addEventListener("DOMContentLoaded", () => {
  // Validación de formulario en asistencias.php
  const form = document.querySelector(".asistencias-form");
  if (form) {
    form.addEventListener("submit", (e) => {
      const campos = form.querySelectorAll("select, input[type='date']");
      for (let campo of campos) {
        if (!campo.value) {
          alert("Por favor completa todos los campos antes de continuar.");
          e.preventDefault();
          return;
        }
      }
    });
  }

  // --- Marcar / desmarcar todos ---
  const marcarTodos = document.getElementById("marcarTodos");
  const desmarcarTodos = document.getElementById("desmarcarTodos");

  if (marcarTodos && desmarcarTodos) {
    marcarTodos.addEventListener("click", () => {
      document.querySelectorAll('input[type="checkbox"][name^="asistencia["]')
        .forEach(chk => chk.checked = true);
    });

    desmarcarTodos.addEventListener("click", () => {
      document.querySelectorAll('input[type="checkbox"][name^="asistencia["]')
        .forEach(chk => chk.checked = false);
    });
  }

  // --- Alternar con barra espaciadora
  document.addEventListener("keydown", e => {
    if (e.code === "Space" && document.activeElement.type === "checkbox") {
      e.preventDefault();
      document.activeElement.checked = !document.activeElement.checked;
    }
  });
});
