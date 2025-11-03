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
  // Permite marcar/desmarcar con tecla de espacio
document.addEventListener("keydown", e => {
  if (e.code === "Space" && document.activeElement.type === "checkbox") {
    e.preventDefault();
    document.activeElement.checked = !document.activeElement.checked;
  }
});
// --- Marcar / desmarcar todos ---
document.addEventListener("DOMContentLoaded", () => {
  const marcarTodos = document.getElementById("marcarTodos");
  const desmarcarTodos = document.getElementById("desmarcarTodos");

  if (marcarTodos && desmarcarTodos) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="asistencia["]');

    marcarTodos.addEventListener("click", () => {
      checkboxes.forEach(chk => chk.checked = true);
    });

    desmarcarTodos.addEventListener("click", () => {
      checkboxes.forEach(chk => chk.checked = false);
    });
  }
});
