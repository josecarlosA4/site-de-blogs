<?php 

class PostComment {
	public $id;
	public $id_user;
	public $id_post;
	public $created_at;
	public $body;
}

interface PostCommentDao {
	public function listComments($id);
	public function generateComment($array);
	public function insertComment($id_post, $id_user, $body, $created_at);
}