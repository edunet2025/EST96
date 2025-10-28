const API_BUSCAR = "/backend/api/alumnos/buscar_por_matricula.php";
const API_BUSCA_MAT = "/backend/api/alumnos/buscar_matricula_por_nombre.php";
const API_GUARDAR = "/backend/api/reportes/guardar.php";

// Mostrar docente actual
document.getElementById("docInfo").textContent = `Docente: ${PHP_NOMBRE} (${PHP_MATRICULA})`;

// ==================== PREFECTO AUTO ====================
function asignaPrefecto(grado, grupo) {
  const g = (grupo || "").toUpperCase().trim();
  const n = parseInt(grado, 10);
  if (n === 1 && ["A", "B", "C", "D"].includes(g)) return "Edith";
  if (n === 1 && ["E", "F", "G"].includes(g)) return "Martin";
  if (n === 2 && ["A", "B", "C"].includes(g)) return "Carlos";
  if (n === 2 && ["D", "E", "F"].includes(g)) return "Carlos";
  if (n === 3 && ["A", "B", "C"].includes(g)) return "Rosalinda";
  if (n === 3 && ["D", "E", "F"].includes(g)) return "Alfredo";
  if (["1", "2", "3"].includes(String(n)) && g === "H") return "Erika";
  return "Prefecto";
}

function refrescaPrefecto(bloque) {
  const grado = bloque.querySelector(".grado").value;
  const grupo = bloque.querySelector(".grupo").value;
  bloque.querySelector(".prefecto").value = asignaPrefecto(grado, grupo);
}

// ==================== BUSCAR ALUMNO POR MATRÍCULA ====================
async function buscarAlumno(bloque) {
  const mat = bloque.querySelector(".matricula").value.trim();
  const msg = bloque.querySelector(".lookupMsg");
  if (!mat) return;
  msg.textContent = "Buscando...";
  try {
    const res = await fetch(`${API_BUSCAR}?matricula=${encodeURIComponent(mat)}`, { cache: "no-store" });
    const al = await res.json();
    if (al.error) {
      msg.textContent = "No encontrado";
      bloque.querySelector(".nombre").value = "";
      bloque.querySelector(".grado").value = "";
      bloque.querySelector(".grupo").value = "";
      bloque.querySelector(".prefecto").value = "";
      return;
    }

    bloque.querySelector(".nombre").value = al.nombre_completo || "";
    bloque.querySelector(".grado").value = al.grado || "";
    bloque.querySelector(".grupo").value = al.grupo || "";
    refrescaPrefecto(bloque);
    msg.textContent = "✓ Alumno encontrado";
  } catch (err) {
    console.error(err);
    msg.textContent = "Error al buscar matrícula.";
  }
}

// ==================== BUSCAR MATRÍCULA POR NOMBRE ====================
async function buscarMatriculaPorNombre(bloque) {
  const nombre = bloque.querySelector(".nombre").value.trim();
  const grado = bloque.querySelector(".grado").value;
  const grupo = bloque.querySelector(".grupo").value;
  const msg = bloque.querySelector(".lookupMsg");

  if (!nombre || !grado || !grupo) return;
  msg.textContent = "Buscando matrícula...";
  try {
    const res = await fetch(`${API_BUSCA_MAT}?nombre=${encodeURIComponent(nombre)}&grado=${grado}&grupo=${grupo}`);
    const data = await res.json();

    // Un solo resultado directo
    if (data.matricula) {
      bloque.querySelector(".matricula").value = data.matricula;
      bloque.querySelector(".prefecto").value = asignaPrefecto(grado, grupo);
      msg.textContent = `✓ Matrícula: ${data.matricula}`;
      return;
    }

    // Varios candidatos
    if (Array.isArray(data.candidatos) && data.candidatos.length > 1) {
      msg.innerHTML = `<select class="sel-candidatos" style="width:100%;margin-top:4px;">
        <option value="">Selecciona alumno</option>
        ${data.candidatos
          .map(
            (c) =>
              `<option value="${c.matricula}">${c.nombre_completo} — ${c.grado}°${c.grupo}</option>`
          )
          .join("")}
      </select>`;

      const sel = msg.querySelector(".sel-candidatos");
      sel.addEventListener("change", (e) => {
        const val = e.target.value;
        if (val) {
          bloque.querySelector(".matricula").value = val;
          msg.textContent = `✓ Matrícula: ${val}`;
        }
      });
      return;
    }

    // No coincidencias
    msg.textContent = "No se encontraron coincidencias.";
  } catch (err) {
    console.error(err);
    msg.textContent = "Error al buscar por nombre.";
  }
}

// ==================== CLONAR BLOQUE DE ALUMNO ====================
function inicializarBloque(b) {
  b.querySelector(".matricula").addEventListener("blur", () => buscarAlumno(b));
  b.querySelector(".nombre").addEventListener("blur", () => buscarMatriculaPorNombre(b));
  b.querySelector(".grado").addEventListener("change", () => refrescaPrefecto(b));
  b.querySelector(".grupo").addEventListener("change", () => refrescaPrefecto(b));
}

function agregarAlumno() {
  const cont = document.getElementById("alumnosContainer");
  const base = cont.firstElementChild;
  const clone = base.cloneNode(true);

  // limpia campos del clon
  clone.querySelectorAll("input").forEach((inp) => {
    if (inp.type !== "file") inp.value = "";
  });
  clone.querySelectorAll("select").forEach((sel) => {
    sel.value = "";
  });
  clone.querySelector(".lookupMsg").textContent = "";
  clone.querySelector(".scanMsg").textContent = "";

  cont.appendChild(clone);
  inicializarBloque(clone);
}

// Inicializar el primer bloque
document.querySelectorAll(".alumno-block").forEach(inicializarBloque);
document.getElementById("btnAddAlumno").addEventListener("click", agregarAlumno);

// ==================== ENVIAR REPORTE ====================
document.getElementById("frmReporte").addEventListener("submit", async (e) => {
  e.preventDefault();
  const status = document.getElementById("statusMsg");

  // armar array de alumnos
  const alumnos = [];
  document.querySelectorAll(".alumno-block").forEach((b) => {
    alumnos.push({
      matricula: b.querySelector(".matricula").value.trim(),
      nombre: b.querySelector(".nombre").value.trim(),
      grado: b.querySelector(".grado").value,
      grupo: b.querySelector(".grupo").value,
      prefecto_asignado: b.querySelector(".prefecto").value.trim(),
    });
  });

  // validaciones
  if (!PHP_NOMBRE || !PHP_MATRICULA) {
    status.className = "err";
    status.textContent = "❌ No se detectó sesión del docente.";
    return;
  }

  for (const a of alumnos) {
    if (!a.matricula || !a.nombre || !a.grado || !a.grupo) {
      status.className = "err";
      status.textContent = "❌ Falta capturar datos completos del alumno.";
      return;
    }
  }

  if (
    !document.getElementById("clase").value ||
    !document.getElementById("hora").value ||
    !document.getElementById("contenido").value.trim()
  ) {
    status.className = "err";
    status.textContent = "❌ Completa clase, hora y descripción.";
    return;
  }

  const payload = {
    docente: {
      usuario: PHP_MATRICULA,
      nombre: PHP_NOMBRE,
    },
    clase: document.getElementById("clase").value,
    hora: document.getElementById("hora").value,
    contenido: document.getElementById("contenido").value.trim(),
    alumnos,
  };

  status.className = "muted";
  status.textContent = "Enviando...";

  try {
    const res = await fetch(API_GUARDAR, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const textResp = await res.text();
    let data;
    try {
      data = JSON.parse(textResp);
    } catch (parseErr) {
      console.error("Respuesta cruda del servidor:", textResp);
      throw new Error("El servidor no devolvió JSON válido.");
    }

    if (!data.ok) {
      status.className = "err";
      status.textContent = "❌ Error al guardar: " + (data.error || "Desconocido");
      console.error("Backend dijo error:", data);
      return;
    }

    status.className = "ok";
    status.textContent = `✅ Folio ${data.folio} guardado correctamente.`;

    if (data.recibo_url) {
      document.getElementById("aRecibo").href = data.recibo_url;
      document.getElementById("reciboLink").style.display = "block";
    }

    // Limpiar formulario tras éxito
    document.getElementById("contenido").value = "";
    document.querySelectorAll(".alumno-block").forEach((b, i) => {
      if (i === 0) {
        b.querySelectorAll("input, select").forEach((el) => {
          if (el.type !== "file") el.value = "";
        });
        b.querySelector(".lookupMsg").textContent = "";
      } else {
        b.remove();
      }
    });
  } catch (err) {
    status.className = "err";
    status.textContent = "❌ Error al guardar: " + err.message;
    console.error(err);
  }
});
