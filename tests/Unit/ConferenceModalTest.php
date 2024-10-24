<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conference;
use App\Enums\RoleType;
use App\Enums\ThemeType;
use Carbon\Carbon;

use function PHPUnit\Framework\assertEquals;

class ConferenceModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_conference(): void
    {
        // Arrange
        $conferenceOwner = User::create([
            'name' => 'OwnerName',
            'surname' => 'OwnerSurname',
            'role' => RoleType::User->value
        ]);

        // Act
        $newConference = Conference::create([
            'title' => 'ConferenceTitle',
            'description' => 'ConferenceDescription',
            'theme' => ThemeType::Other->value,
            'start_time' => Carbon::create(2024, 10, 24, 8, 0, 0),
            'end_time' => Carbon::create(2024, 10, 24, 16, 0, 0),
            'place_address' => 'VUT FIT',
            'price' => 100,
            'capacity' => 300,
            'owner_id' => $conferenceOwner->id
        ]);

        // Assert
        $conference = Conference::find($newConference->id);
        
        $this->assertEquals('ConferenceTitle', $conference->title);
        $this->assertEquals($conferenceOwner->id, $conference->owner_id);
    }
}
