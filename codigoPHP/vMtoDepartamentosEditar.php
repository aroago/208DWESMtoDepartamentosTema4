<?php
if (isset($_REQUEST["Cancelar"])) {
    header('Location: mtoDepartamentos.php');
    exit;
}
require_once '../core/210322ValidacionFormularios.php'; // incluyo la libreria de validacion

define("OBLIGATORIO", 1); // defino e inicializo la constante a 1


$entradaOK = true; // declaro la variable que determiná si esta bien la entrada de los campos

$aErrores = [//declaro e inicializo el array de errores
    'DescDepartamento' => '',
    'VolumenNegocio' => '',
];

$aFormulario = [// declaro e inicializo el array de los campos del formulario
    'CodDepartamento' => $_REQUEST['CodDepartamentoEnCurso'],
    'DescDepartamento' => '',
    'FechaBaja' => '',
    'VolumenNegocio' => '',
];

if (isset($_REQUEST["Aceptar"])) { // compruebo que el usuario le ha dado a enviar
    $aErrores['DescDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['DescDepartamento'], MAX_TAMANYO_ALFABETICO, MIN_TAMANYO_ALFABETICO, OBLIGATORIO); // valido que el nombre esta bien y que la ha introducido
    $aErrores['VolumenNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['VolumenNegocio'], PHP_FLOAT_MAX, -PHP_FLOAT_MAX, OBLIGATORIO);

    foreach ($aErrores as $campo => $error) { // reocrro el array de errores
        if ($error != null) { // compruebo si hay algun elemento distinto de null
            $entradaOK = false; // le doy el valor false a $entradaOK
        }
    }
} else { // si el usuario no le ha dado al boton de enviar
    $entradaOK = false; // le doy el valor false a $entradaOK           
}

if ($entradaOK) { // si la entrada esta bien
    try {
        //Establecimiento de la conexión 
        $mydb = new PDO(HOST, USER, PASSWORD);
        $mydb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($entradaOK) {
            //Preparación de la consulta
            $resultadoConsulta = $mydb->prepare(<<<QUERY
                                UPDATE Departamento
                                SET DescDepartamento = :descDepartamento, VolumenNegocio = :volumenNegocio
                                WHERE CodDepartamento = :codDepartamento
                        QUERY);
            //Asignación de las respuestas en los parámetros de las consultas preparadas
            $aColumnas = [
                ':codDepartamento' => $_REQUEST['CodDepartamento'],
                ':descDepartamento' => $_REQUEST['DescDepartamento'],
                ':volumenNegocio' => $_REQUEST['VolumenNegocio']
            ];
            //Ejecución de la consulta de actualización
            $resultadoConsulta->execute($aColumnas);
            header('Location: MtoDepartamentos.php');
        } else {
            //Preparación y ejecución de las consultas creadas en la condición
            $resultadoConsulta = $mydb->prepare(<<<QUERY
                                    SELECT * FROM Departamento
                                    WHERE CodDepartamento = :codDepartamento
                            QUERY);
            $aColumnas = [
                ':codDepartamento' => $_REQUEST['CodDepartamentoEnCurso']
            ];
            $resultadoConsulta->execute($aColumnas);
            //Carga del registro en una variable
            $registroObjeto = $resultadoConsulta->fetch(PDO::FETCH_OBJ);

            $aValores = [];
            //Recorrido del registro
            foreach ($registroObjeto as $clave => $valor) {
                $aValores[$clave] = $valor;
            }
        }

        header('Location: mtoDepartamentos.php');
        exit;
    } catch (PDOException $miExceptionPDO) {       //Si no se ha podido ejecutar saltara la excepcion
        echo "<h3>Mensaje de ERROR</h3>";
        //Mensaje de salida
        echo "Error: " . $miExceptionPDO->getMessage() . "<br>";
        //Código del error
        echo "Código de error: " . $miExceptionPDO->getCode();
    }
}
?> 
<!DOCTYPE html>
<!--Aroa Granero Omañas 
Fecha Creacion: 18/11/2021
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

        </style>
    </head>
    <body>
        <form name="formulario" action="mtoDepartamentos.php" method="post">
            <fieldset>
                <legend>Editar Departamento</legend>
                <table>
                    <tr>
                        <td><label for="CodDepartamento">Código Departamento:</label></td>
                        <td><input id="CodDepartamento" type="text" name="CodDepartamento" value="<?php echo (isset($_REQUEST['CodDepartamento'])) ? $_REQUEST['CodDepartamento'] : ""; ?>" disabled></td>
                    </tr>
                    <tr>
                        <td><label for="DescDepartamento">Descripción:</label></td>
                        <td><input id="DescDepartamento" type="text" name="DescDepartamento"  value="<?php echo (isset($_REQUEST['DescDepartamento'])) ? $_REQUEST['DescDepartamento'] : ""; ?>" ></td>
                        <td> <?php
                            if (!is_null($aErrores['DescDepartamento'])) { //compruebo si ha introducido mal el nombre
                                echo "<span>" . $aErrores['DescDepartamento'] . "</span>"; // muestro el error en el nombre
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="FechaBaja">Fecha de baja:</label></td>
                        <td><input id="FechaBaja" type="text" name="FechaBaja"  value="<?php echo (isset($_REQUEST['FechaBaja'])) ? $_REQUEST['FechaBaja'] : ""; ?>" disabled></td>
                    </tr>
                    <tr>
                        <td><label for="VolumenNegocio">Volumen de negocio:</label></td>
                        <td><input id="VolumenNegocio" type="text" name="VolumenNegocio" value="<?php echo (isset($_REQUEST['VolumenNegocio'])) ? $_REQUEST['VolumenNegocio'] : ""; ?>" ></td>
                        <td> <?php
                            if (!is_null($aErrores['VolumenNegocio'])) { //compruebo si ha introducido mal el nombre
                                echo "<span>" . $aErrores['VolumenNegocio'] . "</span>"; // muestro el error en el nombre
                            }
                            ?>
                        </td>
                    </tr>

                </table>
                <input id="Aceptar" type="button" name="Editar" value="Aceptar">
                <input id="Cancelar" type="button" name="Cancelar" value="Cancelar">

            </fieldset>
        </form>

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
