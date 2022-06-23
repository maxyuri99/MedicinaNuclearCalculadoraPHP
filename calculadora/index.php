
<?php
  // include_once("config/url.php");
   include_once("config/conexao.php");
   //include_once("config/processa_dado.php");

   if(isset($_SESSION['msg'])){

     $pintMsg = $_SESSION['msg'];
     $_SESSION['msg'] = '';
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <!-- BOOTSTRAP-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0-beta1/css/bootstrap.min.css" integrity="sha512-o/MhoRPVLExxZjCFVBsm17Pkztkzmh7Dp8k7/3JrtNCHh0AQ489kwpfA3dPSHzKDe8YCuEhxXq3Y71eb/o6amg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://medicinanuclearsc.com.br/Calculadora/css/style.css"> 
</head>
<body> 
        <form action="index.php"  method="post">
          <h2>
          <span class="cabeca">Calculadora de Atividade Pediátrica</span>
          </h2>
          <i>Cálculo da atividade administrada em [MBq] e [mCi]</i>
          <div class="Dados1">
          <select id="Dados1" name="Dados1"> 
            <option value=''>Selecione a Massa em 'kg'</option>;
            <?php     
              $sql = "select * from quilo_class";        
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $resultado = $stmt->fetchAll();
              foreach($resultado as $key => $value){
                $id = $value['kg'];
                echo "<option value='$id'>$id kg</option>";
              }
            ?>
          </select><br/>  
          </div>  
          <div class="Dados2"> 
          <select id= "Dados2" name="Dado2">
            <option value=''>Selecione um Radiofármaco</option>;
            <?php       
             $sql = "select * from pharmaceutical_class";        
             $stmt = $conn->prepare($sql);
             $stmt->execute();
             $resultado = $stmt->fetchAll();
             foreach($resultado as $key => $value){
                $id = $value['nome'];
                echo "<option value='$id'>$id</option>";               
             }
            ?>
          </select><br/>
          </div>      
          <button class="btn" id="calcular">Calcular</button>
          <?php
            if(!empty($_POST['Dados1'])) {
              if(!empty($_POST['Dado2'])) {
                $dado1 = $_POST['Dados1'];
                $dado2 = $_POST['Dado2']; 

                // Pega a classe se é A B ou C do BC 
                $sqlp1 = "select classe from pharmaceutical_class where nome =  '" .  $dado2 . "'"; 
                $stmt1 = $conn->prepare($sqlp1);
                $stmt1->execute();
                $resultado1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                $classe = implode("", $resultado1[0]);

                // Pega a Calc_base 
                $sqlp2 = "select calc_base from pharmaceutical_class where nome =  '" .  $dado2 . "'"; 
                $stmt2 = $conn->prepare($sqlp2);
                $stmt2->execute();
                $resultado2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $calc_base = implode("", $resultado2[0]);

                // Pega o minimo 
                $sqlp3 = "select minimum from pharmaceutical_class where nome =  '" .  $dado2 . "'"; 
                $stmt3 = $conn->prepare($sqlp3);
                $stmt3->execute();
                $resultado3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                $minimum = implode("", $resultado3[0]);

                // Pega o número na tabela quilo_class para calcular 
                $calq1 = "select " .  $classe . " from quilo_class where kg = " .  $dado1;
                $stmt4 = $conn->prepare($calq1);
                $stmt4->execute();
                $resultado4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                $quilo_class = implode("", $resultado4[0]);
                
                $resultfinal = $quilo_class * $calc_base;

                // Calcula o mCi 
                $resultadomci = $resultfinal / 37;
                
                if ($resultfinal < $minimum ) {
                 $mostrar = "Unidade sugerida a ser administrada = " . substr($minimum , 0, 5) . " MBq ou " . substr($resultadomci, 0, 4) . " mCi ";
                }else
                 $mostrar = "Unidade sugerida a ser administrada = " . substr($resultfinal, 0, 5) . " MBq ou " . substr($resultadomci, 0, 4) . " mCi ";

              }else {
                $mostrar = 'Selecione um Radiofármaco';
              }
            }else {
              $mostrar = 'Selecione a Massa e um Radiofármaco';
            }
          ?>
          <div class="Resultado">
          <?php 
          echo $mostrar; 
          ?>
          </div>
        </form>   
    
</body>
</html>




