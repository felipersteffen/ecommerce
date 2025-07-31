<?php
use Hcode\Model\User;
use Hcode\Model\Cart;

function formatPrice($vlprice){
    if(empty($vlprice)) 
        $vlprice = 0;
    
    return number_format($vlprice, 2, ",", ".");
}

function checkLogin($inadmin = true){
    return User::checkLogin($inadmin);
}

function getUserName(){
    $user = User::getFromSession();

    return $user->getdesperson();
}

function getCartVlSubTotal(){
    $cart = Cart::getFromSession();

    $totals = $cart->getProductsTotals();

    return formatPrice($totals['vlprice']);
}

function getCartNrQdt(){
    $cart = Cart::getFromSession();

    $totals = $cart->getProductsTotals();

    return $totals['nrqtd'];
}