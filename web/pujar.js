// Tamaño de cada trozo: 1 MB (cabe en el límite por defecto de PHP, que es 2 MB)
const TAMANY_BLOC = 1024 * 1024;

async function pujarImatge() {
  const campo    = document.getElementById('arxiu');
  const barra    = document.getElementById('barra');
  const missatge  = document.getElementById('missatge');
  const arxiu  = campo.files[0];            // el archivo elegido por el usuario

  if (!arxiu) {
    missatge.textContent = 'Tria primer la imatge.';
    return;
  }

  // Cuántos trozos salen: 350 MB / 1 MB = 350 trozos
  const total = Math.ceil(arxiu.size / TAMANY_BLOC);

  for (let i = 0; i < total; i++) {
    // Cortamos el trozo i: desde "inicio" hasta "fin"
    const inici = i * TAMANY_BLOC;
    const fi    = inici + TAMANY_BLOC;
    const bloc  = arxiu.slice(inici, fi);

    // Preparamos los datos igual que un formulario con enctype multipart
    const dades = new FormData();
    dades.append('bloc',  bloc);
    dades.append('nom', arxiu.name);
    dades.append('numero', i);
    dades.append('total',  total);

    // Enviem i esperem la resposta abans de enviar la següent
    const resposta = await fetch('pujar.php', { method: 'POST', body: dades });
    const text = await resposta.text();

    if (text !== 'ok') {
      missatge.textContent = 'Error en el bloc ' + i + ': ' + text;
      return;                                  
    }

    // Actualitzem la barra de progress
    const percentatge = Math.round(((i + 1) / total) * 100);
    barra.value = percentatge;
    missatge.textContent = 'Pujant... ' + percentatge + '%';
  }

  missatge.textContent = 'Arxiu pujat! Actualitzant el llistat...';
  setTimeout(() => location.reload(), 1000);
}

function mostrarPestana(id) {
  const paneles = document.querySelectorAll('.panel');
  paneles.forEach(p => p.style.display = 'none');
  document.getElementById(id).style.display = 'block';
}
