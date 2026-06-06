<?php
require_once __DIR__ . '/../models/Flight.php';

class FlightController
{
    private Flight $flightModel;

    public function __construct(PDO $database)
    {
        $this->flightModel = new Flight($database);
    }

    public function getAllFlights(): array
    {
        return $this->flightModel->getAll();
    }

    public function getFlight(string $flightCode): ?array
    {
        return $this->flightModel->getByCode($flightCode);
    }

    public function addFlight(array $data): bool
    {
        return $this->flightModel->create($data);
    }

    public function flightExists(string $flightCode): bool
    {
        return $this->flightModel->exists($flightCode);
    }

    public function updateFlight(string $flightCode, array $data): bool
    {
        return $this->flightModel->update($flightCode, $data);
    }

    public function deleteFlight(string $flightCode): bool
    {
        return $this->flightModel->delete($flightCode);
    }

    public function searchFlights(string $origin, string $destination, string $date): array
    {
        return $this->flightModel->search($origin, $destination, $date);
    }
}
