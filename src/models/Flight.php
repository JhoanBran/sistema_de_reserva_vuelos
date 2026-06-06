<?php

class Flight {
    private PDO $db;
    private string $table = 'flights';

    public function __construct(PDO $database)
    {
        $this->db = $database;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY fecha_vuelo ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCode(string $flightCode): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE flight_code = :flight_code LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':flight_code', $flightCode);
        $stmt->execute();
        $flight = $stmt->fetch();
        return $flight ?: null;
    }

    public function create(array $data): bool
    {
        try {
            $query = "INSERT INTO {$this->table} (flight_code, cedula, nombre, apellido, telefono, ciudad_origen, ciudad_destino, fecha_vuelo) 
                      VALUES (:flight_code, :cedula, :nombre, :apellido, :telefono, :ciudad_origen, :ciudad_destino, :fecha_vuelo)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':flight_code', $data['flight_code']);
            $stmt->bindParam(':cedula', $data['cedula']);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':apellido', $data['apellido']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':ciudad_origen', $data['ciudad_origen']);
            $stmt->bindParam(':ciudad_destino', $data['ciudad_destino']);
            $stmt->bindParam(':fecha_vuelo', $data['fecha_vuelo']);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function exists(string $flightCode): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE flight_code = :flight_code";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':flight_code', $flightCode);
        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
    }

    public function update(string $flightCode, array $data): bool
    {
        $query = "UPDATE {$this->table} SET cedula = :cedula, nombre = :nombre, apellido = :apellido, telefono = :telefono, 
                  ciudad_origen = :ciudad_origen, ciudad_destino = :ciudad_destino, fecha_vuelo = :fecha_vuelo 
                  WHERE flight_code = :flight_code";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':flight_code', $flightCode);
        $stmt->bindParam(':cedula', $data['cedula']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':apellido', $data['apellido']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':ciudad_origen', $data['ciudad_origen']);
        $stmt->bindParam(':ciudad_destino', $data['ciudad_destino']);
        $stmt->bindParam(':fecha_vuelo', $data['fecha_vuelo']);
        return $stmt->execute();
    }

    public function delete(string $flightCode): bool
    {
        $query = "DELETE FROM {$this->table} WHERE flight_code = :flight_code";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':flight_code', $flightCode);
        return $stmt->execute();
    }

    public function search(string $origin, string $destination, string $date): array
    {
        $query = "SELECT * FROM {$this->table} WHERE ciudad_origen LIKE :origin AND ciudad_destino LIKE :destination";
        $params = [
            ':origin' => '%' . $origin . '%',
            ':destination' => '%' . $destination . '%',
        ];

        if (!empty($date)) {
            $query .= ' AND fecha_vuelo = :fecha_vuelo';
            $params[':fecha_vuelo'] = $date;
        }

        $query .= ' ORDER BY fecha_vuelo ASC';
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
