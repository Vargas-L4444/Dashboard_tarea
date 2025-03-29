<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Panel de Control - Cursos">
    <meta name="author" content="Admin">
    <link rel="icon" href="/Dashboard_tarea/img/EL_User2.png"> <!-- Favicon -->
    <title>Panel de Control</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<?php 
require_once "vistas/parte_superior.php"; 
require_once "php/config.php"; 
require_once "php/Curso_PanelControl_Crud.php";

// Crear la instancia de la clase y obtener los valores
$crud = new Curso_PanelControl_Crud($pdo);
$cursosActivos = $crud->obtenerCursosActivos();
$estudiantesInscritos = $crud->obtenerEstudiantesInscritos();
$clasesProgramadas = $crud->obtenerClasesProgramadas();
$mensajesNoLeidos = $crud->obtenerMensajesNoLeidos();
?>

<!-- Contenido del Panel de Control -->
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Control</h1>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <!-- Cursos Activos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Cursos Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $cursosActivos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estudiantes Inscritos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Estudiantes Inscritos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $estudiantesInscritos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clases Programadas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Clases Programadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $clasesProgramadas; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes No Leídos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Mensajes No Leídos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $mensajesNoLeidos; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Resumen -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen General</h6>
                </div>
                <div class="card-body">
                    <p>Bienvenido al Panel de Control del módulo de cursos. Aquí puedes ver un resumen de la actividad reciente, estadísticas clave y accesos rápidos a las funciones más importantes.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "vistas/parte_inferior.php"; ?>