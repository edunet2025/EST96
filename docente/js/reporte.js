const API_BUSCAR = "/backend/api/alumnos/buscar_por_matricula.php";
const API_GUARDAR = "/backend/api/reportes/guardar.php";

// Poner el nombre/matrícula del docente actual
document.getElementById("docInfo").textContent = `Docente: ${PHP_NOMBRE} (${PHP_MATRICULA})`;

// ==================== PREFECTO AUTO ====================
function asignaPrefecto(grado, grupo){
  const g = (grupo||"").toUpperCase().trim();
  const n = parseInt(grado,10);
  if (n===1 && ["A","B","C","D"].includes(g)) return "Andrés";
  if (n===2 && ["A","B","C","D"].includes(g)) return "Karla";
  if (n===3 && ["A","B","C","D"].includes(g)) return "José Luis";
  if (["1","2","3"].includes(String(n)) && g==="E") return "Erika";
  return "Prefecto";
}

function refrescaPrefecto(bloque){
  const grado = bloque.querySelector(".grado").value;
  const grupo = bloque.querySelector(".grupo").value;
  const pref = asignaPrefecto(grado, grupo);
  bloque.querySelector(".prefecto").value = pref;
}

// ==================== BUSCAR ALUMNO POR MATRÍCULA ====================
async function buscarAlumno(bloque){
  const mat = bloque.querySelector(".matricula").value.trim();
  const msg = bloque.querySelector(".lookupMsg");
  msg.textContent = "Buscando...";
  try{
    const res = await fetch(`${API_BUSCAR}?matricula=${encodeURIComponent(mat)}`, {cache:"no-store"});
    const al = await res.json();
    if (al.error){
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
  }catch(err){
    console.error(err);
    msg.textContent = "Error";
  }
}

// ==================== CLONAR BLOQUE DE ALUMNO ====================
function inicializarBloque(b){
  b.querySelector(".matricula").addEventListener("blur",()=>buscarAlumno(b));
  b.querySelector(".grado").addEventListener("change",()=>refrescaPrefecto(b));
  b.querySelector(".grupo").addEventListener("change",()=>refrescaPrefecto(b));
}

function agregarAlumno(){
  const cont = document.getElementById("alumnosContainer");
  const base = cont.firstElementChild;
  const clone = base.cloneNode(true);

  // limpia campos del clon
  clone.querySelectorAll("input").forEach(inp=>{
    // menos el tipo="file"
    if (inp.type !== "file") inp.value = "";
  });
  clone.querySelectorAll("select").forEach(sel=>{ sel.value = ""; });
  clone.querySelector(".lookupMsg").textContent = "";
  clone.querySelector(".scanMsg").textContent = "";

  cont.appendChild(clone);
  inicializarBloque(clone);
}

// inicializar primer bloque existente
document.querySelectorAll(".alumno-block").forEach(inicializarBloque);
document.getElementById("btnAddAlumno").addEventListener("click", agregarAlumno);

// ==================== ENVIAR REPORTE ====================
document.getElementById("frmReporte").addEventListener("submit", async (e)=>{
  e.preventDefault();
  const status = document.getElementById("statusMsg");

  // armar array alumnos
  const alumnos = [];
  document.querySelectorAll(".alumno-block").forEach(b=>{
    alumnos.push({
      matricula: b.querySelector(".matricula").value.trim(),
      nombre: b.querySelector(".nombre").value.trim(),
      grado: b.querySelector(".grado").value,
      grupo: b.querySelector(".grupo").value,
      prefecto_asignado: b.querySelector(".prefecto").value.trim()
    });
  });

  // validar rápido
  if (!PHP_NOMBRE || !PHP_MATRICULA){
    status.className = "err";
    status.textContent = "❌ No se detectó sesión del docente.";
    return;
  }

  for (const a of alumnos){
    if (!a.matricula || !a.nombre || !a.grado || !a.grupo){
      status.className = "err";
      status.textContent = "❌ Falta capturar datos completos del alumno.";
      return;
    }
  }

  if (!document.getElementById("clase").value ||
      !document.getElementById("hora").value ||
      !document.getElementById("contenido").value.trim()) {
    status.className = "err";
    status.textContent = "❌ Completa clase, hora y descripción.";
    return;
  }

  const payload = {
    docente: {
      usuario: PHP_MATRICULA,
      nombre: PHP_NOMBRE
    },
    clase: document.getElementById("clase").value,
    hora: document.getElementById("hora").value,
    contenido: document.getElementById("contenido").value.trim(),
    alumnos
  };

  status.className = "muted";
  status.textContent = "Enviando...";

  try {
    const res = await fetch(API_GUARDAR, {
      method:"POST",
      headers:{"Content-Type":"application/json"},
      body: JSON.stringify(payload)
    });

    const textResp = await res.text(); // <-- leemos texto SIEMPRE
    let data;
    try {
      data = JSON.parse(textResp);
    } catch(parseErr) {
      console.error("Respuesta cruda del servidor:", textResp);
      throw new Error("El servidor no devolvió JSON válido.");
    }

    if (!data.ok){
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

    // limpiar descripción, pero NO borro los alumnos por si quieres mandar otro similar
    document.getElementById("contenido").value = "";

  } catch (err) {
    status.className = "err";
    status.textContent = "❌ Error al guardar: " + err.message;
    console.error(err);
  }
});

// (OCR sigue pendiente de integrar aquí para múltiples alumnos, lo podemos agregar después)
