const API_BUSCAR = "/backend/api/alumnos/buscar_por_matricula.php";
const API_BUSCA_MAT = "/backend/api/alumnos/buscar_matricula_por_nombre.php";
const API_GUARDAR = "/backend/api/reportes/guardar.php";

// Prefecto automático
function asignaPrefecto(grado, grupo) {
  const g = (grupo || "").toUpperCase().trim();
  const n = parseInt(grado, 10);
  if (n === 1 && ["A","B","C","D"].includes(g)) return "Andrés";
  if (n === 2 && ["A","B","C","D"].includes(g)) return "Karla";
  if (n === 3 && ["A","B","C","D"].includes(g)) return "José Luis";
  if (["1","2","3"].includes(String(n)) && g === "E") return "Erika";
  return "Prefecto";
}

function refrescaPrefecto(block) {
  const grado = block.querySelector(".sel-grado").value;
  const grupo = block.querySelector(".sel-grupo").value;
  block.querySelector(".prefecto").value = asignaPrefecto(grado, grupo);
}

// Búsqueda por matrícula
async function buscarAlumno(block) {
  const mat = block.querySelector(".inp-mat").value.trim();
  const msg = block.querySelector(".msg");
  if (!mat) return;
  msg.textContent = "Buscando...";
  try {
    const res = await fetch(`${API_BUSCAR}?matricula=${encodeURIComponent(mat)}`);
    const al = await res.json();
    if (al?.nombre_completo) {
      block.querySelector(".inp-nom").value = al.nombre_completo;
      block.querySelector(".sel-grado").value = al.grado;
      block.querySelector(".sel-grupo").value = al.grupo;
      refrescaPrefecto(block);
      msg.textContent = "✓ Alumno encontrado";
    } else {
      msg.textContent = "No encontrado.";
    }
  } catch {
    msg.textContent = "Error en búsqueda.";
  }
}

// Búsqueda por nombre
async function buscarPorNombre(block) {
  const nombre = block.querySelector(".inp-nom").value.trim();
  const grado = block.querySelector(".sel-grado").value;
  const grupo = block.querySelector(".sel-grupo").value;
  const msg = block.querySelector(".msg");
  if (!nombre || !grado || !grupo) return;
  msg.textContent = "Buscando coincidencias...";
  try {
    const res = await fetch(`${API_BUSCA_MAT}?nombre=${encodeURIComponent(nombre)}&grado=${grado}&grupo=${grupo}`);
    const data = await res.json();
    if (data.matricula) {
      block.querySelector(".inp-mat").value = data.matricula;
      msg.textContent = `✓ Matrícula: ${data.matricula}`;
    } else if (Array.isArray(data.candidatos) && data.candidatos.length > 1) {
      let html = `<select class='sel-candidatos'><option value=''>Selecciona alumno</option>`;
      data.candidatos.forEach(c=>{
        html += `<option value='${c.matricula}'>${c.nombre_completo} — ${c.grado}°${c.grupo}</option>`;
      });
      html += "</select>";
      msg.innerHTML = html;
      msg.querySelector(".sel-candidatos").addEventListener("change", e=>{
        const val = e.target.value;
        if (val) {
          block.querySelector(".inp-mat").value = val;
          msg.textContent = `✓ Matrícula: ${val}`;
        }
      });
    } else msg.textContent = "Sin coincidencias.";
  } catch {
    msg.textContent = "Error en búsqueda.";
  }
}

// Eventos
function attachEvents(block) {
  block.querySelector(".inp-mat").addEventListener("blur", ()=> buscarAlumno(block));
  block.querySelector(".inp-nom").addEventListener("blur", ()=> buscarPorNombre(block));
  block.querySelector(".sel-grado").addEventListener("change", ()=> refrescaPrefecto(block));
  block.querySelector(".sel-grupo").addEventListener("change", ()=> refrescaPrefecto(block));
}

document.querySelectorAll(".alumno-card").forEach(attachEvents);

// Agregar alumno
document.getElementById("btnAdd").addEventListener("click", ()=>{
  const cont = document.getElementById("alumnosCont");
  const clone = cont.firstElementChild.cloneNode(true);
  clone.querySelectorAll("input,select").forEach(i=> i.value = "");
  clone.querySelector(".msg").textContent = "";
  cont.appendChild(clone);
  attachEvents(clone);
});

// Guardar reporte
document.getElementById("frmReporte").addEventListener("submit", async e=>{
  e.preventDefault();
  const status = document.getElementById("statusMsg");
  const alumnos = Array.from(document.querySelectorAll(".alumno-card")).map(block=>({
    matricula: block.querySelector(".inp-mat").value.trim(),
    nombre: block.querySelector(".inp-nom").value.trim(),
    grado: block.querySelector(".sel-grado").value,
    grupo: block.querySelector(".sel-grupo").value,
    prefecto_asignado: block.querySelector(".prefecto").value
  }));

  const payload = {
    matricula_docente: DOCENTE.matricula,
    nombre_docente: DOCENTE.nombre,
    clase: document.getElementById("clase").value,
    hora: document.getElementById("hora").value,
    contenido: document.getElementById("contenido").value,
    alumnos
  };

  status.textContent = "Enviando...";
  try {
    const res = await
