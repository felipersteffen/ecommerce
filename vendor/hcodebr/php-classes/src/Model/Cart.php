<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Model\User;

class Cart extends Model {

    const SESSION = "Cart";

    public static function getFromSession(){
        $cart = new Cart();

        if(isset($_SESSION[Cart::SESSION]) && $_SESSION[Cart::SESSION]['idcart'] > 0)
            $cart->get((int)$_SESSION[Cart::SESSION]['idcart']);

        else {
            $cart->getFromSessionId();

            if(empty($cart->getidcart())){
                
                $data = array(
                    "dessessionid" => session_id()
                );

                if(User::checkLogin(false)){
                    $user = User::getFromSession();

                    $data["iduser"] = $user->getiduser();
                }

                $cart->setData($data);
                $cart->save();
                $cart->setToSession();
            }
        }

        return $cart;
 
    }

    public function setToSession(){
        $_SESSION[Cart::SESSION] = $this->getValues();
    }

    public function getFromSessionId(){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", array(
            ":idcart" => session_id()
        ));

        if(!empty($results))
            $this->setData($results[0]);

    }

    public function get(int $idcart){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", array(
            ":idcart" => $idcart
        ));

        if(!empty($results))
            $this->setData($results[0]);

    }

    public function save(){
        $sql = new Sql();

        $results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", array(
            ":idcart" => $this->getidcart(),
            ":dessessionid" => $this->getdessessionid(),
            ":iduser" => $this->getiduser(),
            ":deszipcode" => $this->getdeszipcode(),
            ":vlfreight" => $this->getvlfreight(),
            ":nrdays" => $this->getnrdays()
        ));

        $this->setData($results[0]);
    }

    public function addProduct(Product $product){
        $sql = new Sql();

        $sql->query("INSERT INTO tb_cartsproducts (idcart, idproduct) VALUES (:idcart, :idproduct)", array(
            ":idcart" => $this->getidcart(),
            ":idproduct" => $product->getidproduct()
        ));

        $this->getCalculateTotal();

    }

    public function removeProduct(Product $product, $all = false){
        $sql = new Sql();

        if($all === true){
            $sql->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL", array(
                ":idcart" => $this->getidcart(),
                ":idproduct" => $product->getidproduct()
            ));
        } else{
            $sql->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL LIMIT 1", array(
                ":idcart" => $this->getidcart(),
                ":idproduct" => $product->getidproduct()
            ));
        }

        $this->getCalculateTotal();
    }

    public function getProducts(){
        $sql = new Sql();

        $results = $sql->select(
            "SELECT p.idproduct, p.desproduct, p.vlprice, p.vlwidth, p.vlheight, p.vllength, p.vlweight, p.desurl, COUNT(*) AS nrqtd, SUM(p.vlprice) AS vltotal
            FROM tb_cartsproducts cp 
            INNER JOIN tb_products p 
                ON cp.idproduct = p.idproduct 
            WHERE cp.idcart = :idcart 
                AND cp.dtremoved IS NULL 
            GROUP BY p.idproduct, p.desproduct, p.vlprice, p.vlwidth, p.vlheight, p.vllength, p.vlweight, p.desurl
            ORDER BY p.desproduct"
            , array(
                ":idcart" => $this->getidcart()
            ));

        return Product::checkList($results);
    }

    public function getProductsTotals(){
        $sql = new Sql();

        $results = $sql->select(
            "SELECT SUM(vlprice) AS vlprice, SUM(vlwidth) AS vlwidth, SUM(vlheight) AS vlheight, SUM(vllength) AS vllength, SUM(vlweight) AS vlweight, COUNT(*) AS nrqtd
            FROM tb_products p
            INNER JOIN tb_cartsproducts cp
                ON p.idproduct = cp.idproduct
            WHERE cp.idcart = :idcart AND dtremoved IS NULL"
            , array(
                ":idcart" => $this->getidcart()
            ));
        
        if(!empty($results))
            return $results[0];
        
        return [];
    }

    public function setFreight($nrzipcode){
        /*
        /////////// Não funciona mais este link para calculo de frete dos Correios, colocado valores fixos de momento
        $nrzipcode = str_replace('-', '', $nrzipcode);

        $totals = $this->getProductsTotals();

        if(!empty($totals)){
            $qs = http_build_query(array(
                "nCdEmpresa" => "",
                "sDsSenha" => "",
                "nCdServico" => "40010",
                "sCepOrigem" => "84430000",
                "sCepDestino" => $nrzipcode,
                "nVlPeso" => $totals["vlweight"],
                "nCdFormato" => 1,
                "nVlComprimento" => $totals["vllength"],
                "nVlAltura" => $totals["vlheight"],
                "nVlLargura" => $totals["vlwidth"],
                "nVlDiametro" => "",
                "sCdMaoPropria" => "N",
                "nVIValorDeclarado" => 0,
                "sCdAvisoRecebimento" => "N",
            ));

            $xml = simplexml_load_file("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?".$qs);

            var_dump($xml);
            exit;
        }
        */

        $this->setnrdays(12);
        $this->setvlfreight(25.00);
        $this->setdeszipcode($nrzipcode);

        $this->save();
    }

    public function updateFreight(){
        if(!empty($this->getdeszipcode()))
            $this->setFreight($this->getdeszipcode());

    }

    public function getValues(){
        $this->getCalculateTotal();

        return parent::getValues();
    }

    public function getCalculateTotal(){
        $this->updateFreight();

        $totals = $this->getProductsTotals();

        $this->setvlsubtotal($totals['vlprice'] > 0 ? $totals['vlprice'] : 0);
        $this->setvltotal($totals['vlprice'] + $this->getvlfreight() > 0 ? $totals['vlprice'] + $this->getvlfreight() : 0);
    }
}
?>