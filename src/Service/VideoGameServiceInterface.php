<?php

namespace App\Service;


interface VideoGameServiceInterface
{

    public function searchByName(string $name): array;
    
    public function searchById(int $id): array;
}