<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateVacationRequest;
use App\Http\Requests\Api\UpdateVacationRequest;
use App\Repositories\Interfaces\VacationRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VacationController extends Controller
{
    protected $vacationRepository;

    public function __construct(VacationRepositoryInterface $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    public function index()
    {
        try {
            $vacations = $this->vacationRepository->all();

            return response()->json($vacations);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occured while trying to get vacations list'], 500);
        }
    }

    public function show($id)
    {
        try {
            $vacation = $this->vacationRepository->find($id);

            return response()->json($vacation);
        } catch (Exception $e) {

            if ($e->getCode() == 0) {
                return response()->json(['error' => "Vacation with ID {$id} not found"], 404);
            }

            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function store(CreateVacationRequest $request)
    {

        try {
            $vacation = $this->vacationRepository->create($request->all());

            return response()->json($vacation, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'An error occured while creating vacation'], 500);
        }
    }

    public function update(UpdateVacationRequest $request, $id)
    {

        try {
            $this->vacationRepository->update($request->all(), $id);

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            if ($e->getCode() == 0) {
                return response()->json(['error' => "Vacation with ID {$id} not found"], 404);
            }

            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }



    public function destroy($id)
    {
        try {
            $this->vacationRepository->delete($id);

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            if ($e->getCode() == 0) {
                return response()->json(['error' => "Vacation with ID {$id} not found"], 404);
            }

            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function generate($id)
    {
        try {
            $vacation = $this->vacationRepository->find($id);

            $pdf = Pdf::loadView('pdf.vacation', ['vacation' => $vacation]);

            return $pdf->download('vacation.pdf');
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
