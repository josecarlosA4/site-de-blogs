<?php

require_once 'models/Post.php';
require_once 'dao/UserDaoMySql.php';
require_once 'dao/UserRelationDaoMySql.php';

class PostDaoMySql implements PostDao {
	private $pdo;

	public function __construct(PDO $driver) {
		$this->pdo = $driver;
	}

	public function generateThumb($array) {
		$list = [];

		foreach($array as $item) {
			$p = new Post();
			$p->id = $item['id'];
			$p->id_user = $item['id_user'];
			$p->thumbnail = $item['thumbnail'];
			$p->title = $item['title'];

			$list[] = $p;
		}

		return $list;
	}

	public function generatePost($id) {
		$sql  = $this->pdo->prepare("SELECT * FROM posts WHERE id=:id");
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetch(PDO::FETCH_ASSOC);
			$post = new Post();

			$dao = new UserDaoMySql($this->pdo);
			$user = $dao->findById($data['id_user']);

			$post->id = $data['id'];
			$post->title = $data['title'];
			$post->body = $data['body'];
			$post->created_at = $data['created_at'];
			$post->category = $data['category'];
			$post->thumbnail = $data['thumbnail'];
			$post->updated_at = $data['updated_at']; 
			$post->user = $user;

			return $post;
		} else {
			return false;
		}
	}

	public function insertPost($id_user, $body,$title,$created_at,$thumbnail,$category) {
	
		$sql = $this->pdo->prepare("INSERT INTO posts
			(id_user, body, title, created_at, thumbnail, category)
			VALUES
			(:id_user, :body, :title, :created_at, :thumbnail, :category)
		 ");
		$sql->bindValue(':id_user', $id_user);
		$sql->bindValue(':body', $body);
		$sql->bindValue(':title', $title );
		$sql->bindValue(':created_at', $created_at );
		$sql->bindValue(':thumbnail', $thumbnail);
		$sql->bindValue(':category', $category);
		$sql->execute();

		return true;
	}

	public function deletePost($id) {
		$sql = $this->pdo->prepare("DELETE FROM posts WHERE id=:id");
		$sql->bindValue(':id',$id);
		$sql->execute();

		return true;
	}

	public function editPost($id, $title, $category, $body, $thumbnail, $updated_at) {
		$sql = $this->pdo->prepare("UPDATE posts SET 
			title = :title, category = :category, body = :body, thumbnail = :thumbnail, updated_at = :updated_at 
			WHERE id = :id");

		$sql->bindValue(':title', $title);
		$sql->bindValue(':category', $category);
		$sql->bindValue(':body', $body);
		$sql->bindValue(':thumbnail', $thumbnail);
		$sql->bindValue(':updated_at', $updated_at);
		$sql->bindValue(':id', $id);
		$sql->execute();

		return true;
	}

	public function getHomeFeed($id) {
		$array = [];

		$perPage = 6;

		$page = intval(filter_input(INPUT_GET, 'page'));

		if($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $perPage;

		$relations = new UserRelationDaoMySql($this->pdo);
		$following = $relations->getFollowing($id);
		$following[] = $id;

		$sql = $this->pdo->query("SELECT * FROM posts WHERE id_user IN (".implode(',',$following).") ORDER BY created_at DESC  LIMIT $offset, $perPage ");

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);

			$array['posts'] = $this->generateThumb($data);
		}

		$sqlTotal = $this->pdo->query("SELECT COUNT(*) as c FROM posts WHERE id_user IN (".implode(',',$following).") ");
		$totalData = $sqlTotal->fetch(PDO::FETCH_ASSOC);
		$total = $totalData['c'];

		$array['pages'] = ceil($total / $perPage);

		return $array;
	}

	public function getResultSearch($term) {

		$array = [];

		$perPage = 3;
		$page = intval(filter_input(INPUT_GET, 'page'));

		if($page < 1) {
			$page = 1;
		}

		$offset = ($page - 1) * $perPage;

		$sql = $this->pdo->prepare("SELECT * FROM posts WHERE title LIKE :term OR category LIKE :term 
									LIMIT $offset, $perPage");
		$sql->bindValue(':term',  '%'.$term.'%');
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);

			$array['posts'] = $this->generateThumb($data);

			$sqlTotal = $this->pdo->prepare("SELECT COUNT(*) as c FROM posts WHERE title LIKE :term OR category LIKE :term");
			$sqlTotal->bindValue(':term',  '%'.$term.'%');
			$sqlTotal->execute();


			$totalData = $sqlTotal->fetch(PDO::FETCH_ASSOC);
			$total = $totalData['c'];
			$array['pages'] = ceil($total /$perPage);

			return $array;

		} else {
			$array['posts'] = false;
		}

		return $array;
	}
}