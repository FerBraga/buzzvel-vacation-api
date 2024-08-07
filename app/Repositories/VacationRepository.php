<?php

namespace App\Repositories;

use App\Models\Vacation;
use App\Repositories\Interfaces\VacationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class VacationRepository implements VacationRepositoryInterface
{

    public function all(): Collection
    {
        return Vacation::all();
    }

    public function find(int $id)
    {
        return Vacation::findOrFail($id);
    }

    public function create(array $data)
    {
        return Vacation::create($data);
    }

    public function update(array $data, int $id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacation->update($data);
        return $vacation;
    }

    public function delete(int $id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacation->delete();
    }

    public function generate(int $id)
    {
        $vacation = Vacation::findOrFail($id);
        //logica para gerar pdf
    }
}
