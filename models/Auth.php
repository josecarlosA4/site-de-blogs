<?php
	
	require_once 'dao/UserDaoMySql.php';

	class Auth {
		private $pdo;
		private $base;
		private $dao;

		public function __construct(PDO $driver, $path) {
			$this->pdo = $driver;
			$this->base = $path;
			$this->dao = new UserDaoMySql($this->pdo);
		}

		public function checkLogin() {
			if(!empty($_SESSION['token'])) {
				$token = $_SESSION['token'];
				$user = $this->dao->findByToken($token);

				if($user) {
					return $user;
				} else {
					header("Location: ".$this->base."/login.php");
					exit;
				}
			} else {
				header("Location: ".$this->base."/login.php");
				exit;
			}
		}

		public function emailExists($email) {
			$emailCheck = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
			$emailCheck->bindValue(':email', $email);
			$emailCheck->execute();

			if($emailCheck->rowCount() > 0) {
				return true;
			} else {
				return false;
			}
		}

		public function create($name, $email, $password, $birthdate) {

			$hash = password_hash($password, PASSWORD_DEFAULT);
			$token = md5(time().rand(0, 9999).time());

			$u = new User();
			$u->name = $name;
			$u->email = $email;
			$u->password = $hash;
			$u->birthdate = $birthdate;
			$u->token = $token;

			$_SESSION['token'] = $token;

			$this->dao->registerUser($u);
			header("Location: ".$this->base."/index.php");
			exit;

		}

		public function validateLogin($email, $password) {
			$user = $this->dao->findByEmail($email);

			if($user) {
				if(password_verify($password, $user->password)) {
					$token = md5(time().rand(0, 9999).time());
					$this->dao->updateToken($token, $user->id);
					$_SESSION['token'] = $token;

					return true;

				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}