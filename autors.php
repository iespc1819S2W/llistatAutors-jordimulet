<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once 'sql.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Llista autors</title>
        <style>
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
            }
        </style>
        
        <script>
            window.onload = function() {
                var codi = document.getElementById("codi");
                var nom = document.getElementById("nom");
                
                if(codi.value != ""){
                    nom.disabled = true;                    
                }else if(nom.value != ""){
                    codi.disabled = true;
                }           
                
                codi.onchange = function(){
                    if(codi.value == ""){
                        nom.disabled = false;                    
                    }else{
                        nom.disabled = true;
                    }  
                }
                
                nom.onchange = function(){
                    if(nom.value == ""){
                        codi.disabled = false;                    
                    }else{
                        codi.disabled = true;
                    } 
                }
            }
        </script>
    </head>
    <body>
        <?php
        $codi = isset($_GET["codi"])?$_GET["codi"]:"";
        $nom = isset($_GET["nom"])?$_GET["nom"]:"";
        $limit = isset($_GET["limit"])?$_GET["limit"]:10;
        ?>
        <label for="codi">Codi</label>
        <input type="number" name="codi" id="codi" form="tot" value="<?=$codi?>"><br>
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" form="tot" value="<?=$nom?>"><br>
        <select form="tot" name="limit">
            <option value="10"<?php if($limit==10){echo " selected";} ?>>10</option>
            <option value="20"<?php if($limit==20){echo " selected";} ?>>20</option>
            <option value="30"<?php if($limit==30){echo " selected";} ?>>30</option>
        </select>
        <br>
        <input type="submit" name="cercar" id="cercar" form="tot"><br>
        <table>
            <tr>
                <th>Codi 
                    <input type="submit" name="asc_codi" id="asc_codi" value="0"  form="tot">
                    <input type="submit" name="des_codi" id="des_codi" value="9"  form="tot">
                </th>
                <th>Autor
                    <input type="submit" name="asc_nom" id="asc_nom" value="A"  form="tot">
                    <input type="submit" name="des_nom" id="des_nom" value="Z"  form="tot">
                </th>
                <th>Nacionalitat      
                    <input type="submit" name="asc_nac" id="asc_nac" value="A"  form="tot">
                    <input type="submit" name="des_nac" id="des_nac" value="Z"  form="tot">
                </th>
            </tr>
            <?php
                $mysqli = conectar();  
                
                //nou autor
                if(isset($_POST["newaut"]) && !empty($_POST["autor"])){                    
                    $query=$mysqli->query("SELECT MAX(ID_AUT)+1 FROM AUTORS");
                    $max = $query->fetch_row();
                    $idaut = $max[0];
                    
                    $autor = $mysqli->real_escape_string($_POST["autor"]);
                    $insert = "INSERT INTO `AUTORS`(`ID_AUT`, `NOM_AUT`) VALUES ('$idaut', '$autor')";
                    $mysqli->query($insert);
                }
                
                //borrar
                if(isset($_POST["borrar"])){
                    $query = "DELETE FROM `AUTORS` WHERE ID_AUT={$_POST["borrar"]}";
                    $mysqli->query($query);                           
                }
                
                //guardar edicio
                if(isset($_POST["guardar"])){
                    $autor = $mysqli->real_escape_string($_POST["edicio"]);
                    $update = "UPDATE `AUTORS` SET `NOM_AUT`='$autor', `FK_NACIONALITAT`='{$_POST["nacio"]}' WHERE ID_AUT={$_POST["guardar"]}";
                    $mysqli->query($update); 
                }
                
                //ordre
                $ordre = "id_aut";
                if(!empty($_GET["ordre"])){
                    $ordre = $_GET["ordre"];                    
                }                
                if(isset($_GET["asc_codi"])){
                    $ordre = "id_aut";
                }else if(isset($_GET["des_codi"])) {
                    $ordre = "id_aut desc";
                }else if(isset($_GET["asc_nom"])){
                    $ordre = "nom_aut";
                }else if(isset($_GET["des_nom"])){
                    $ordre = "nom_aut desc";
                }else if(isset($_GET["asc_nac"])){
                    $ordre = "fk_nacionalitat";
                }else if(isset($_GET["des_nac"])){
                    $ordre = "fk_nacionalitat desc";
                }
                
                //cerca
                $cerca = "";
                if(!empty($_GET["codi"])){
                    $cerca = "where id_aut = '".$mysqli->real_escape_string($_GET["codi"])."'";
                }else if(!empty ($_GET["nom"])){
                    $cerca = "where nom_aut like '%".$mysqli->real_escape_string($_GET["nom"])."%'";
                }else{
                    $cerca = "";
                }                
                
                //numero pagina
                if(isset($_GET["pagina"])){
                    $pagina = $_GET["pagina"];
                }else{
                    $pagina = 0;
                }                 
                
                //paginacio                
                $query=$mysqli->query("SELECT COUNT(ID_AUT) FROM AUTORS ".$cerca);
                $row = $query->fetch_row();
                $rows = $row[0];
                
                if(isset($_GET["primer"])){
                    $pagina = 0;
                }else if(isset ($_GET["darrer"])){
                    $pagina = $rows-$limit;
                }else if(isset ($_GET["seguent"])){
                    $pagina = $pagina+$limit;
                    if($pagina>=$rows-$limit){
                       $pagina = $rows-$limit; 
                    }
                    if($rows<$limit){
                       $pagina = 0; 
                    }
                }else if(isset ($_GET["anterior"])){
                    $pagina = $pagina-$limit;
                    if($pagina<$limit){
                       $pagina = 0; 
                    }
                }
                
                $consulta = "SELECT id_aut, nom_aut, fk_nacionalitat FROM AUTORS $cerca ORDER BY $ordre limit $pagina,$limit";
                
                if($resultat=$mysqli->query($consulta)){
                    while($row = $resultat->fetch_assoc()){
                        if(isset($_POST["editar"]) && $_POST["editar"]==$row["id_aut"]){
                            echo "<tr><td>{$row["id_aut"]}</td>"
                            . "<td><input type'text' form='crud' name='edicio' value='{$row["nom_aut"]}'></td>"
                            . "<td>". nacio($row["fk_nacionalitat"])."</td>"
                            . "<td><button type='submit' form='crud' name='guardar' value='{$row["id_aut"]}'>Guardar</button></td>"
                            . "<td><button type='submit' form='crud' name='cancelar'>Cancelar</button></td>"
                            . "</tr>\n";
                        }else{
                            echo "<tr><td>{$row["id_aut"]}</td><td>{$row["nom_aut"]}</td><td>{$row["fk_nacionalitat"]}</td>"
                            . "<td><button type='submit' form='crud' name='editar' value='{$row["id_aut"]}'>Editar</button></td>"
                            . "<td><button type='submit' form='crud' name='borrar' value='{$row["id_aut"]}'>Borrar</button></td>"
                            . "</tr>\n";                           
                        }   
                    }
                    $resultat->free();
                }
                
                desconectar($mysqli);
                
                function nacio($nac){
                    $nac=strtoupper($nac);
                    $mysqli2 = conectar(); 
                    $torna = "<select form='crud' name='nacio'>";
                    $query = "SELECT nacionalitat FROM NACIONALITATS";
                    $nacions = $mysqli2->query($query);
                    while($row = $nacions->fetch_assoc()){
                        if(strtoupper($row["nacionalitat"]) == $nac){
                            $torna .= "<option selected value='{$row["nacionalitat"]}'>{$row["nacionalitat"]}</option>";
                        }else{
                            $torna .= "<option value='{$row["nacionalitat"]}'>{$row["nacionalitat"]}</option>";
                        }
                    }
                    $torna .= "</selected>";
                    desconectar($mysqli2);
                    return $torna;                    
                }  
            ?>
        </table>
        <form action="" method="get" id="tot">
            <input type="number" name="pagina" id="pagina" value="<?php echo $pagina?>" hidden="">
            <input type="text" name="ordre" id="ordre" value="<?php echo $ordre?>" hidden="">
            <input type="submit" name="primer" id="primer" value="<<">
            <input type="submit" name="anterior" id="anterior" value="<">
            <input type="submit" name="seguent" id="seguent" value=">">
            <input type="submit" name="darrer" id="darrer" value=">>">
        </form>
        
        <form action="" method="post" id="crud">
            <label for="autor">Nou autor: </label>
            <input type="text" name="autor" id="autor">
            <input type="submit" name="newaut" id="newaut" value="Crear autor">
        </form>
    </body>
</html>
