<?php
class User_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserByUsername($username)
    {
        $this->db->query("SELECT * FROM users WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function createUser($data)
    {
        $this->db->query("INSERT INTO users (id, fullname, username, email, password, role) VALUES (:id, :fullname, :username, :email, :password, :role)");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        try {
            $this->db->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
}
