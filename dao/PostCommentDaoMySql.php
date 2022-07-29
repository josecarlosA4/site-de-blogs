<?php 

require_once 'models/PostComment.php';
require_once 'dao/UserDaoMySql.php';

class PostCommentDaoMySql implements PostCommentDao {
	public $pdo;

	public function __construct(PDO $driver) {
		$this->pdo = $driver;
	}

	public function generateComment($array) {

		$list = [];

		$request = new UserDaoMySql($this->pdo);

		foreach($array as $item) {
			$comment = new PostComment();
			$comment->id_user = $item['id_user'];
			$comment->id_post = $item['id_post'];
			$comment->created_at = $item['created_at'];
			$comment->body = $item['body'];

			$user = $request->findById($item['id_user']);
			$comment->user = $user;

			$list[] = $comment;
		}

		return $list;
	}

	public function listComments($id) {
		$array = [];

		$sql = $this->pdo->prepare("SELECT * FROM postcomments WHERE id_post=:id ORDER BY created_at desc");
		$sql->bindValue(':id', $id);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);
			$comments = $this->generateComment($data);
			return $comments;
			
		} 	
	}

	public function insertComment($id_post, $id_user, $body, $created_at) {

		$sql = $this->pdo->prepare("INSERT INTO 
			postcomments (id_post, id_user, created_at, body) 
			VALUES 
			(:id_post, :id_user, :created_at, :body)
			");
		$sql->bindValue(':id_post', $id_post);
		$sql->bindValue(':id_user', $id_user);
		$sql->bindValue(':created_at', $created_at);
		$sql->bindValue(':body', $body);
		$sql->execute();

		$request = new UserDaoMySql($this->pdo);
		$user = $request->findById($id_user);



		$array = ['comment' => $body, 'user' => [$user->name, $user->avatar]];


		return $array;
	}
}