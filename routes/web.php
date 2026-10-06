<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Instalador\InstaladorController;
use App\Http\Controllers\PublicoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Panel\AjustesController;
use App\Http\Controllers\Panel\BlogArticuloController;
use App\Http\Controllers\Panel\BlogCategoriaController;
use App\Http\Controllers\Panel\BuscadorController;
use App\Http\Controllers\Panel\CalendarioController;
use App\Http\Controllers\Panel\CitaController;
use App\Http\Controllers\Panel\DisponibilidadController;
use App\Http\Controllers\Panel\FaqController;
use App\Http\Controllers\Panel\FraseController;
use App\Http\Controllers\Panel\HistoriaController;
use App\Http\Controllers\Panel\ImagenController;
use App\Http\Controllers\Panel\InfoPublicaController;
use App\Http\Controllers\Panel\InicioController;
use App\Http\Controllers\Panel\NotificacionController;
use App\Http\Controllers\Panel\PacienteController;
use App\Http\Controllers\Panel\PerfilPrivadoController;
use App\Http\Controllers\Panel\ProteccionDatosController;
use App\Http\Controllers\Panel\RedesController;
use App\Http\Controllers\Panel\TemaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicoController::class, 'home'])->name('home');
Route::get('sobre-mi', [PublicoController::class, 'sobreMi'])->name('publico.sobre-mi');
Route::get('servicios', [PublicoController::class, 'servicios'])->name('publico.servicios');
Route::get('preguntas-frecuentes', [PublicoController::class, 'faq'])->name('publico.faq');
Route::get('contacto', [PublicoController::class, 'contacto'])->name('publico.contacto');
Route::get('blog', [PublicoController::class, 'blog'])->name('publico.blog');
Route::get('blog/{articulo:slug}', [PublicoController::class, 'articulo'])->name('publico.articulo');
Route::get('media/web/{clave}', [ImagenController::class, 'mostrar'])->name('imagenes.web');

Route::get('reservas/dias', [ReservaController::class, 'dias'])->name('reservas.dias');
Route::get('reservas/horas', [ReservaController::class, 'horas'])->name('reservas.horas');
Route::post('reservas', [ReservaController::class, 'store'])->name('reservas.store');

Route::middleware('guest')->group(function () {
    Route::get('acceso-psicologa', [LoginController::class, 'mostrar'])->name('acceso.mostrar');
    Route::post('acceso-psicologa', [LoginController::class, 'login'])->name('acceso.login');
});

Route::post('cerrar-sesion', [LoginController::class, 'logout'])->name('acceso.logout')->middleware('auth');

Route::prefix('panel-psicologa')->name('panel.')->middleware('auth')->group(function () {
    Route::get('/', [InicioController::class, 'index'])->name('inicio');

    Route::get('disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.index');
    Route::post('disponibilidad/{modalidad}', [DisponibilidadController::class, 'guardar'])->name('disponibilidad.guardar');
    Route::post('modo-vacaciones', [DisponibilidadController::class, 'modoVacaciones'])->name('disponibilidad.modo-vacaciones');
    Route::post('vacaciones', [DisponibilidadController::class, 'agregarVacaciones'])->name('vacaciones.agregar');
    Route::delete('vacaciones/{periodo}', [DisponibilidadController::class, 'eliminarVacaciones'])->name('vacaciones.eliminar');

    Route::get('calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('calendario/eventos', [CalendarioController::class, 'eventos'])->name('calendario.eventos');

    Route::get('info-publica', [InfoPublicaController::class, 'index'])->name('info-publica.index');
    Route::put('info-publica/perfil', [InfoPublicaController::class, 'guardarPerfil'])->name('info-publica.perfil');
    Route::put('info-publica/servicios', [InfoPublicaController::class, 'guardarServicios'])->name('info-publica.servicios');
    Route::put('info-publica/especialidades', [InfoPublicaController::class, 'guardarEspecialidades'])->name('info-publica.especialidades');
    Route::put('info-publica/planes', [InfoPublicaController::class, 'guardarPlanes'])->name('info-publica.planes');

    Route::post('apariencia', [AjustesController::class, 'guardarApariencia'])->name('apariencia.guardar');
    Route::get('secciones', [AjustesController::class, 'secciones'])->name('secciones.index');
    Route::put('secciones', [AjustesController::class, 'guardarSecciones'])->name('secciones.guardar');

    Route::get('frases', [FraseController::class, 'index'])->name('frases.index');
    Route::put('frases', [FraseController::class, 'guardar'])->name('frases.guardar');

    Route::get('redes', [RedesController::class, 'index'])->name('redes.index');
    Route::put('redes', [RedesController::class, 'guardar'])->name('redes.guardar');

    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::put('notificaciones', [NotificacionController::class, 'guardar'])->name('notificaciones.guardar');
    Route::post('notificaciones/prueba', [NotificacionController::class, 'prueba'])->name('notificaciones.prueba');

    Route::get('mi-perfil', [PerfilPrivadoController::class, 'edit'])->name('perfil.edit');
    Route::put('mi-perfil', [PerfilPrivadoController::class, 'update'])->name('perfil.update');

    Route::get('buscar', [BuscadorController::class, 'index'])->name('buscar');
    Route::view('ayuda', 'panel.ayuda')->name('ayuda');

    Route::get('imagenes', [ImagenController::class, 'index'])->name('imagenes.index');
    Route::post('imagenes/{clave}', [ImagenController::class, 'actualizar'])->name('imagenes.actualizar');
    Route::delete('imagenes/{clave}', [ImagenController::class, 'restablecer'])->name('imagenes.restablecer');

    Route::get('temas', [TemaController::class, 'index'])->name('temas.index');
    Route::post('temas/activar', [TemaController::class, 'activar'])->name('temas.activar');
    Route::get('temas/preview/{tema}', [TemaController::class, 'preview'])->name('temas.preview');

    Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('faq/crear', [FaqController::class, 'create'])->name('faq.create');
    Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
    Route::get('faq/{faq}/editar', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
    Route::patch('faq/{faq}/toggle', [FaqController::class, 'toggle'])->name('faq.toggle');
    Route::delete('faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');

    Route::get('proteccion-datos', [ProteccionDatosController::class, 'index'])->name('proteccion.index');
    Route::put('proteccion-datos', [ProteccionDatosController::class, 'guardar'])->name('proteccion.guardar');
    Route::get('proteccion-datos/pdf-vacio', [ProteccionDatosController::class, 'pdfVacio'])->name('proteccion.pdf-vacio');

    Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('pacientes/crear', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::get('pacientes/buscar', [PacienteController::class, 'buscar'])->name('pacientes.buscar');
    Route::post('pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('pacientes/{paciente}/proteccion-datos', [ProteccionDatosController::class, 'pdfPaciente'])->name('pacientes.proteccion-pdf');
    Route::get('pacientes/{paciente}/historia', [HistoriaController::class, 'index'])->name('pacientes.historia');
    Route::post('pacientes/{paciente}/historia', [HistoriaController::class, 'store'])->name('pacientes.historia.store');
    Route::get('historias', [HistoriaController::class, 'indice'])->name('historias.index');
    Route::get('historias/adjuntos/{adjunto}', [HistoriaController::class, 'verAdjunto'])->name('historias.adjunto.ver');
    Route::delete('historias/adjuntos/{adjunto}', [HistoriaController::class, 'eliminarAdjunto'])->name('historias.adjunto.eliminar');
    Route::get('historias/{entrada}/editar', [HistoriaController::class, 'edit'])->name('historias.edit');
    Route::put('historias/{entrada}', [HistoriaController::class, 'update'])->name('historias.update');
    Route::delete('historias/{entrada}', [HistoriaController::class, 'destroy'])->name('historias.destroy');

    Route::get('pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('pacientes/{paciente}/editar', [PacienteController::class, 'edit'])->name('pacientes.edit');
    Route::put('pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::delete('pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

    Route::get('citas', [CitaController::class, 'index'])->name('citas.index');
    Route::get('citas/crear', [CitaController::class, 'create'])->name('citas.create');
    Route::get('citas/huecos', [CitaController::class, 'huecos'])->name('citas.huecos');
    Route::post('citas', [CitaController::class, 'store'])->name('citas.store');
    Route::get('citas/{cita}/editar', [CitaController::class, 'edit'])->name('citas.edit');
    Route::put('citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
    Route::delete('citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogArticuloController::class, 'index'])->name('articulos.index');
        Route::get('articulos/crear', [BlogArticuloController::class, 'create'])->name('articulos.create');
        Route::post('articulos', [BlogArticuloController::class, 'store'])->name('articulos.store');
        Route::get('articulos/{articulo}/editar', [BlogArticuloController::class, 'edit'])->name('articulos.edit');
        Route::put('articulos/{articulo}', [BlogArticuloController::class, 'update'])->name('articulos.update');
        Route::delete('articulos/{articulo}', [BlogArticuloController::class, 'destroy'])->name('articulos.destroy');

        Route::get('categorias', [BlogCategoriaController::class, 'index'])->name('categorias.index');
        Route::post('categorias', [BlogCategoriaController::class, 'store'])->name('categorias.store');
        Route::put('categorias/{categoria}', [BlogCategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('categorias/{categoria}', [BlogCategoriaController::class, 'destroy'])->name('categorias.destroy');
    });

    Route::get('proximamente/{seccion?}', function (?string $seccion = null) {
        return view('panel.proximamente', ['seccion' => $seccion]);
    })->name('proximamente');
});

Route::prefix('instalacion')->name('instalacion.')->group(function () {
    Route::get('/', [InstaladorController::class, 'inicio'])->name('inicio');

    Route::get('base-datos', [InstaladorController::class, 'baseDatos'])->name('base-datos');
    Route::post('base-datos', [InstaladorController::class, 'guardarBaseDatos']);

    Route::get('cuenta', [InstaladorController::class, 'cuenta'])->name('cuenta');
    Route::post('cuenta', [InstaladorController::class, 'guardarCuenta']);

    Route::get('perfil', [InstaladorController::class, 'perfil'])->name('perfil');
    Route::post('perfil', [InstaladorController::class, 'guardarPerfil']);

    Route::get('servicios', [InstaladorController::class, 'servicios'])->name('servicios');
    Route::post('servicios', [InstaladorController::class, 'guardarServicios']);

    Route::get('apariencia', [InstaladorController::class, 'apariencia'])->name('apariencia');
    Route::post('finalizar', [InstaladorController::class, 'finalizar'])->name('finalizar');
});
