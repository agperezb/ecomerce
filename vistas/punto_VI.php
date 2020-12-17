<?php
class punto_VI
{
    function __construct()
    {
        
    }

    function listar(){
        require_once "modelos/usuarios_MO.php";

        $conexion = new conexion('A');
        $usuarios_MO = new usuarios_MO($conexion);

        $arreglo_puntos = $usuarios_MO->listarPuntos();
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de puntos de ventas en el sistema</h3>
                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarPuntos()"><i class="far fa-plus-square"></i> Agregar</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="listar_puntos" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Nit</th>
                            <th>Direccion</th>
                            <th>usuario</th>
                            <th>rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($arreglo_puntos) {
                            foreach ($arreglo_puntos as $objeto_punto) {
                                $id_usuario = $objeto_punto->id_usuario;
                                $nombre = $objeto_punto->nombre;
                                $nit = $objeto_punto->nit;
                                $direccion = $objeto_punto->direccion;
                                $usuario = $objeto_punto->usuario;
                                $rol = $objeto_punto->nombre_rol;

                        ?>
                                <tr>
                                    <td><?php echo $nombre; ?></td>
                                    <td><?php echo $nit; ?></td>
                                    <td><?php echo $direccion; ?></td>
                                    <td><?php echo $usuario; ?></td>
                                    <td><?php echo $rol; ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nombre</th>
                            <th>Nit</th>
                            <th>Direccion</th>
                            <th>usuario</th>
                            <th>rol</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <script>
            function vistaAgregarPuntos() {
                $.post('index.php',{modulo:'punto',accion:'VISTA_AGREGAR_PUNTOS'},function(respuesta)
            {
                $('#titulo_modal').html('Agregar puntos de venta');
                $('#contenido_modal').html(respuesta);
            });
            }

            data_table_puntos = organizarTabla({
                id: "listar_puntos"
            });
        </script>
<?php
    }

    function agregar(){
?>
    <div class="card">
            <div class="card-body">
                <form id="formulario_agregar_puntos">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nit">Nit:</label>
                                <input type="text" class="form-control" id="nit" name="nit">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Direccion:</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario">Usuario:</label>
                                <input type="text" class="form-control" id="usuario" name="usuario">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contrasena">Contraseña:</label>
                                <input type="password" class="form-control" id="contrasena" name="contrasena">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="modulo" name="modulo" value="punto">
                    <input type="hidden" id="accion" name="accion" value="CONTROLADOR_AGREGAR_PUNTOS">
                    <button type="button" class="btn btn-primary float-right" onclick="controladorAgregarPuntos()"><i class="fas fa-save"></i> Guardar</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <script>
            function controladorAgregarPuntos() {
                var cadena = $('#formulario_agregar_puntos').serialize();

                $.post('index.php', cadena, function(respuesta) {
                    var objeto_respuesta = JSON.parse(respuesta);

                    if (objeto_respuesta.estado == "EXITO") {
                        $('#formulario_agregar_puntos')[0].reset();

                        exito(objeto_respuesta.mensaje);

                        data_table_puntos.row.add([objeto_respuesta.nombre, objeto_respuesta.nit, objeto_respuesta.direccion, objeto_respuesta.usuario, objeto_respuesta.nombre_rol]).draw();

                    } else if (objeto_respuesta.estado == "ADVERTENCIA") {
                        advertencia(objeto_respuesta.mensaje);
                    } else if (objeto_respuesta.estado == "ERROR") {
                        error(objeto_respuesta.mensaje);
                    }
                });
            }
        </script>

<?php
    }
}
?>