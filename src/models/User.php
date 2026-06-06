<?php

class User {
    private PDO $db;
    private string $table = 'users';

    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    public function createUser(array $data): bool
    {
        $query = "INSERT INTO {$this->table} (username, password, cedula, nombre, apellido, telefono, tipo_usuario) 
                  VALUES (:username, :password, :cedula, :nombre, :apellido, :telefono, :tipo_usuario)";
        $stmt = $this->db->prepare($query);
        $securePassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password', $securePassword);
        $stmt->bindParam(':cedula', $data['cedula']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':tipo_usuario', $data['tipo_usuario']);
        return $stmt->execute();
    }

    public function getAllUsers(): array
    {
        $query = "SELECT id, username, cedula, nombre, apellido, telefono, tipo_usuario FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateUser(int $id, array $data): bool
    {
        $query = "UPDATE {$this->table} SET username = :username, cedula = :cedula, nombre = :nombre, apellido = :apellido, telefono = :telefono, tipo_usuario = :tipo_usuario";

        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }

        $query .= " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':cedula', $data['cedula']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':tipo_usuario', $data['tipo_usuario']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!empty($data['password'])) {
            $securePassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $securePassword);
        }

        return $stmt->execute();
    }

    public function deleteUser(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findByUsername(string $username): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByCedula(string $cedula): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE cedula = :cedula LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $query = "SELECT id, username, cedula, nombre, apellido, telefono, tipo_usuario FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
