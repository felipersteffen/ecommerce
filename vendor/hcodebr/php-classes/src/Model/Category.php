<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Category extends Model {

    public static function listAll(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save(){
        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
            ":idcategory" => $this->getidcategory(),
            ":descategory" => $this->getdescategory()
        ));

        $this->setData($results[0]);

        Category::updateFile();
    }

    public function get($idcategory){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", array(
            ":idcategory" => $idcategory
        ));

        $this->setData($results[0]);
    }

    public function delete(){
        $sql = new Sql();

        $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", array(
            ":idcategory" => $this->getidcategory()
        ));

        Category::updateFile();
    }

    public static function updateFile(){
        $categories = Category::listAll();

        $html = [];

        foreach($categories as $row){
            array_push($html, '<li><a href="/categories/' . $row['idcategory'] . '">' . $row['descategory'] . '</a></li>');
        }

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));
    }

    public function getProducts($related = true){
        $sql = new Sql();

        if($related === true){
            return $sql->select(
                "SELECT p.* 
                FROM tb_products p 
                INNER JOIN tb_productscategories pc 
                    ON p.idproduct = pc.idproduct 
                WHERE pc.idcategory = :idcategory"
                , array(
                    ":idcategory" => $this->getidcategory()
            ));
        } else{
             return $sql->select(
                "SELECT p.* 
                FROM tb_products p 
                WHERE NOT EXISTS(
                		SELECT 1
                                FROM tb_productscategories pc
                                WHERE pc.idproduct = p.idproduct
                                AND pc.idcategory = :idcategory)"
                , array(
                    ":idcategory" => $this->getidcategory()
            ));
        }
    }

    public function getProductsPage($page = 1, $itemsPerPage = 8){
        $start = ($page - 1) * $itemsPerPage;
        $sql = new Sql();

        $results = $sql->select(
            "SELECT SQL_CALC_FOUND_ROWS p.* 
            FROM tb_products p 
            INNER JOIN tb_productscategories pc 
                ON p.idproduct = pc.idproduct 
            WHERE pc.idcategory = :idcategory
            LIMIT {$start}, {$itemsPerPage}"
            , array(
                ":idcategory"   => $this->getidcategory()
        ));

        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return array(
            "data"  => Product::checkList($results),
            "total" => (int)$resultTotal[0]["nrtotal"],
            "pages" => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
        );
        
    }

    public function addProduct(Product $product){
        $sql = new Sql();

        $sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)", array(
            ":idcategory" => $this->getidcategory(),
            ":idproduct"  => $product->getidproduct()
        ));
    }

    public function removeProduct(Product $product){
        $sql = new Sql();

        $sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct", array(
            ":idcategory" => $this->getidcategory(),
            ":idproduct"  => $product->getidproduct()
        ));
    }
}
?>