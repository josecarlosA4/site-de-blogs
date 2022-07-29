<?php 

require_once 'models/savedPost.php';
require_once 'dao/PostDaoMySql.php';

class savedPostDaoMySql implements savedPostDao {

	public $pdo;

	public function __construct(PDO $driver) {
		$this->pdo = $driver;
	}

	public function checkSave($id_user, $id_post) {
		$sql = $this->pdo->prepare("SELECT * FROM savedposts WHERE id_user = :id_user AND id_post = :id_post");
		$sql->bindValue(':id_user', $id_user);
		$sql->bindValue(':id_post', $id_post);
		$sql->execute();

		if($sql->rowCount() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function saveActions($id_user, $id_post, $date, $check) {
		$array = ['errors' => ''];
		if($check == 1) {
			$sql = $this->pdo->prepare("DELETE FROM savedposts WHERE id_user = :id_user AND id_post = :id_post");
			$sql->bindValue(':id_user', $id_user);
			$sql->bindValue(':id_post', $id_post);
			$sql->execute();

			$array['errors'] = '';
			return $array;
		}

		if($check == 0) {
			$sql = $this->pdo->prepare("INSERT INTO 
				savedposts (id_user, id_post, created_at) 
				VALUES (:id_user, :id_post, :created_at)");
			$sql->bindValue(':id_user', $id_user);
			$sql->bindValue(':id_post', $id_post);
			$sql->bindValue(':created_at', $date);
			$sql->execute();

			$array['errors'] = '';
			return $array;
		}

	}

	public function savedList($id_user) {

		$sql = $this->pdo->prepare("SELECT id_post FROM savedposts WHERE id_user = :id_user ");
		$sql->bindValue(':id_user', $id_user);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);

			foreach($data as $item) {
				$array[] = $item['id_post'];
			}
		} else {
			$array = false;
		}

		return $array;
	}

	public function listSaved($id_user) {
		$array = [];
		$list = $this->savedList($id_user);

		$perPage = 6;
		$page = intval(filter_input(INPUT_GET, 'page'));

		if($page < 1) {
			$page = 1;
		}


		$offset = ($page - 1) * $perPage;

		if($list != false ){
			$sql = $this->pdo->query("SELECT * FROM posts WHERE id IN(".implode(',', $list).") ORDER BY created_at DESC LIMIT $offset,$perPage");

			if($sql->rowCount() > 0) {
				$data = $sql->fetchAll(PDO::FETCH_ASSOC);
				$rPost = new PostDaoMySql($this->pdo);
				$array['posts'] = $rPost->generateThumb($data);


				$sqlTotal = $this->pdo->query("SELECT COUNT(*) as c FROM posts WHERE id IN(".implode(',', $list).")");
				$dataTotal = $sqlTotal->fetch(PDO::FETCH_ASSOC);

				$total = $dataTotal['c'];

				$array['pages'] = ceil($total / $perPage);
			}
		} else {
			$array = false;
		}

		return $array;
	}
}