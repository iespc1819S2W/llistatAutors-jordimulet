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
        <title></title>
        <style>
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <label for="codi">Codi</label>
        <input type="number" name="codi" id="codi" form="tot"><br>
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" form="tot"><br>
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
            </tr>
            <?php
                $mysqli = conectar();
                
                               
                
                $ordre = "id_aut";
                if(!empty($_GET["ordre"])){
                    $ordre = $_GET["ordre"];                    
                }
                
                $cerca = "";
                if(!empty($_GET["cerca"])){
                    $cerca = $_GET["cerca"];                    
                }                              
                
                $query=$mysqli->query("SELECT COUNT(ID_AUT) FROM AUTORS ".$cerca);
                $row = $query->fetch_row();
                $rows = $row[0];               
                
                if(isset($_GET["pagina"])){
                    $pagina = $_GET["pagina"];
                }else{
                    $pagina = 0;
                }                
                $limit = 20; 
                
                if(isset($_GET["asc_codi"])){
                    $ordre = "id_aut";
                }else if(isset($_GET["des_codi"])) {
                    $ordre = "id_aut desc";
                }else if(isset($_GET["asc_nom"])){
                    $ordre = "nom_aut";
                }else if(isset($_GET["des_nom"])){
                    $ordre = "nom_aut desc";
                }
                
                if(!empty($_GET["codi"])){
                    $cerca = "where id_aut = '".$mysqli->real_escape_string($_GET["codi"])."'";
                }else if(!empty ($_GET["nom"])){
                    $cerca = "where nom_aut like '%".$mysqli->real_escape_string($_GET["nom"])."%'";
                }
                
                if(isset($_GET["primer"])){
                    $pagina = 0;
                }else if(isset ($_GET["darrer"])){
                    $pagina = $rows-$limit;
                }else if(isset ($_GET["seguent"])){
                    $pagina = $pagina+$limit;
                    if($pagina>=$rows-$limit){
                       $pagina = $rows-$limit; 
                    }
                }else if(isset ($_GET["anterior"])){
                    $pagina = $pagina-$limit;
                    if($pagina<=20){
                       $pagina = 0; 
                    }
                }
                
                $consulta = "SELECT id_aut, nom_aut FROM AUTORS $cerca ORDER BY $ordre limit $pagina,$limit";
                
                if($resultat=$mysqli->query($consulta)){
                    while($row = $resultat->fetch_assoc()){
                        echo "<tr><td>".$row["id_aut"]."</td><td>".$row["nom_aut"]."</td></tr>\n";
                    }
                    $resultat->free();
                }
                desconectar($mysqli);
            ?>
        </table>
        <form action="" method="get" id="tot">
            <input type="number" name="pagina" id="pagina" value="<?php echo $pagina?>" hidden="">
            <input type="text" name="cerca" id="cerca" value="<?php echo $cerca?>" hidden="">
            <input type="text" name="ordre" id="ordre" value="<?php echo $ordre?>" hidden="">
            <input type="submit" name="primer" id="primer" value="<<">
            <input type="submit" name="anterior" id="anterior" value="<">
            <input type="submit" name="seguent" id="seguent" value=">">
            <input type="submit" name="darrer" id="darrer" value=">>">
        </form>
    </body>
</html>
