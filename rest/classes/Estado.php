<?php

class Estado 
{
    
      public function listar($id = null){
         if($_SERVER['REQUEST_METHOD'] === 'GET'){
            //$m = new MongoDB\Driver\Manager();
            $cliente = new MongoDB\Client("mongodb://webtcs:kyojou22@webtcs-shard-00-00-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-02-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-01-czg9p.gcp.mongodb.net:27017/admin?ssl=true&replicaSet=WEBTCS-shard-0&readPreference=primary&connectTimeoutMS=10000&authSource=admin&authMechanism=SCRAM-SHA-1&3t.uriVersion=3&3t.connection.name=WEBTCS-shard-0&3t.databases=admin,test");
            $collection = $cliente->test->estado;

            if($id != null){
               $numero = (int) $id;
               $dados = $collection->find( [ '_id' => $numero ] );
               
            }else{
               $dados = $collection->find([]);
            }
            
            
            $resultados = array();
            
            foreach ($dados as $estado) {
               //echo $estado['_id'], ': ', $estado['nome'], "\n";
               //echo json_encode(array($estado));
               $resultados[] = $estado;
            }

            if (!$resultados) {
               throw new Exception("Nenhum estado encontrado!");
            }
            
            return $resultados;
         }
      }
      public function cadastrar(){
         if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $metodo = $_SERVER['REQUEST_METHOD'];
            $data = json_decode(file_get_contents("php://input"));    
            $con = new MongoDB\Client("mongodb://webtcs:kyojou22@webtcs-shard-00-00-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-02-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-01-czg9p.gcp.mongodb.net:27017/admin?ssl=true&replicaSet=WEBTCS-shard-0&readPreference=primary&connectTimeoutMS=10000&authSource=admin&authMechanism=SCRAM-SHA-1&3t.uriVersion=3&3t.connection.name=WEBTCS-shard-0&3t.databases=admin,test");

            $filter = [];
            $options = ['sort' => ['_id' => -1],'limit' => 1];
            $db = $con->test;
            $collection = $db->estado;
            $contagem = $collection->count();
            $resultado = $collection->find($filter,$options);
            
            
            if($contagem < 1){
               $result = $collection->insertOne( [ 
                  '_id' => (double)'1',
                  'nome' => $data->nome, 
                  'uf' => $data->uf, 
                  'data_cadastro' =>  date("Y-m-d H:i:s"),
                  'data_alteracao' =>  date("Y-m-d H:i:s"),
               ]);
            }else{
               
               foreach ($resultado as $ultimo) {
                  $ultimoid = (double) $ultimo->_id + 1;
                  $result = $collection->insertOne( [ 
                     '_id' => $ultimoid,
                     'nome' => $data->nome, 
                      'uf' => $data->uf, 
                     'data_cadastro' =>  date("Y-m-d H:i:s"),
                     'data_alteracao' =>  date("Y-m-d H:i:s"),
                  ]);
                  return 'Cadastrado com Sucesso';
               }
            }
            
         }
      }

      public function alterar($id = null){
         if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            $metodo = $_SERVER['REQUEST_METHOD'];
            $data = json_decode(file_get_contents("php://input"));    

            $con = new MongoDB\Client("mongodb://webtcs:kyojou22@webtcs-shard-00-00-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-02-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-01-czg9p.gcp.mongodb.net:27017/admin?ssl=true&replicaSet=WEBTCS-shard-0&readPreference=primary&connectTimeoutMS=10000&authSource=admin&authMechanism=SCRAM-SHA-1&3t.uriVersion=3&3t.connection.name=WEBTCS-shard-0&3t.databases=admin,test");
            $db = $con->test;
            $collection = $db->estado;

            if($id != null){
               $id = (int) $id;
              
               
            }else{
               return 'Preencha o ID';
            }

            // Caso Parar de funcionar utilizar (new DateTime)->format('U.u')
            $alteracoes = array(
               '$set' => array(
                  'nome'=> $data->nome,
                  'uf'=> $data->uf,
                  'data_alteracao' => date("Y-m-d H:i:s"),
               )
            );
            $collection->updateOne(array("_id" => $id ), $alteracoes);   
            $dados = $collection->findOne( [ '_id' => $id ] );
            return($dados);
         }
      }

      public function deletar($id = null){
         if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            $metodo = $_SERVER['REQUEST_METHOD'];
            $data = json_decode(file_get_contents("php://input"));    

            $con = new MongoDB\Client("mongodb://webtcs:kyojou22@webtcs-shard-00-00-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-02-czg9p.gcp.mongodb.net:27017,webtcs-shard-00-01-czg9p.gcp.mongodb.net:27017/admin?ssl=true&replicaSet=WEBTCS-shard-0&readPreference=primary&connectTimeoutMS=10000&authSource=admin&authMechanism=SCRAM-SHA-1&3t.uriVersion=3&3t.connection.name=WEBTCS-shard-0&3t.databases=admin,test");
            $db = $con->test;
            $collection = $db->estado;

            if($id != null){
               $id = (int) $id; 
            }else{
               return 'Preencha o ID';
            }
            
            $dados = $collection->deleteOne( [ '_id' => $id ] );
            
            return 'Deletado com Sucesso';
         }
      }
     
}