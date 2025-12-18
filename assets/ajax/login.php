<?php 

require_once("../../config/config.php");
require_once("../../helpers/helpers.php"); 
require_once("../../libraries/conexion.php"); 
session_start();

$operacion = $_POST['action'];


switch ($operacion) 
{
	case 'valida_login':
	
	$usuario = $_POST['usuario'];
	$clave   = $_POST['clave'];
	$usuario = trim($usuario);
	$clave = trim($clave);
	$ruc   = trim($_POST['ruc']);

	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql="SELECT * FROM vw_tbl_usuarios WHERE usuario= :user AND clave= :pass AND ruc=:ruc";
	$resultado=$connect->prepare($sql);
	// Sanitizar entradas
	$ruc = trim($ruc);
	$usuario = trim($usuario);
	$clave = md5(trim($clave)); // Hash MD5 para la clave
	$resultado->bindValue(":user",$usuario);
	$resultado->bindValue(":pass",$clave);
	$resultado->bindValue(":ruc",$ruc);
	$resultado->execute();
	$num_reg=$resultado->rowCount();
	
	//echo $num_reg;
	$row_resultado=$resultado->fetch(PDO::FETCH_ASSOC);

//	var_dump($row_resultado);


	if($num_reg!=0 && $row_resultado !== false)
	{
		$hoy  = date('Y-m-d');
		$fechaven = $row_resultado['fecha_vencimiento'];
		
		if( $fechaven <= $hoy)
		{
			$response = array(
             "status"  => 'licencia_vencida'
            
			);

			// Liberar recursos
	        $resultado->closeCursor();

			echo json_encode($response);
			session_destroy();
		}
		else
		{
			//var_dump($row_resultado);
		     $_SESSION['iniciarSesion']          ="cinema";
	            
	            $_SESSION["id"]                     =$row_resultado['id'];
	            $_SESSION["id_empresa"]             =$row_resultado['id_empresa'];
	            $_SESSION["empresa"]                =$row_resultado['razon'];
	            $_SESSION["ruc"]                    =$row_resultado['ruc'];
	            $_SESSION["fecha_vencimiento"]      =$row_resultado['fecha_vencimiento'];
	            $_SESSION["local"]      =$row_resultado['local'];
	            
	            
	            

	            $response = array(
	             "status"  => 'ok'
				);
			echo json_encode($response);

			// Liberar recursos
			$resultado->closeCursor();

			exit();


		}


	}
	else
	{
      $response = array(
             "status"  => 'error'
			);
            session_destroy();
			echo json_encode($response);
	}







	break;

	
}



?>