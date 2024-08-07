<?php

namespace Tests\Feature;

use App\Models\Vacation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VacationTest extends TestCase
{

    public function test_create_vacation(): void
    {
        $vacation = Vacation::create([
            'date' => '2024-08-01',
            'title' => 'Test Title',
            'description' => 'Test Description',
            'location' => 'Test Location',
            'participants' => json_encode(['Participant 1', 'Participant 2']),
        ]);


        $this->assertInstanceOf(Vacation::class, $vacation);
        $this->assertTrue(
            \DateTime::createFromFormat('Y-m-d', $vacation->date) == false,
            'The date is not in the correct format'
        );
        $this->assertEquals('Test Title', $vacation->title);
        $this->assertEquals('Test Description', $vacation->description);
        $this->assertEquals('Test Location', $vacation->location);
        $this->assertEquals(['Participant 1', 'Participant 2'], json_decode($vacation->participants, true));
    }
}
