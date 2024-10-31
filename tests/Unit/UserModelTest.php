<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Enums\RoleType;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user(): void
    {
        // Arrange && Act
        $newUser = User::create([
            'name' => 'TestName',
            'surname' => 'TestSurname',
            'email' => 'test@mail.com',
            'role' => RoleType::User->value
        ]);

        // Assert
        $userInDatabase = User::find($newUser->id);

        $this->assertEquals('TestName', $userInDatabase->name);
        $this->assertEquals('TestSurname', $userInDatabase->surname);
        $this->assertEquals('test@mail.com', $userInDatabase->email);
        $this->assertEquals(RoleType::User->value, $userInDatabase->role);
    }
}
