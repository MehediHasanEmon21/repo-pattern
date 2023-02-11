<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;

interface CrudInterface{

    public function getAll(int $perPae): Paginator;

    public function findById(int $id): object|null;

}