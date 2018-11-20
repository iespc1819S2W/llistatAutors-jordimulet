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
        <form method="get">
            <label for="codi">Codi</label>
            <input type="number" name="codi" id="codi"><br>
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom"><br>
            <input type="submit" name="cercar" id="cercar"><br>
        </form>
        <table>
            <tr>
                <th>Codi 
                    <form method="get">
                        <input type="submit" name="asc_codi" id="asc_codi" value="0">
                        <input type="submit" name="des_codi" id="des_codi" value="9">
                    </form>
                </th>
                <th>Autor
                    <form method="get">
                        <input type="submit" name="asc_nom" id="asc_nom" value="A">
                        <input type="submit" name="des_nom" id="des_nom" value="Z">
                    </form>
                </th>
            </tr>
            <?php
                $mysqli = conectar();
                $ordre = "id_aut";
                $limit = 10;
                if(isset($_GET["asc_codi"])){
                    $ordre = "id_aut";
                }else if(isset($_GET["des_codi"])) {
                    $ordre = "id_aut desc";
                }else if(isset($_GET["asc_nom"])){
                    $ordre = "nom_aut";
                }else if(isset($_GET["des_nom"])){
                    $ordre = "nom_aut desc";
                }
                $consulta = "SELECT id_aut, nom_aut FROM AUTORS ORDER BY $ordre limit $limit";
                if($resultat=$mysqli->query($consulta)){
                    while($row = $resultat->fetch_assoc()){
                        echo "<tr><td>".$row["id_aut"]."</td><td>".$row["nom_aut"]."</td></tr>";
                    }
                    $resultat->free();
                }
                desconectar($mysqli);
            ?>
        </table>
    </body>
</html>
