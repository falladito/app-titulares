<?php


    try{

        $pdo = new PDO("mysql:host=localhost;dbname=titulares", "root", "root");

        // Set the PDO error mode to exception

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e){

        die("ERROR: No es posible conectar a la db. soporte@desarrollosocial.gob.ar. " . $e->getMessage());

    }

     

    // Attempt search query execution

    try{

        if(isset($_REQUEST['term'])){

            // create prepared statement

            $sql = "SELECT distinct
                    LEFT(programa.mes_alta, 4) as mes_alta_anio,
                    RIGHT(programa.mes_alta, 2) as mes_alta_mes,
                    LEFT(programa.periodo, 4) as periodo_anio,
                    RIGHT(programa.periodo, 2) as periodo_mes,
                    LEFT(programa.ultimo_subsidio, 4) as ultimo_subsidio_anio,
                    RIGHT(programa.ultimo_subsidio, 2) as ultimo_subsidio_mes,
                    programa.mes_alta,
                    programa.aviso,
                    programa.fecha_estado,
                    programa.monto,
                    programa.periodo,
                    programa.programa_id,
                    programa_listado.programa_descripcion,
                    programa.numero_formulario,
                    persona.apellido,
                    persona.fecha_nacimiento,
                    persona.nombres,
                    persona.dni,
                    persona.cuit,
                    programa.ultimo_subsidio,
                    programa_estado.estado,
                    sucursales_bna.sucursal,
                    sucursales_bna.direccion,
                    sucursales_bna.provincia,
                    sucursales_bna.codigo_postal
                            FROM programa JOIN persona 
                                ON programa.id_persona = persona.id_persona
                                JOIN programa_estado
                                ON programa_estado.id = programa.programa_estado
                                JOIN sucursales_bna
                                ON persona.sucursal_bna = sucursales_bna.id
                                JOIN programa_listado
                                ON programa.programa_id = programa_listado.id
                                WHERE persona.dni = :term";

            $stmt = $pdo->prepare($sql);

            $term = $_REQUEST['term'] . '';

            // bind parameters to statement

            $stmt->bindParam(':term', $term);

            // execute the prepared statement

            $stmt->execute();


            if($stmt->rowCount() > 0){ 

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '</br>
                  <div class="col-lg-12">
                <div class="alert alert-success">
                  <div class="row numbers text-center">
                  <div class="col-md-6">
                    <div class="h4 text-success">'.$result[0]['nombres'].' '.$result[0]['apellido'].'</div>
                    <p class="h5">DNI: '.$result[0]['dni'].'</p>
                    <p class="h5">Fecha Nac: '.$result[0]['fecha_nacimiento'].'</p>
                  </div>
                  <div class="col-md-6">
                    <div class="h5">Sucursal: '.$result[0]['sucursal'].'</div>
                    <p class="h5">'.$result[0]['direccion'].'</p>
                    <p class="h5">'.$result[0]['provincia'].' - CP : '.$result[0]['codigo_postal'].'</p>
                    <p class="h5">Mes de alta: '.$result[0]['mes_alta_mes'].'-'.$result[0]['mes_alta_anio'].'</p>
                  </div>
                </div>
                </div>
                </div>';

            echo '<div class="col-lg-12">
            <table class="table">
              <thead>
                <tr>
                  <th>Click para acceder al detalle</th>
                </tr>
              </thead>  <tbody></div>';

                foreach ($result as $result) {




                    if ($result['programa_id'] <> 3) {
                        echo '
                                <!-- Button to trigger modal -->
                                <div id="myModal'.$result['periodo_mes'].'" class="modal fade" tabindex="-1" role="dialog">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">'.$result['programa_descripcion'].'</h4>
                                      </div>
                                      <div class="modal-body">
                                        <h5>Periodo: '.$result['periodo_mes'].'-'.$result['periodo_anio'].'</h5>
                                        <h5>Estado: '.$result['estado'].'</h5>
                                        <h5>Aviso: '.$result['aviso'].'</h5>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <tr>
                                <td><a href="#myModal'.$result['periodo_mes'].'" role="button" class="btn btn-primary btn-block" data-toggle="modal"> Periodo: '.$result['periodo_mes'].'-'.$result['periodo_anio'].'</a></td>
                              </tr>';
                 } else echo '<tr>
                                <!-- Button to trigger modal -->
                                <div id="myModal1" class="modal fade" tabindex="-1" role="dialog">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">'.$result['programa_descripcion'].'</h4>
                                      </div>
                                      <div class="modal-body">
                                        <h5>Número de formulario: '.$result['numero_formulario'].'</h5>
                                        <h5>Estado: '.$result['estado'].'</h5>
                                        <h5>Último subsidio: '.$result['ultimo_subsidio_mes'].'-'.$result['ultimo_subsidio_anio'].'</h5>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <td><a href="#myModal1" role="button" class="btn btn-primary btn-block" data-toggle="modal"> '.$result['programa_descripcion'].'</a></td>
                              </tr>
                              </div>
                              </div>';


                }



            } else{

                echo '</br><div class="col-lg-12"><div class="alert alert-danger">
                      <div class="media">
                        <div class="media-left">
                            <i class="fa fa-arrow-circle-o-right fa-fw fa-4x"></i>
                         </div>
                            <div class="media-body">
                            <h4>Verificar DNI ingresado </h4>
                            <p class="margin-0">Para consultar enviá un <a href="#" target="_blank">mensaje al programa</a></p>
                          </div>
                        </div>
                      </div>
                      </div>';

            }

        }  

    } catch(PDOException $e){

        die("ERROR: Could not able to execute $sql. " . $e->getMessage());

    }

     

    // Close statement

    unset($stmt);


    // Close connection

    unset($pdo);

    ?>

