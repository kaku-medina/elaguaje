<?php
// Codigo de validación de inicio de sesión 

session_start(); //se inicia la sesión o se reinicia
if (!empty($_SESSION['active'])) { //comprueba que nuestra array session este activa 
    header('location: src/'); // en caso de ser cierta la comparación nos mostrará el inicio de nuestra app
} else {
    if (!empty($_POST)) {  //comprueba de que el llamado desde el post no este vacio
        $alert = '';   //crea una variable para mandar un mensaje 
        if (empty($_POST['usuario']) || empty($_POST['clave'])) { //comprueba que los espacios no esten vacios 
            // se crea una etiqueta la cual se guarda en nuestra variable para mostrar un mensaje 
            $alert = '<div class="alert alert-danger" role="alert"> 
            Ingrese su usuario y su contraseña
            </div>';
            
        } else {
            require_once "conexion.php"; //extrae el archivo de conexión 
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']); //guarda en una variable los datos extraidos por el metodo POST 
            $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$clave' AND estado = 1"); // realiza la consulta y comparación de los datos extraidos con los datos guardados en la BDD
            mysqli_close($conexion); //cierra la conexion para evitar que el servidor se sobre sature
            $resultado = mysqli_num_rows($query); // guarda en una variable la cantidad de resultados que se encontraron
            if ($resultado > 0) { 
                $dato = mysqli_fetch_array($query);//se utiliza para obtener los datos de la consulta 
                $_SESSION['active'] = true;  //nos permite tener nuestra sesion activa 
                //nos permite extraer los datos del usuario los cuales se pueden utilizar de manera rapida 
                $_SESSION['idUser'] = $dato['idusuario'];
                $_SESSION['nombre'] = $dato['nombre'];
                $_SESSION['user'] = $dato['usuario'];
                header('location: src/');//nos envia al inicio de nuestra app
            } else {
                //nos manda un mensaje cuando la sesion no esta activa y la elimina para no poder acceder a la app 
                $alert = '<div class="alert alert-danger" role="alert">
                Usuario o Contraseña Incorrecta
                </div>';
                session_destroy();
            }
        }
    }
}
?>
<!-- Código para interfaz -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>El Aguaje del Moro</title>
    <link href="assets/css/styles2.css" rel="stylesheet" />
    <script src="assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary";>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header text-center">
                                    <img class="img-thumbnail" src="assets/img/logo.png" width="100">
                                    <h3 class="font-weight-light my-4">Iniciar Sesión</h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label class="small mb-1" for="usuario"><i class="fas fa-user"></i> Usuario</label>
                                            <input class="form-control py-4" id="usuario" name="usuario" type="text" placeholder="Ingrese usuario" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="clave"><i class="fas fa-key"></i> Contraseña</label>
                                            <input class="form-control py-4" id="clave" name="clave" type="password" placeholder="Ingrese Contraseña" required />
                                        </div>
                                        <div class="alert alert-danger text-center d-none" id="alerta" role="alert">

                                        </div>
                                        <?php echo isset($alert) ? $alert : ''; ?>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Iniciar Sesión </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
    <script src="assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>