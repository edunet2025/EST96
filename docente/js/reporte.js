const API_BUSCAR = "/backend/api/alumnos/buscar_por_matricula.php";
const API_GUARDAR = "/backend/api/reportes/guardar.php";

document.getElementById("docInfo").textContent = `Docente: ${PHP_NOMBRE} (${PHP_MATRICULA})`;

// ----------------------
// PREFECTOS AUTOMÁTICOS
// ----------------------
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
  bloque.querySelector(".prefecto").value = asignaPrefecto(grado, grupo);
}

// ----------------------
// BÚSQUEDA DE ALUMNO
// ----------------------
async function buscarAlumno(bloque){
  const mat = bloque.querySelector(".matricula").value.trim();
  const msg = bloque.querySelector(".lookupMsg");
  msg.textContent = "Buscando...";
  try{
    const res = await fetch(`${API_BUSCAR}?matricula=${encodeURIComponent(mat)}`);
    if(!res.ok) throw new Error("HTTP "+res.status);
    const al = await res.json();
    bloque.querySelector(".nombre").value = al.nombre_completo || "";
    bloque.querySelector(".grado").value = al.grado || "";
    bloque.querySelector(".grupo").value = al.grupo || "";
    refrescaPrefecto(bloque);
    msg.textContent = "✓ Alumno encontrado";
  }catch(err){
    msg.textContent = "No encontrado";
    bloque.querySelector(".nombre").value="";
    bloque.querySelector(".grado").value="";
    bloque.querySelector(".grupo").value="";
  }
}

// ----------------------
// CLONAR BLOQUES
// ----------------------
function agregarAlumno(){
  const cont = document.getElementById("alumnosContainer");
  const base = cont.firstElementChild;
  const clone = base.cloneNode(true);
  clone.querySelectorAll("input,select,textarea").forEach(el=>{el.value="";});
  clone.querySelector(".lookupMsg").textContent="";
  cont.appendChild(clone);
  inicializarBloque(clone);
}

function inicializarBloque(b){
  b.querySelector(".matricula").addEventListener("blur",()=>buscarAlumno(b));
  b.querySelector(".grado").addEventListener("change",()=>refrescaPrefecto(b));
  b.querySelector(".grupo").addEventListener("change",()=>refrescaPrefecto(b));
}
document.querySelectorAll(".alumno-block").forEach(inicializarBloque);
document.getElementById("btnAddAlumno").addEventListener("click", agregarAlumno);

// ----------------------
// ENVIAR FORMULARIO
// ----------------------
document.getElementById("frmReporte").addEventListener("submit", async (e)=>{
  e.preventDefault();
  const status = document.getElementById("statusMsg");

  const alumnos = [];
  document.querySelectorAll(".alumno-block").forEach(b=>{
    alumnos.push({
      matricula: b.querySelector(".matricula").value.trim(),
      nombre: b.querySelector(".nombre").value.trim(),
      grado: b.querySelector(".grado").value,
      grupo: b.querySelector(".grupo").value
    });
  });

  const payload = {
    docente: {usuario: PHP_MATRICULA, nombre: PHP_NOMBRE},
    prefecto: alumnos[0]?.prefecto || "Automático",
    clase: document.getElementById("clase").value,
    hora: document.getElementById("hora").value,
    contenido: document.getElementById("contenido").value.trim(),
    alumnos
  };

  status.textContent="Enviando...";
  try{
    const res = await fetch(API_GUARDAR,{
      method:"POST",
      headers:{"Content-Type":"application/json"},
      body:JSON.stringify(payload)
    });
    const data = await res.json();
    if(data.ok){
      status.className="ok";
      status.textContent=`✅ Folio ${data.folio} guardado correctamente.`;
      document.getElementById("aRecibo").href=data.recibo_url;
      document.getElementById("reciboLink").style.display="block";
      e.target.reset();
    }else{
      status.className="err";
      status.textContent="❌ "+(data.error||"Error desconocido");
    }
  }catch(err){
    status.className="err";
    status.textContent="❌ Error al guardar: "+err.message;
  }
});
