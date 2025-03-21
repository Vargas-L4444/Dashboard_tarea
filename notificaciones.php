<?php  
require_once "vistas/parte_superior.php"; 
require_once "php/notificaciones_crud.php"; 
$notificaciones = obtenerNotificaciones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Notificaciones y Mensajería</title>
    <link rel="icon" href="/Dashboard_tarea/img/EL_User2.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Notificaciones y Mensajería</h1>

    <!-- Contenedor para el botón de nueva notificación, botón de refrescar y la barra de búsqueda -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button id="btnCrear" class="btn btn-primary">Nueva Notificación</button>
        </div>
        <input type="text" id="busqueda" class="form-control w-25" placeholder="Buscar notificación...">
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Notificaciones</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="notificacionesTable">
                    <?php foreach ($notificaciones as $notificacion): ?>
                        <tr class="notificacion-item" id="fila_<?php echo $notificacion['id']; ?>">
                            <td><?php echo $notificacion['id']; ?></td>
                            <td><?php echo $notificacion['usuario']; ?></td>
                            <td><?php echo $notificacion['mensaje']; ?></td>
                            <td><?php echo $notificacion['fecha_envio']; ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm editar-btn" data-id="<?php echo $notificacion['id']; ?>" data-mensaje="<?php echo htmlspecialchars($notificacion['mensaje'], ENT_QUOTES, 'UTF-8'); ?>">Editar</button>
                                <button class="btn btn-danger btn-sm eliminar-btn" data-id="<?php echo $notificacion['id']; ?>">Eliminar</button>
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
    function cargarNotificaciones() {
        $.get("php/notificaciones_crud.php", { action: "listar" }, function(data) {
            $("#notificacionesTable").html(data);
        });
    }

    // Filtrar notificaciones en tiempo real
    $("#busqueda").on("keyup", function() {
        var valor = $(this).val().toLowerCase();
        $(".notificacion-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Crear una nueva notificación
    $("#btnCrear").click(function() {
        Swal.fire({
            title: "Nueva Notificación",
            html: `
                <input type="text" id="usuario" class="swal2-input" placeholder="Usuario ID">
                <textarea id="mensaje" class="swal2-textarea" placeholder="Mensaje"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            preConfirm: () => {
                let usuario_id = $("#usuario").val();
                let mensaje = $("#mensaje").val();
                if (!usuario_id || !mensaje) {
                    Swal.showValidationMessage("Todos los campos son obligatorios");
                    return false;
                }
                return { usuario_id, mensaje };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("php/notificaciones_crud.php", { action: "crear", usuario_id: result.value.usuario_id, mensaje: result.value.mensaje }, function(response) {
                    if (response.success) {
                        Swal.fire("Éxito!", response.message, "success");
                        cargarNotificaciones();
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                }, "json");
            }
        });
    });

    // Editar notificación
    $(document).on("click", ".editar-btn", function() {
        var id = $(this).data("id");
        var mensajeActual = $(this).data("mensaje");

        Swal.fire({
            title: "Editar Notificación",
            input: "textarea",
            inputValue: mensajeActual,
            showCancelButton: true,
            confirmButtonText: "Actualizar",
            preConfirm: (nuevoMensaje) => {
                if (!nuevoMensaje) {
                    Swal.showValidationMessage("El mensaje no puede estar vacío");
                    return false;
                }
                return nuevoMensaje;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("php/notificaciones_crud.php", { action: "editar", id: id, mensaje: result.value }, function(response) {
                    if (response.success) {
                        Swal.fire("Actualizado!", response.message, "success");
                        cargarNotificaciones();
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                }, "json");
            }
        });
    });

    // Eliminar notificación
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
                $.post("php/notificaciones_crud.php", { action: "eliminar", id: id }, function(response) {
                    if (response.success) {
                        Swal.fire("Eliminado!", response.message, "success");
                        cargarNotificaciones(); 
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