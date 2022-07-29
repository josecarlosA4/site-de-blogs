<?php 
class User {
	public $id;
	public $name;
	public $email;
	public $password;
	public $birthdate;
	public $avatar;
	public $cover;
	public $description;
	public $token;
}


interface UserDao {
	public function findByToken($token);
	public function findByEmail($email);
	public function findById($id);
	public function registerUser(User $u);
	public function updateToken($token, $id);
	public function getProfileFeed($id);
	public function updateUser($id, $name, $email, $hash, $birthdate, $avatar, $cover, $description);	
}