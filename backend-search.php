<?php


    try{

        $pdo = new PDO("mysql:host=;dbname=", "", "");

        // Set the PDO error mode to exception

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e){

        die("ERROR: No es posible conectar a la db. soporte@desarrollosocial.gob.ar. " . $e->getMessage());

    }

     

    // Attempt search query execution

    try{

        if(isset($_REQUEST['term'])){

            // create prepared statement

            $sql = "SELECT * FROM t_monotributosocial WHERE dni = :term";

            $stmt = $pdo->prepare($sql);

            $term = $_REQUEST['term'] . '';

            // bind parameters to statement

            $stmt->bindParam(':term', $term);

            // execute the prepared statement

            $stmt->execute();

            if($stmt->rowCount() > 0){

                while($row = $stmt->fetch()){


                    echo '</br><div class="col-lg-12">

<div class="panel panel-default panel-icon panel-primary margin-40" href="#">
                    <div class="panel-heading"><i class="fa fa-check-square-o"></i></div>
                    <div class="panel-body">
                      <h2>'. $row['nombre'] . ' ' . $row['apellido'] .'</h2>
                      <p class="h3"><strong>'. $row['dni'] . '</strong></p>
                      <p class="h3"><strong> Fecha nac:'. $row['fechaNac'] . '</strong></p>
                      <hr>
                      <p class="h3"><strong> Fecha de cobro: 06/04/2018</strong></p>
                      <p class="h3"><strong> Sucursal: San telmo</strong></p>
                      <p class="h3"><strong> Tipo de Persona: Asociado</strong></p>
                      <p class="h3"><strong> Estado: Efector</strong></p>
                      <p class="h3"><strong> Fecha último cobro: 2018-05</strong></p>

                    </div>
                    <div class="panel-footer">
                      <div class="alert alert-info">
    <div class="media">
        <div class="media-left">
            <i class="fa fa-phone fa-fw fa-4x"></i>
        </div>
        <div class="media-body">
            <h4><a href="">Línea gratuíta 0800-999-0209 </a></h4>
            <p class="margin-0">Servicio de Orientación y Asesoramiento</p>
        </div>
    </div>
</div>
                    </div>
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
</div></div>';

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

