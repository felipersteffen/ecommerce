<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Product extends Model {

    public static function listAll(){
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
    }

    public static function listAllSlider(){
        $sql = new Sql();

        return $sql->select(
            "SELECT DISTINCT p.*
            FROM tb_products p
            LEFT JOIN tb_cartsproducts cp
                ON cp.idproduct = p.idproduct
            LEFT JOIN tb_orders o
                ON o.idcart = cp.idcart
            ORDER BY o.dtregister DESC
            LIMIT 5"
        );
    }

    public static function checkList($list){
        foreach($list as &$row){
            $p = new Product();
            $p->setData($row);
            $row = $p->getValues();
        }

        return $list;
    }

    public function save(){
        $sql = new Sql();

        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"  => $this->getidproduct(),
            ":desproduct" => $this->getdesproduct(),
            ":vlprice"    => $this->getvlprice(),
            ":vlwidth"    => $this->getvlwidth(),
            ":vlheight"   => $this->getvlheight(),
            ":vllength"   => $this->getvllength(),
            ":vlweight"   => $this->getvlweight(),
            ":desurl"     => $this->getdesurl()
        ));

        $this->setData($results[0]);
    }

    public function get($idproduct){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct" => $idproduct
        ));

        $this->setData($results[0]);
    }

    public function delete(){
        $sql = new Sql();

        $sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct" => $this->getidproduct()
        ));
    }
    
    public function checkPhoto(){
        $url = "/res/site/img/product.jpg";

        if(file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
            "res" . DIRECTORY_SEPARATOR . 
            "site" . DIRECTORY_SEPARATOR . 
            "img" . DIRECTORY_SEPARATOR . 
            "products" . DIRECTORY_SEPARATOR . 
            $this->getidproduct() . ".jpg")){

            $url = "/res/site/img/products/" . $this->getidproduct() . ".jpg";
        }

        return $this->setdesphoto($url);

        
    }

    public function getValues(){
        $this->checkPhoto();

        $values = parent::getValues();

        return $values;
    }

    public function setPhoto($file){
        $extension = explode('.', $file['name']);
        $extension = end($extension);

        switch($extension){
            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($file["tmp_name"]);
                break;
            case "gif":
                $image = imagecreatefromgif($file["tmp_name"]);
                break;
            case "png":
                $image = imagecreatefrompng($file["tmp_name"]);
                break;
        }

        $dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
            "res" . DIRECTORY_SEPARATOR . 
            "site" . DIRECTORY_SEPARATOR . 
            "img" . DIRECTORY_SEPARATOR . 
            "products" . DIRECTORY_SEPARATOR . 
            $this->getidproduct() . ".jpg";

        imagejpeg($image, $dist);

        imagedestroy($image);

        $this->checkPhoto();
    }

    public function getFromURL($desurl){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl", array(
            ":desurl" => $desurl
        ));

        $this->setData($results[0]);
    }

    public function getCategories(){
        $sql = new Sql();

        return $sql->select(
            "SELECT * 
            FROM tb_categories c 
            INNER JOIN tb_productscategories pc 
                ON pc.idcategory = c.idcategory
            WHERE pc.idproduct = :idproduct"
            , array(
                "idproduct" => $this->getidproduct()
            ));
    }

    public static function getPage($page = 1, $search, $itemsPerPage = 10){
        $start = ($page - 1) * $itemsPerPage;
        $sql = new Sql();

        $whereSearch = "";
        if($search != '')
            $whereSearch = "WHERE desproduct LIKE '%{$search}%'";

        $results = $sql->select(
            "SELECT SQL_CALC_FOUND_ROWS * 
            FROM tb_products
            {$whereSearch}
            ORDER BY desproduct
            LIMIT {$start}, {$itemsPerPage}"
        );

        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return array(
            "data"  => $results,
            "total" => (int)$resultTotal[0]["nrtotal"],
            "pages" => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
        );
        
    }

    public static function getPageSite($page = 1, $search, $itemsPerPage = 20){
        $start = ($page - 1) * $itemsPerPage;
        $sql = new Sql();

        $whereSearch = "";
        if($search != '')
            $whereSearch = "WHERE p.desproduct LIKE '%{$search}%'";

        $results = $sql->select(
            "SELECT SQL_CALC_FOUND_ROWS 
                p.*,
                (SELECT GROUP_CONCAT(c.descategory)
                    FROM tb_categories c
                    INNER JOIN tb_productscategories pc
                        ON pc.idcategory = c.idcategory
                    WHERE pc.idproduct = p.idproduct) AS categories
            FROM tb_products p
            {$whereSearch}
            ORDER BY p.desproduct
            LIMIT {$start}, {$itemsPerPage}"
        );

        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return array(
            "data"  => $results,
            "total" => (int)$resultTotal[0]["nrtotal"],
            "pages" => ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
        );
        
    }
}
?>