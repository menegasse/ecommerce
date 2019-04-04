<?php 

session_start();

#Rotas das páginas

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});


//tela de administrador
$app->get('/admin', function() {

	User::verifyLogin();
    
	$page = new PageAdmin();

	$page->setTpl("index");

});


//tela inicial de login do administrador 
$app->get('/admin/login', function() {
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});


// rota de lgoin do administrador
$app->post('/admin/login', function() {
    
	User::login($_POST["login"],$_POST["password"]);

	header("Location: /admin");
	exit;
});


// rota de logout do sistema
$app->get('/admin/logout',function(){

	User::logout();

	header("Location:  /admin/login");
	exit;
});


//tela que vai listar todos os usuários
$app->get('/admin/users',function(){

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users",array(
		"users" => $users
	));
});

//tela de criação de usuário
$app->get('/admin/users/create',function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});

$app->get("/admin/users/:iduser/delete",function($iduser){
	User::verifyLogin();
});

//tela de update de ususário
$app->get('/admin/users/:iduser',function($iduser){    //passasse o id do usuario na rota como boas praticas para acessar aquele usuário em especifico 

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-update");
});

//rota para salvar o usuário no banco
$app->post("/admin/users/create",function($iduser){
	User::verifyLogin();

});

//rota para salvar a edisão do usuário no banco
$app->post("/admin/users/:iduser",function($iduser){
	User::verifyLogin();
});


$app->run();

 ?>