<?php 


require_once 'models/User.php';
require_once 'dao/PostDaoMySql.php';


class UserDaoMySql implements UserDao {

	private $pdo;

	public function __construct(PDO $driver) {
		$this->pdo = $driver;
	} 

	public function generateUser($array) {
		$u = new User();
		$u->id = $array['id'];
		$u->name = $array['name'];
		$u->email = $array['email'];
		$u->birthdate = $array['birthdate'];
		$u->password = $array['password'];
		$u->avatar = $array['avatar'];
		$u->cover = $array['cover'];
		$u->description = $array['description'];
		$u->token = $array['token'];

		return $u;
	}

	public function findByToken($token) {
		$sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token ");
		$sql->bindValue(':token', $token);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			$user = $this->generateUser($data);
			return $user;
		} else {
			return false;
		}
	}

	public function findByEmail($email) {
		$sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
		$sql->bindValue(':email', $email);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			$user = $this->generateUser($data);
			return $user;
		} else {
			return false;
		}
	}

	public function registerUser(User $u) {
			$sql = $this->pdo->prepare("INSERT INTO users (name, email, password, birthdate, token) VALUES (:name, :email, :password, :birthdate, :token) ");
		$sql->bindValue(':email', $u->email);
		$sql->bindValue(':name', $u->name);
		$sql->bindValue(':password', $u->password);
		$sql->bindValue(':birthdate', $u->birthdate);
		$sql->bindValue(':token', $u->token);
		$sql->execute();

		return true;
	}

	public function updateToken($token, $id) {
		$sql = $this->pdo->prepare("UPDATE users SET token = :token WHERE id=:id");
		$sql->bindValue(':token', $token);
		$sql->bindValue(':id', $id);
		$sql->execute();

		return true;
	}

	public function getProfileFeed($id) {
		$array = [];

		$perPage = 6;
		$page = intval(filter_input(INPUT_GET, 'page'));

		if($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $perPage;



		$sql = $this->pdo->prepare("SELECT * FROM posts WHERE id_user=:id ORDER BY created_At DESC LIMIT $offset, $perPage");
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);
			$posts = new PostDaoMySql($this->pdo);
			$array['posts'] = $posts->generateThumb($data);

			$sqlTotal = $this->pdo->prepare("SELECT COUNT(*) as c FROM posts WHERE id_user=:id");
			$sqlTotal->bindValue(':id', $id);
			$sqlTotal->execute();

			$totalData = $sqlTotal->fetch(PDO::FETCH_ASSOC);
			$total = $totalData['c'];

			$array['pages'] = ceil($total/ $perPage);
		}

		return $array;
	}

	public function findById($id) {
		$sql = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			$user = $this->generateUser($data);
			return $user;
		}
	}

	public function updateUser($id, $name, $email, $hash, $birthdate, $avatar, $cover, $description) {
		
		$sql = $this->pdo->prepare("UPDATE users SET 
			name = :name, email = :email, password = :password, birthdate = :birthdate, 
			avatar = :avatar ,cover =:cover, description = :description
			WHERE id = :id ");
		$sql->bindValue(':name', $name);
		$sql->bindValue(':email', $email);
		$sql->bindValue(':password', $hash);
		$sql->bindValue(':birthdate', $birthdate);
		$sql->bindValue(':avatar', $avatar);
		$sql->bindValue(':cover', $cover);
		$sql->bindValue(':description', $description);
		$sql->bindValue(':id', $id);
		$sql->execute();

		return true;
			
	}
}