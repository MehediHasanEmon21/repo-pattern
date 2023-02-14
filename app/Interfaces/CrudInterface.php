<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;

interface CrudInterface{

    public function getAll(array $filterData): Paginator;

    public function findById(int $id): object|null;

    public function create(array $data): object|null;

    public function update(array $data, int $id): object|null;

    public function delete(int $id): object|null;

}