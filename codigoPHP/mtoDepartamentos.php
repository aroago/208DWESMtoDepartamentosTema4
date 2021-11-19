<!DOCTYPE html>
<!--Aroa Granero Omañas 
Fecha Creacion: 17/11/2021
Fecha Modificacion: 18/11/2021 -->
<html>
    <head>
        <meta charset="UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="aroaGraneroOmañas">
        <meta name="application-name" content="Sitio web de DAW2">
        <meta name="description" content="Inicio de la pagina web MtoDepartamento.php">
        <meta name="keywords" content=" index" >
        <meta name="robots" content="index, follow" />
        <link href="../webroot/css/estilos.css"  rel="stylesheet"  type="text/css" title="Default style">
        <link rel="shortcut icon" href="favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AroaGO</title>
        <title>ejercicio 09</title>
        <style>
            table{
                margin-left: auto;
                margin-right: auto;
                width: 70%;
                font-size: 15px;
                margin-top: 10px;

            }
            td,tr{
                border: solid 3px cadetblue;
            }
            h3{
                color:blue;
            }

            form{
                text-align: center;
                background-color: rgba(129, 98, 91,0.9);
                border-radius: 5px;
            }
            #btnEnviar{
                width: 12%;
                height: 36px;
                background-image: linear-gradient( 
                    90deg, #b1a8a6 0%, #35d4c5 49%, #88b6b0 80%, #a3aca9 100%);
                border-radius: 6px;
                align-items: center;
                justify-content: center;
                font-size: 27px;
                font-weight: bold;
            }
            .formBox input{
                width: 24%;
                height: 33px;
                margin: 2%;
            }

            label{
                font-size: 22px;
                color: white;

            }
            img{
                line-height: 10px;
                width: 30px;
                height: 30px;
            }
        </style>
    </head>
    <body>
        <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <fieldset>
                <legend>Buscar Departamento</legend>
                <div class="formBox">
                    <label for="DescDepartamento">Descripcion del Departamento</label>
                    <input style="background-color:#CCF8F4;" type="text" id="DescDepartamento" name="DescDepartamento" placeholder="Introduzca Descripcion del Departamento" value="<?php
                    echo (isset($_REQUEST['DescDepartamento'])) ? $_REQUEST['DescDepartamento'] : ""; // si el campo esta correcto mantengo su valor en el formulario
                    ?>">     
                    <input id="btnEnviar" type="submit" value="Buscar" name="Enviar">
                </div> 
            </fieldset>
        </form>
        <?php
        /*
         * @author: Aroa Granero Omañas
         * @version: v1
         * Created on: 17/11/2021
         * Last modification: 17/11/2021
         */
        require_once '../core/210322ValidacionFormularios.php'; // incluyo la libreria de validacion para validar los campos de formulario
        require_once '../config/confDBPDO.php';

        //Inicializa una variable que nos ayudará a controlar si todo esta correcto
        $entradaOK = true;

        //Inicializa un array que se encargará de recoger los datos del formulario
        $aFormulario = [
            'DescDepartamento' => null,
        ];
        try {

            $mydb = new PDO(HOST, USER, PASSWORD); //Establecer una conexión con la base de datos 
            $mydb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['Enviar'])) {                                      //Cuando se pulsa el boton de buscar
                $aFormulario['DescDepartamento'] = $_REQUEST['DescDepartamento']; //Guardamos en la variable lo que se ha introducido en el formulario
                $DescDepartamento = $aFormulario['DescDepartamento'];

                $consulta = "SELECT * FROM Departamento WHERE DescDepartamento LIKE '%$DescDepartamento%'"; //Guardamos en la variable la consulta que queremos hacer
                $resultadoConsulta = $mydb->prepare($consulta); //Preparamos la consulta
                $resultadoConsulta->execute();
                if ($resultadoConsulta->rowCount() == 0) {
                    echo "No se ha encontrado ningún departamento con esa descripción";
                } else
                    
                    ?>
                <!--mostrar tabla-->
                <table>
                    <tr>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>Fecha Baja</th>
                        <th>Volumen de Negocio</th>
                    </tr>
                    <?php
                    //Al realizar el fetchObject, se pueden sacar los datos de $registro como si fuera un objeto
                    while ($registro = $resultadoConsulta->fetchObject()) {
                        echo "<tr>";
                        echo "<td>$registro->CodDepartamento</td>";
                        echo "<td>$registro->DescDepartamento</td>";
                        echo "<td>$registro->FechaBaja</td>";
                        echo "<td>$registro->VolumenNegocio</td>";
                        echo '<td class="celdaIcono"><a href="vMtoDepartamentosEditar.php?CodDepartamentoEnCurso=<?php echo urlencode($registro["CodDepartamento"]);?><img src="../webroot/img/editar2.png"></a></td>';
                        echo "<td class='celdaIcono'><a href='#'><img src='../webroot/img/eliminar2.png'></a></td>";
                        echo "<td class='celdaIcono'><a href='#'><img src='../webroot/img/ver2.png'></a></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
                <?php
            }
        } catch (PDOException $miExceptionPDO) {       //Si no se ha podido ejecutar saltara la excepcion
            echo "<h3>Mensaje de ERROR</h3>";
            //Mensaje de salida
            echo "Error: " . $miExceptionPDO->getMessage() . "<br>";
            //Código del error
            echo "Código de error: " . $miExceptionPDO->getCode();
        } finally {
            //Cerramos la conexion
            unset($mydb);
        }
        ?>

        <footer id="footerP">
            <p>&copy;2021 Todos los derechos reservados AroaGO</p>
            <div id="iconos">
                <a type="application/github" href="https://github.com/aroago/208DWESMtoDepartamentosTema4.git" target="_blank">
                    <img class="iconoIMG" alt="gitHub" src="./webroot/img/github.png" />
                </a>
            </div>
        </footer>
    </body>
</html>
