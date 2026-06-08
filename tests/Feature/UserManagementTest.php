<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Chef Magasinier can access user management index.
     */
    public function test_chef_magasinier_can_access_user_management(): void
    {
        $chef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
        ]);

        $response = $this->actingAs($chef)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Users Management');
    }

    /**
     * Test Magasinier cannot access user management index.
     */
    public function test_magasinier_cannot_access_user_management(): void
    {
        $magasinier = User::factory()->create([
            'role' => 'Magasinier',
            'is_active' => true,
        ]);

        $response = $this->actingAs($magasinier)->get('/users');

        // Check if access is forbidden
        $response->assertStatus(403);
    }

    /**
     * Test deactivated user cannot log in.
     */
    public function test_deactivated_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/'); // Redirection back (session guard redirects back to login)
        $this->assertGuest();
    }

    /**
     * Test profile update details.
     */
    public function test_user_can_update_profile_details(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->post('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    /**
     * Test profile picture uploading.
     */
    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $file,
        ]);

        $response->assertSessionHas('success');
        $user->refresh();

        $this->assertNotNull($user->profile_picture);
        Storage::disk('public')->assertExists($user->profile_picture);
    }

    /**
     * Test password change verification.
     */
    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->post('/profile/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
    }

    public function test_another_chef_cannot_edit_protected_chef_account(): void
    {
        $superChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => true,
        ]);

        $otherChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $response = $this->actingAs($otherChef)->get('/users/' . $superChef->id . '/edit');
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You cannot edit the protected Chef Magasinier account.');

        $response = $this->actingAs($otherChef)->put('/users/' . $superChef->id, [
            'name' => 'Attempted Change',
            'email' => 'attempt@example.com',
            'role' => 'Chef Magasinier',
            'is_active' => true,
        ]);
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You cannot edit the protected Chef Magasinier account.');
    }

    public function test_cannot_deactivate_or_delete_protected_chef_account(): void
    {
        $superChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => true,
        ]);

        $otherChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $response = $this->actingAs($otherChef)->post('/users/' . $superChef->id . '/toggle-status');
        $response->assertSessionHas('error', 'You cannot deactivate the protected Chef Magasinier account.');
        $superChef->refresh();
        $this->assertTrue($superChef->is_active);

        $response = $this->actingAs($otherChef)->delete('/users/' . $superChef->id);
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You cannot delete the protected Chef Magasinier account.');
        $this->assertDatabaseHas('users', ['id' => $superChef->id]);
    }

    public function test_protected_chef_cannot_deactivate_or_change_role_of_own_account(): void
    {
        $superChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => true,
        ]);

        $response = $this->actingAs($superChef)->put('/users/' . $superChef->id, [
            'name' => 'Super Name',
            'email' => 'super@example.com',
            'role' => 'Magasinier',
            'is_active' => true,
        ]);
        $response->assertSessionHasErrors(['role']);

        $response = $this->actingAs($superChef)->put('/users/' . $superChef->id, [
            'name' => 'Super Name',
            'email' => 'super@example.com',
            'role' => 'Chef Magasinier',
            'is_active' => false,
        ]);
        $response->assertSessionHasErrors(['is_active']);

        $response = $this->actingAs($superChef)->delete('/users/' . $superChef->id);
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You cannot delete the protected Chef Magasinier account.');
        $this->assertDatabaseHas('users', ['id' => $superChef->id]);
    }

    public function test_protected_chef_can_update_own_information(): void
    {
        $superChef = User::factory()->create([
            'name' => 'Super Chef Old Name',
            'email' => 'superold@example.com',
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => true,
        ]);

        $response = $this->actingAs($superChef)->put('/users/' . $superChef->id, [
            'name' => 'Super Chef New Name',
            'email' => 'supernew@example.com',
            'role' => 'Chef Magasinier',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/users');
        $response->assertSessionHas('success');

        $response = $this->actingAs($superChef)->get('/users/' . $superChef->id . '/edit');
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $superChef->id,
            'name' => 'Super Chef New Name',
            'email' => 'supernew@example.com',
        ]);
    }

    public function test_chef_cannot_manage_another_chef(): void
    {
        $chefA = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $chefB = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $response = $this->actingAs($chefA)->get('/users/' . $chefB->id . '/edit');
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You do not have permission to edit this user.');

        $response = $this->actingAs($chefA)->put('/users/' . $chefB->id, [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'Chef Magasinier',
            'is_active' => true,
        ]);
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You do not have permission to edit this user.');

        $response = $this->actingAs($chefA)->delete('/users/' . $chefB->id);
        $response->assertRedirect('/users');
        $response->assertSessionHas('error', 'You do not have permission to delete this user.');

        $response = $this->actingAs($chefA)->post('/users/' . $chefB->id . '/toggle-status');
        $response->assertSessionHas('error', 'You do not have permission to deactivate this user.');
    }

    public function test_chef_can_manage_magasinier(): void
    {
        $chef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $magasinier = User::factory()->create([
            'role' => 'Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $response = $this->actingAs($chef)->get('/users/' . $magasinier->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($chef)->put('/users/' . $magasinier->id, [
            'name' => 'Updated Magasinier',
            'email' => 'updatedmag@example.com',
            'role' => 'Magasinier',
            'is_active' => true,
        ]);
        $response->assertRedirect('/users');
        $response->assertSessionHas('success');

        $response = $this->actingAs($chef)->post('/users/' . $magasinier->id . '/toggle-status');
        $response->assertSessionHas('success');

        $response = $this->actingAs($chef)->delete('/users/' . $magasinier->id);
        $response->assertRedirect('/users');
        $response->assertSessionHas('success');
    }

    public function test_super_chef_can_manage_standard_chef(): void
    {
        $superChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => true,
        ]);

        $standardChef = User::factory()->create([
            'role' => 'Chef Magasinier',
            'is_active' => true,
            'is_super_chef_magasinier' => false,
        ]);

        $response = $this->actingAs($superChef)->get('/users/' . $standardChef->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($superChef)->put('/users/' . $standardChef->id, [
            'name' => 'Updated Chef',
            'email' => 'updatedchef@example.com',
            'role' => 'Chef Magasinier',
            'is_active' => true,
        ]);
        $response->assertRedirect('/users');
        $response->assertSessionHas('success');

        $response = $this->actingAs($superChef)->post('/users/' . $standardChef->id . '/toggle-status');
        $response->assertSessionHas('success');

        $response = $this->actingAs($superChef)->delete('/users/' . $standardChef->id);
        $response->assertRedirect('/users');
        $response->assertSessionHas('success');
    }
}
