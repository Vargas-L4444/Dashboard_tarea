<?php  
require_once "vistas/parte_superior.php"; 
require_once "php/Curso_RegistroActividades_Crud.php"; 
$actividades = listarActividades();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro de Actividades</title>
    <link rel="icon" href="/Dashboard_tarea/img/EL_User2.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Registro de Actividades</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button id="btnCrear" class="btn btn-primary">Nueva Actividad</button>
            <a href="generar_pdf.php" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
        </div>
        <input type="text" id="busqueda" class="form-control w-25" placeholder="Buscar actividad...">
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Actividades</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Curso</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="actividadesTable">
                    <?php foreach ($actividades as $actividad): ?>
                        <tr class="actividad-item" id="fila_<?php echo $actividad['id']; ?>">
                            <td><?php echo $actividad['id']; ?></td>
                            <td><?php echo isset($actividad['usuario_nombre']) ? $actividad['usuario_nombre'] : 'Desconocido'; ?></td>
                            <td><?php echo isset($actividad['curso_nombre']) ? $actividad['curso_nombre'] : 'Desconocido'; ?></td>
                            <td><?php echo $actividad['accion']; ?></td>
                            <td><?php echo $actividad['descripcion']; ?></td>
                            <td><?php echo isset($actividad['fecha_hora']) ? $actividad['fecha_hora'] : 'Sin fecha'; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm editar-btn" data-id="<?php echo $actividad['id']; ?>" data-accion="<?php echo htmlspecialchars($actividad['accion'], ENT_QUOTES, 'UTF-8'); ?>">Editar</button>
                                <button class="btn btn-danger btn-sm eliminar-btn" data-id="<?php echo $actividad['id']; ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#busqueda").on("keyup", function() {
        var valor = $(this).val().toLowerCase();
        $(".actividad-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    $("#btnCrear").click(function() {
        Swal.fire({
            title: "Nueva Actividad",
            html: `
                <input type="text" id="usuario" class="swal2-input" placeholder="Usuario ID">
                <input type="text" id="curso" class="swal2-input" placeholder="Curso ID">
                <input type="text" id="accion" class="swal2-input" placeholder="Acción">
                <textarea id="descripcion" class="swal2-textarea" placeholder="Descripción"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            preConfirm: () => {
                let usuario_id = $("#usuario").val();
                let curso_id = $("#curso").val();
                let accion = $("#accion").val();
                let descripcion = $("#descripcion").val();
                if (!usuario_id || !curso_id || !accion) {
                    Swal.showValidationMessage("Todos los campos son obligatorios");
                    return false;
                }
                return { usuario_id, curso_id, accion, descripcion };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("php/Curso_RegistroActividades_Crud.php", { action: "crear", ...result.value }, function(response) {
                    if (response.success) {
                        Swal.fire("Éxito!", response.message, "success");
                        location.reload();
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                }, "json");
            }
        });
    });

    $(document).on("click", ".editar-btn", function() {
    var id = $(this).data("id");
    var accionActual = $(this).closest("tr").find("td:eq(3)").text();
    var descripcionActual = $(this).closest("tr").find("td:eq(4)").text();

    Swal.fire({
        title: "Editar Actividad",
        html: `
            <input type="text" id="accionEdit" class="swal2-input" placeholder="Acción" value="${accionActual}">
            <textarea id="descripcionEdit" class="swal2-textarea" placeholder="Descripción">${descripcionActual}</textarea>
        `,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        preConfirm: () => {
            let nuevaAccion = $("#accionEdit").val().trim();
            let nuevaDescripcion = $("#descripcionEdit").val().trim();

            if (!nuevaAccion || !nuevaDescripcion) {
                Swal.showValidationMessage("Todos los campos son obligatorios");
                return false;
            }

            return { id, nuevaAccion, nuevaDescripcion };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("php/Curso_RegistroActividades_Crud.php", {
                action: "editar",
                id: result.value.id,
                accion: result.value.nuevaAccion,
                descripcion: result.value.nuevaDescripcion
            }, function(response) {
                if (response.success) {
                    Swal.fire("¡Actualizado!", "Los datos fueron actualizados con éxito.", "success")
                        .then(() => location.reload());
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            }, "json");
        }
    });
});


    $(document).on("click", ".eliminar-btn", function() {
        var id = $(this).data("id");

        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esto.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminarlo!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("php/Curso_RegistroActividades_Crud.php", { action: "eliminar", id: id }, function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: "La notificación ha sido eliminada con éxito.",
                            icon: "success"
                        }).then(() => {
                            location.reload(); // Recargar la página después de cerrar la alerta
                        });
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                }, "json");
            }
        });
    });
});
</script>

<?php require_once "vistas/parte_inferior.php"; ?>