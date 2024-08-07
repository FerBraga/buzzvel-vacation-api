<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\VacationController;
use App\Http\Requests\Api\CreateVacationRequest;
use App\Http\Requests\Api\UpdateVacationRequest;
use App\Models\Vacation;
use App\Repositories\Interfaces\VacationRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;

class VacationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $vacationRepository;
    protected $controller;


    protected function setUp(): void
    {
        Mockery::close();
        parent::setUp();
        $this->vacationRepository = Mockery::mock(VacationRepositoryInterface::class);
        $this->controller = new VacationController($this->vacationRepository);
    }

    /** @test */
    public function it_can_list_vacations()
    {

        $vacationCollection = new Collection([
            new Vacation(['date' => '2024-08-01', 'title' => 'Test Title', 'description' => 'Test Description', 'location' => 'Test Location']),
        ]);

        $this->vacationRepository->shouldReceive('all')
            ->once()
            ->andReturn($vacationCollection);

        $controller = new \App\Http\Controllers\Api\VacationController($this->vacationRepository);

        $response = $controller->index();

        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals([
            ['date' => '2024-08-01', 'title' => 'Test Title', 'description' => 'Test Description', 'location' => 'Test Location'],
        ], $responseContent);
    }


    /** @test */
    public function it_can_show_a_vacation()
    {
        $vacation = ['id' => 1, 'date' => '2024-08-01', 'title' => 'Test Vacation'];

        $this->vacationRepository->shouldReceive('find')->once()->with(1)->andReturn($vacation);

        $response = $this->controller->show(1);
        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            [
                'id' => 1, 'date' => '2024-08-01', 'title' => 'Test Vacation'
            ],
            $responseContent
        );
    }

    /** @test */
    public function it_can_create_a_vacation()
    {
        $request = Mockery::mock(CreateVacationRequest::class);
        $request->shouldReceive('all')->once()->andReturn([
            'date' => '2024-08-01',
            'title' => 'New Vacation',
            'description' => 'Vacation Description',
            'location' => 'Vacation Location',
            'participants' => ['Participant 1']
        ]);

        $vacation = [
            'date' => '2024-08-01',
            'title' => 'New Vacation',
            'description' => 'Vacation Description',
            'location' => 'Vacation Location',
            'participants' => ['Participant 1']
        ];

        $this->vacationRepository->shouldReceive('create')->once()->andReturn($vacation);

        $response = $this->controller->store($request);

        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(
            [
                'date' => '2024-08-01',
                'title' => 'New Vacation',
                'description' => 'Vacation Description',
                'location' => 'Vacation Location',
                'participants' => ['Participant 1']
            ],
            $responseContent
        );
    }

    /** @test */
    public function it_can_update_a_vacation()
    {
        $request = Mockery::mock(UpdateVacationRequest::class);
        $request->shouldReceive('all')->once()->andReturn([
            'date' => '2024-08-01',
            'title' => 'Updated Vacation'
        ]);

        $this->vacationRepository->shouldReceive('update')->once()->with([
            'date' => '2024-08-01',
            'title' => 'Updated Vacation'
        ], 1);

        $response = $this->controller->update($request, 1);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_can_delete_a_vacation()
    {
        $this->vacationRepository->shouldReceive('delete')->once()->with(1);

        $response = $this->controller->destroy(1);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_can_generate_a_pdf_for_a_vacation()
    {
        Pdf::shouldReceive('loadView')
            ->once()
            ->andReturnSelf();

        Pdf::shouldReceive('download')
            ->once()
            ->andReturn(response()->make('pdf-content', 200, ['Content-Type' => 'application/pdf']));

        $vacationRepositoryMock = Mockery::mock(VacationRepositoryInterface::class);

        $vacation = new Vacation(['id' => 1, 'date' => '2024-08-01', 'title' => 'Test Title', 'description' => 'Test Description', 'location' => 'Test Location']);

        $vacationRepositoryMock->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($vacation);

        $controller = new \App\Http\Controllers\Api\VacationController($vacationRepositoryMock);

        $response = $controller->generate(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('pdf-content', $response->getContent());
    }
}
