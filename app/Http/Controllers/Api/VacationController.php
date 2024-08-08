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

    /**
     * @OA\Get(
     *     path="/api/vacations",
     *     summary="List all vacations",
     *     description="Get a list of all vacations",
     *     tags={"Vacation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of vacations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(
     *                     type="object",
     *                     required={"id", "title", "description", "date", "location", "participants"},
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         format="int64",
     *                         description="The unique identifier of the vacation"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         description="Title of the vacation"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         description="Description of the vacation"
     *                     ),
     *                     @OA\Property(
     *                         property="date",
     *                         type="string",
     *                         format="date",
     *                         description="The date of the vacation"
     *                     ),
     *                     @OA\Property(
     *                         property="location",
     *                         type="string",
     *                         description="Location of the vacation"
     *                     ),
     *                     @OA\Property(
     *                         property="participants",
     *                         type="array",
     *                         @OA\Items(
     *                             type="string",
     *                             description="Participants of the vacation"
     *                         ),
     *                         description="List of participants"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error while fetching vacations list"
     *     )
     * )
     */
    public function index()
    {
        try {
            $vacations = $this->vacationRepository->all();
            return response()->json($vacations);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occured while trying to get vacations list'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/vacations/show/1",
     *     summary="Show vacation details",
     *     description="Get details of a specific vacation by ID",
     *     tags={"Vacation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vacation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vacation details",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "title", "description", "date", "location", "participants"},
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 format="int64",
     *                 description="The unique identifier of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Description of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="The date of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="location",
     *                 type="string",
     *                 description="Location of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="participants",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     description="Participants of the vacation"
     *                 ),
     *                 description="List of participants"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vacation not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error while fetching vacation details"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/vacations/create",
     *     summary="Create a new vacation",
     *     description="Create a new vacation record",
     *     tags={"Vacation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title", "description", "date", "location", "participants"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Description of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="The date of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="location",
     *                 type="string",
     *                 description="Location of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="participants",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     description="Participants of the vacation"
     *                 ),
     *                 description="List of participants"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vacation created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "title", "description", "date", "location", "participants"},
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 format="int64",
     *                 description="The unique identifier of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Description of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="The date of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="location",
     *                 type="string",
     *                 description="Location of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="participants",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     description="Participants of the vacation"
     *                 ),
     *                 description="List of participants"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error while creating vacation"
     *     )
     * )
     */
    public function store(CreateVacationRequest $request)
    {
        try {
            $vacation = $this->vacationRepository->create($request->all());
            return response()->json($vacation, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'An error occurred while creating vacation'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/vacations/update/2",
     *     summary="Update an existing vacation",
     *     description="Update vacation details by ID",
     *     tags={"Vacation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vacation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="Title of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 description="Description of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="The date of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="location",
     *                 type="string",
     *                 description="Location of the vacation"
     *             ),
     *             @OA\Property(
     *                 property="participants",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     description="Participants of the vacation"
     *                 ),
     *                 description="List of participants"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Vacation updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vacation not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error while updating vacation"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/vacations/delete/2",
     *     summary="Delete a vacation",
     *     description="Delete a vacation by ID",
     *     tags={"Vacation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vacation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Vacation deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vacation not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error while deleting vacation"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/vacations/pdf/1",
     *     summary="Generate vacation PDF",
     *     description="Generate a PDF file for a specific vacation by ID",
     *     tags={"Vacation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Vacation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vacation PDF generated",
     *         @OA\MediaType(
     *             mediaType="application/pdf"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vacation not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error while generating PDF"
     *     )
     * )
     */
    public function generate($id)
    {
        try {
            $vacation = $this->vacationRepository->find($id);
            $pdf = Pdf::loadView('pdf.vacation', compact('vacation'));
            return $pdf->download('vacation_' . $id . '.pdf');
        } catch (ModelNotFoundException $e) {
            if ($e->getCode() == 0) {
                return response()->json(['error' => "Vacation with ID {$id} not found"], 404);
            }
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
