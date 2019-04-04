<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model
{

	const SESSION = "User";

	public function login($login,$password)
	{
		$sql = new Sql();


		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN",array(
			":LOGIN"=>$login
		));

		$data = $results[0];

		if(count($results)!==0 and (password_verify($password, $data["despassword"]) === true))
		{
			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues(); # cria a sessão do usuário

			return $user;
			
		}
		else
		{
			throw new \Exception("Usuário inexistente ou senha inválida.", 1);# a contra barra é pq a classe Exception está em outro name space, nó não criamos nossa proprias exceptions

		}

	}

	public static function verifyLogin($inadmin = true)
	{
		if(!isset($_SESSION[User::SESSION]) #verifica se a sessão do usuário existe
		|| 
		!$_SESSION[User::SESSION] #verifica se a sessão do usuário está definida (não perdeu valor)
		||
		!(int)$_SESSION[User::SESSION]["iduser"]>0 # verifica se a sessão do usuário é dele
		||
		(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin # verifica se ele é usuário admin
		)
		{
			header("Location: /admin/login");
			exit;
		}
	}

	public static function logout()
	{
	  $_SESSION[User::SESSION] = NULL;
	}


	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users u INNER JOIN tb_persons p USING(idperson) ORDER BY p.desperson");


	}
}

?>