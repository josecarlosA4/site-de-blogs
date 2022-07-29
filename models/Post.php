<?php



class Post {
	public $id;
	public $id_user;
	public $body;
	public $title;
	public $created_at;
	public $updated_at;
	public $thumbnail;
	public $category;
}

interface PostDao {
	public function generateThumb($array);
	public function generatePost($id);
	public function insertPost($id_user, $body,$title,$created_at,$thumbnail,$category);
	public function deletePost($id);
	public function editPost($id,$title, $category, $body, $thumbnail, $updated_at);
	public function getHomeFeed($id);
	public function getResultSearch($term);
}