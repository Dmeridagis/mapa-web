<?php
//MONITORES * *
//*PAGINA DE CONEXIÓN
include 'config.php'; //CREDECIALES A LA BD 
$cnx=""; //Variable global -vacia que se reutiliza para conectar, desconectar <etc 


//*PAGINAS DE FUNCIONES --CRUD
function conectar(){
    global $cnx; 
	$cadena="host=".HOST." port=".PORT." dbname=".BASE." user=".USER." password=".PASS." options='-c client_encoding=utf8'"; //cadena de conexion- varia segun MOTOR DE BD-- options-> ecoding importante por la ñ etc..
    $cnx=pg_connect($cadena); // 1)pg_conect -> varia segun la Bd . CONECTAR A LA BD
}
function desconectar(){ //CIERRA LA BD
    global $cnx;
    pg_close($cnx);
}

//CONSULTA
function consultar($sql=''){ //ingresa la variable en blanco
    global $cnx;
    conectar(); //llamo a la funcion conectar-- ingreso a la BD
    $bolsa=pg_query($cnx,$sql); //BOLSA es una variable en blanco que recibe la conexion con la consulta que le paso. 
    if(pg_num_rows($bolsa)>0){ //recorrido para saber cuantos registros hay en bolsa
            while($f=pg_fetch_assoc($bolsa)){ //2)devuelve dato asociativos. campos y su valor y llena el campo
                $datos[]=$f; //llena este campo
            }
        }else{
            $datos=array(); //sin registro -
        }
	pg_free_result($bolsa); // deja en blanco
        unset($bolsa);
        unset($f); // deja f en blanco
	desconectar();
    //return $salida;
	return $datos; // retorna objeto
}

// el proceso anterior recogo los datos de la BD y trabajo con ellos. Es importante descontar por si otro usuario quiere modificar la Bd en el momento



function consultaAsociado($sql=''){ // ingresa y salenn datos
    global $cnx;
    conectar();
    $bolsa=pg_query($cnx,$sql);
    if(pg_num_rows($bolsa)>0){
            while($f=pg_fetch_assoc($bolsa)){
                $datos[]=$f;
            }
        }else{
            $datos=array();
        }
	pg_free_result($bolsa);
        unset($bolsa);
        unset($f);
	desconectar();
    //return $salida;
	return json_decode(json_encode($datos),false); // objeto
}

// aca arma el dato el json
function consultaGeojson($sql=''){ // ingresa consulta sql
    global $cnx; //se conecta ala BD
    conectar();
	$datos=[]; //ARRAY EN BLANCO
    $bolsa=pg_query($cnx,$sql); //BOLSA RECUPERA LOS DATOS DE LA BD
    if(pg_num_rows($bolsa)>0){ // SI hAY REGistros entonces realiza un array asociativo
            while($f=pg_fetch_assoc($bolsa)){ // que me devuelva el campo con su respectivo valor
				    $feature = array( // feacture en blanco
					'type'=>'Feature'); // type es un texto que se esta almacenando
					$feature['geometry'] = json_decode($f['geom']); //decodiifca para que solo me devuelva la coord y x- rellena el feacture 
					unset($f['geom']); // la geometria se rellena en el campo geom
					$feature['properties'] = $f; // los demas valores de la tabla /id, nombre, tipo etc se rellenan en properties 
					array_push($datos, $feature); //llene el array de datos- con array push relleno un array con otro array
            }
			$featureCollection = ['type'=>'FeatureCollection', 'features'=>$datos]; // si el feacture collection esta blanco me lo devuelve vacio, en cambio si estea lleno con datos.
        }else{
            $featureCollection=array(); //me entrega el json
        }
	pg_free_result($bolsa);
        unset($bolsa);
        unset($f);
	desconectar();
	return json_decode(json_encode($featureCollection),true);
}

function consultaGeojson2($sql=''){
    global $cnx;
    conectar();
	$datos=[];
    $bolsa=pg_query($cnx,$sql);
    if(pg_num_rows($bolsa)>0){
            while($f=pg_fetch_assoc($bolsa)){
				    $feature = array(
					'type'=>'Feature');
					$feature['geometry'] = json_decode($f['geom']);
					unset($f['geom']);
					$feature['properties'] = $f;
					array_push($datos, $feature);
            }
			$featureCollection = ['type'=>'FeatureCollection', 'features'=>$datos];
        }else{
            $featureCollection=array();
        }
	pg_free_result($bolsa);
        unset($bolsa);
        unset($f);
	desconectar();
	return json_encode($featureCollection);
}

function ejecutar($sql){ // actualizar, modificar , eliminar
    global $cnx;
    conectar();
    $exito=pg_query($cnx,$sql);
	
    if($exito==true or $exito==1){
        return 1;
    }else{
        return 0;
    }
	pg_free_result($exito);
	desconectar();
}

?>