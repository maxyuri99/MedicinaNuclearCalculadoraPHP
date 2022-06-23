<?php
   $host   = "localhost";
   $user   = "root";
   $pass   = "";
   $dbname = "medi0470_wp293";

   try{
     
      $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

      //ativar o modo de erros
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   } catch (PDOExeception $e){
      // erro na conxão
      $error = $e->gertMessage();
      print "Erro: $error";
   }
?>
