<?php

use Hcode\PageAdmin;
use Hcode\Model\User;
use Hcode\Model\Category;

$app->get('/admin', function() {
    
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login', function() {
    
	$page = new PageAdmin([
		"header"=> false,
		"footer"=> false
	]);

	$page->setTpl("login");

});

$app->post('/admin/login', function() {
    
	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;
});

$app->get('/admin/logout', function() {
    User::logout();

	header("Location: /admin/login");
	exit;

});

$app->get('/admin/users', function(){
	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users" => $users
	));
});

$app->get('/admin/users/create', function(){
	User::verifyLogin();

	$page = new PageAdmin();
	$page->setTpl("users-create");
});

$app->get('/admin/users/:iduser/delete', function($iduser){
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

$app->get("/admin/users/:iduser", function($iduser){
	User::verifyLogin();

	$user = new User();
    $user->get((int)$iduser);

	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user" => $user->getValues()
	));
});

$app->post('/admin/users/create', function(){
	User::verifyLogin();

	$user = new User();

	$_POST["iadmin"] = isset($_POST["inadmin"]) ? 1 : 0;

	$user->setData($_POST);
	$user->save();

	header("Location: /admin/users");
	exit;
});

$app->post("/admin/users/:iduser", function($iduser){
	User::verifyLogin();

	$user = new User();
	$_POST["iadmin"] = isset($_POST["inadmin"]) ? 1 : 0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();

	header("Location: /admin/users");
	exit;
});



$app->get("/admin/categories", function(){
	User::verifyLogin();

	$categories = Category::listAll();
	
	$page = new PageAdmin();

	$page->setTpl("categories", array(
		"categories" => $categories
	));
});

$app->get("/admin/categories/create", function(){
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");
});

$app->post("/admin/categories/create", function(){
	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	exit;
});

$app->get('/admin/categories/:iduser/delete', function($idcategory){
	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

	header("Location: /admin/categories");
	exit;
});

$app->get("/admin/categories/:idcategory", function($idcategory){
	User::verifyLogin();

	$category = new Category();
    $category->get((int)$idcategory);

	$page = new PageAdmin();
	$page->setTpl("categories-update", array(
		"category" => $category->getValues()
	));
});

$app->post("/admin/categories/:idcategory", function($idcategory){
	User::verifyLogin();

	$category = new Category();

    $category->get((int)$idcategory);
	$category->setData($_POST);
	$category->save();

	header("Location: /admin/categories");
	exit;
});
?>