document.addEventListener("DOMContentLoaded", () => {
    const listaCursos = document.getElementById("lista-cursos");
    const formCurso = document.getElementById("form-curso");

    // Obtener cursos desde PHP
    fetch("php/cursos.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(curso => {
                let li = document.createElement("li");
                li.textContent = `${curso.nombre} - ${curso.descripcion}`;
                listaCursos.appendChild(li);
            });
        });

    // Enviar un nuevo curso
    formCurso.addEventListener("submit", function(event) {
        event.preventDefault();

        let cursoData = {
            nombre: document.getElementById("nombre").value,
            descripcion: document.getElementById("descripcion").value,
            fecha_inicio: document.getElementById("fecha_inicio").value,
            fecha_fin: document.getElementById("fecha_fin").value
        };

        fetch("php/cursos.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(cursoData)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.mensaje || data.error);
            location.reload();
        });
    });
});
