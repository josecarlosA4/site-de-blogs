<?php 

class savedPost {
	public $id;
	public $id_user;
	public $id_post;
	public $created_at;
}

interface savedPostDao {
	public function checkSave($id_user, $id_post);
	public function saveActions($id_user, $id_post, $date, $check);
	public function savedList($id_user);
	public function listSaved($id_user);
}