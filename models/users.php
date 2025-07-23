<?php

require_once("dbconfig.php");

class Users extends Base
{
	public function getAllUsers()
	{
		$query = $this->db->prepare("
			SELECT 
				*
			FROM 
				users
		");

		$query->execute();

		return $query->fetchAll();
	}

	public function findUserByEmail($email)
	{
		$query = $this->db->prepare("
			SELECT 
                *
			FROM 
				users
            WHERE
                email = ?
		");

		$query->execute([$email]);

		return $query->fetch();
	}

	public function findUserById($id)
	{
		$query = $this->db->prepare("
			SELECT 
                *
			FROM 
				users
            WHERE
                user_id = ?
		");

		$query->execute([$id]);

		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function RegisterUser($formData)
	{

		$query = $this->db->prepare("
            INSERT INTO users
                (nome, email, password)
                VALUES(?, ?, ?)
            ");

		$query->execute(
			[
				$formData["nome"],
				$formData["email"],
				$formData["password"]
			]
		);
	}

	public function storeRememberToken($userId, $tokenHash, $expiresAt)
	{
		$check = $this->db->prepare("SELECT user_id FROM users WHERE user_id = ?");
		$check->execute([$userId]);
		if (!$check->fetch()) {
			throw new Exception("O user_id $userId não existe na tabela users.");
		}

		$sql = "INSERT INTO user_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)";
		$query = $this->db->prepare($sql);
		$query->execute([$userId, $tokenHash, $expiresAt]);
	}

	public function getUserByRememberToken($token)
	{
		$tokenHash = hash('sha256', $token);
		$sql = "SELECT u.* FROM user_tokens ut
				JOIN users u ON ut.user_id = u.user_id
				WHERE ut.token_hash = ? AND ut.expires_at > NOW()";
		$query = $this->db->prepare($sql);
		$query->execute(
			[$tokenHash]
		);
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteRememberToken($token)
	{
		$tokenHash = hash('sha256', $token);
		$sql = "DELETE FROM user_tokens WHERE token_hash = ?";
		$query = $this->db->prepare($sql);
		$query->execute(
			[$tokenHash]
		);
	}

	public function storeResetToken($id, $token)
	{
		$stmt = $this->db->prepare("UPDATE users SET password_token = ?, token_expira = NOW() + INTERVAL 1 HOUR WHERE user_id = ?");
		$stmt->execute([$token, $id]);
	}

	public function findByToken($token)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE password_token = ? AND token_expira > NOW()");
		$stmt->execute([$token]);
		return $stmt->fetch();
	}

	public function updatePassword($id, $hashedPassword)
	{
		$stmt = $this->db->prepare("UPDATE users SET password = ?, password_token = NULL, token_expira = NULL WHERE user_id = ?");
		$stmt->execute([$hashedPassword, $id]);
	}
}
