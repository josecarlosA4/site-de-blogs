<?php 

class UserRelation {
	public $id;
	public $user_from;
	public $user_to;
}

interface UserRelationDao {
	public function checkFollow($user_from_id, $user_to_id);
	public function relation($user_from_id, $user_to_id, $check);
	public function getFollowing($id);
}