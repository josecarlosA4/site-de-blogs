<?php 
 require_once 'models/UserRelation.php';
 
 class UserRelationDaoMySql implements UserRelationDao {

 	public $pdo;

 	public function __construct(PDO $driver) {
 		$this->pdo = $driver;
 	}

 	public function checkFollow($user_from_id, $user_to_id) {
		$sql = $this->pdo->prepare("SELECT * FROM userrelations WHERE user_from = :user_from AND user_to = :user_to ");
		$sql->bindValue(':user_from', $user_from_id);
		$sql->bindValue(':user_to', $user_to_id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function relation($user_from_id, $user_to_id, $check) {
		if($check == 1) {
			$sql = $this->pdo->prepare("DELETE FROM userrelations WHERE user_from = :user_from AND user_to = :user_to");
			$sql->bindValue(':user_from', $user_from_id);
			$sql->bindValue(':user_to', $user_to_id);
			$sql->execute();
			$check = 0;
			return $check;

		}

		if($check == 0) {
			$sql = $this->pdo->prepare("INSERT INTO userrelations (user_to, user_from) VALUES (:user_to, :user_from)");
			$sql->bindValue(':user_from', $user_from_id);
			$sql->bindValue(':user_to', $user_to_id);
			$sql->execute();
			$check = 1;
			return $check;	  
		}
	}

	public function getFollowing($id) {
		$array = [];

		$sql = $this->pdo->prepare("SELECT user_to FROM userrelations WHERE user_from = :id");
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);

			foreach($data as $item) {
				$array[] = $item['user_to'];
			}

			return $array;

		} 
	}
 }