<?php
class peticiones_VI
{
    function __construct()
    {
    }

    function listar()
    {
        require_once "modelos/peticiones_MO.php";

        $conexion = new conexion('A');
        $peticiones_MO = new peticiones_MO($conexion);

        $arreglo_peticiones = $peticiones_MO->listarPeticiones();
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de peticiones en el sistema</h3>
                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#ventana_modal" onclick="vistaAgregarPeticiones()"><i class="far fa-plus-square"></i> Agregar</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="listar_peticiones" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Monto a Pagar</th>
                            <th>Forma de pago</th>
                            <th>Comision</th>
                            <th>Franquicia</th>
                            <th>Token</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($arreglo_peticiones) {
                            foreach ($arreglo_peticiones as $objeto_peticion) {

                                $id_pago = $objeto_peticion->id_pago;
                                $descripcion = $objeto_peticion->descripcion;
                                $monto_pagar = $objeto_peticion->monto_pagar;
                                $forma_pago = $objeto_peticion->forma_pago;
                                $comision = $objeto_peticion->comision;
                                $franquicia = $objeto_peticion->franquicia;
                                $token= $objeto_peticion->token;
                                $estado = $objeto_peticion->estado;

                        ?>
                                <tr>
                                    <td><?php echo $descripcion; ?></td>
                                    <td><?php echo $monto_pagar; ?></td>
                                    <td><?php echo $forma_pago; ?></td>
                                    <td><?php echo $comision; ?></td>
                                    <td><?php echo $franquicia; ?></td>
                                    <td><?php echo $token; ?></td>
                                    <td><?php echo $estado; ?></td>
                                    <td><a href="pagos_VI.php?token=<?php echo $token; ?>">Link</a></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <<th>Descripcion</th>
                            <th>Monto a Pagar</th>
                            <th>Forma de pago</th>
                            <th>Comision</th>
                            <th>Franquicia</th>
                            <th>Token</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <script>
            function vistaAgregarPeticiones() {
                $.post('index.php', {
                    modulo: 'peticion',
                    accion: 'VISTA_AGREGAR_PETICION'
                }, function(respuesta) {
                    $('#titulo_modal').html('Agregar peticiones');
                    $('#contenido_modal').html(respuesta);
                });
            }

            data_table_peticiones = organizarTabla({
                id: "listar_peticiones"
            });
        </script>
    <?php
    }

    function agregar()
    {
        require_once "modelos/peticiones_MO.php";

        $conexion = new conexion('A');
        $peticiones_MO = new peticiones_MO($conexion);

        $arreglo_comercios = $peticiones_MO->seleccionarComercios();
    ?>
        <div class="card">
            <div class="card-body">
                <form id="formulario_agregar_pagos">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="descripcion">Descripcion:</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monto_pago">Monto a pagar:</label>
                                <input type="text" class="form-control" id="monto_pago" name="monto_pago">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comercio">Comercio:</label>
                                <select id="comercio" name="comercio" required="" class="form-control">
                                    <option>Seleccione</option>
                                    <?php
                                    if ($arreglo_comercios) {
                                        foreach ($arreglo_comercios as $objeto_comercio) {
                                    ?>
                                            <option value="<?php echo $objeto_comercio->id_comercio; ?>"><?php echo $objeto_comercio->nombre; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="forma_pago">Forma de pago;</label>
                                <select id="forma_pago" name="forma_pago" class="form-control" onchange="mostrar()">
                                    <option selected>Seleccione</option>
                                    <option value="efecty">Efecty</option>
                                    <option value="targeta">Targeta</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="comisiones" style="display: none;">
                                <label for="comision">Comision:</label>
                                <input type="text" class="form-control" id="comision" name="comision">
                            </div>
                            <div class="form-group" id="franquicias" style="display: none;">
                                <label for="franquicia">Franquicia;</label>
                                <select id="franquicia" name="franquicia" class="form-control">
                                    <option value="VISA">Visa</option>
                                    <option value="Mastercard">MasterCard</option>
                                    <option value="American">American</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <input type="hidden" id="modulo" name="modulo" value="peticion">
                    <input type="hidden" id="accion" name="accion" value="CONTROLADOR_AGREGAR_PETICION">
                    <button type="button" class="btn btn-primary float-right" onclick="controladorAgregarPeticion()"><i class="fas fa-save"></i> Guardar</button>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <script>
            function mostrar() {
                var selector = document.getElementById('forma_pago').value;
                var comision = document.getElementById('comisiones');
                var franquicia = document.getElementById('franquicias');
                if(selector == "efecty") {
                    comision.style.display="block";
                    franquicia.style.display="none";
                }else if(selector == "targeta"){
                    franquicia.style.display="block";
                    comision.style.display="none";
                }
            }

            function controladorAgregarPeticion() {
                var cadena = $('#formulario_agregar_pagos').serialize();

                $.post('index.php', cadena, function(respuesta) {
                    var objeto_respuesta = JSON.parse(respuesta);

                    if (objeto_respuesta.estado == "EXITO") {
                        $('#formulario_agregar_puntos')[0].reset();

                        exito(objeto_respuesta.mensaje);

                        data_table_peticiones.row.add([objeto_respuesta.descripcion, objeto_respuesta.monto_pago, objeto_respuesta.forma_pago, objeto_respuesta.comision, objeto_respuesta.franquicia,objeto_respuesta.token,objeto_respuesta.estado]).draw();

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