<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Material;
use App\Models\Espace;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest redirection to login page.
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test login page returns successful status.
     */
    public function test_guests_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Inventory Portal');
    }

    /**
     * Test user authentication logic.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test dashboard displays counts.
     */
    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        // Seed some data
        $category = Category::create(['title' => 'Cables']);
        $material = Material::create([
            'name' => 'Fiber Optic',
            'ref' => 'REF-FIB-01',
            'category_id' => $category->id
        ]);
        $espace = Espace::create(['title' => 'Magasin']);
        Article::create([
            'li_ref' => 'LI-001',
            'material_id' => $material->id,
            'espace_id' => $espace->id
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Fiber Optic');
        $response->assertSee('Magasin');
    }

    /**
     * Test category creation.
     */
    public function test_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/categories', [
            'title' => 'New Category',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['title' => 'New Category']);
    }

    /**
     * Test material creation.
     */
    public function test_user_can_create_material(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['title' => 'Category A']);

        $response = $this->actingAs($user)->post('/materials', [
            'name' => 'Steel Rod',
            'ref' => 'REF-STL-01',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect('/materials');
        $this->assertDatabaseHas('materials', [
            'name' => 'Steel Rod',
            'ref' => 'REF-STL-01',
        ]);
    }

    /**
     * Test article creation.
     */
    public function test_user_can_create_article(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['title' => 'Category A']);
        $material = Material::create([
            'name' => 'Steel Rod',
            'ref' => 'REF-STL-01',
            'category_id' => $category->id,
        ]);
        $espace = Espace::create(['title' => 'Pole GC']);

        $response = $this->actingAs($user)->post('/articles', [
            'li_ref' => 'LI-REF-UNIQUE',
            'material_id' => $material->id,
            'espace_id' => $espace->id,
        ]);

        $response->assertRedirect('/articles');
        $this->assertDatabaseHas('articles', [
            'li_ref' => 'LI-REF-UNIQUE',
            'material_id' => $material->id,
            'espace_id' => $espace->id,
        ]);
    }

    /**
     * Test listing materials filtered by category route.
     */
    public function test_user_can_view_materials_filtered_by_category(): void
    {
        $user = User::factory()->create();
        $catA = Category::create(['title' => 'Electric']);
        $catB = Category::create(['title' => 'Mechanical']);

        $matA = Material::create([
            'name' => 'Copper Cable',
            'ref' => 'REF-COP-01',
            'category_id' => $catA->id
        ]);
        $matB = Material::create([
            'name' => 'Steel Pipe',
            'ref' => 'REF-STL-02',
            'category_id' => $catB->id
        ]);

        $response = $this->actingAs($user)->get("/categories/{$catA->id}/materials");

        $response->assertStatus(200);
        $response->assertSee('Copper Cable');
        $response->assertDontSee('Steel Pipe');
        
        // Also check if add material button link is prefilled
        $response->assertSee(route('materials.create', ['category_id' => $catA->id]));
    }

    /**
     * Test bulk article creation view returns 200.
     */
    public function test_user_can_view_bulk_create_articles_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/articles/bulk-create');
        $response->assertStatus(200);
        $response->assertSee('Bulk Create Articles');
    }

    /**
     * Test successful bulk article creation.
     */
    public function test_user_can_bulk_create_articles(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['title' => 'Category A']);
        $material = Material::create([
            'name' => 'Steel Rod',
            'ref' => 'REF-STL-01',
            'category_id' => $category->id,
        ]);
        $espace = Espace::create(['title' => 'Magasin']);

        // Check pre-existing article to test skip logic
        Article::create([
            'li_ref' => '1235',
            'material_id' => $material->id,
            'espace_id' => $espace->id,
        ]);

        $response = $this->actingAs($user)->post('/articles/bulk-create', [
            'material_id' => $material->id,
            'espace_id' => $espace->id,
            'start_ref' => 1234,
            'end_ref' => 1236, // range: 1234, 1235 (exists), 1236 -> creates 2, skips 1
        ]);

        $response->assertRedirect('/articles');
        // Flash message checks
        $response->assertSessionHas('success', 'Successfully created 2 articles. 1 existing articles were skipped to prevent duplication.');

        $this->assertDatabaseHas('articles', ['li_ref' => '1234']);
        $this->assertDatabaseHas('articles', ['li_ref' => '1236']);
    }

    /**
     * Test bulk article creation validation.
     */
    public function test_bulk_create_validation_checks(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['title' => 'Category A']);
        $material = Material::create([
            'name' => 'Steel Rod',
            'ref' => 'REF-STL-01',
            'category_id' => $category->id,
        ]);
        $espace = Espace::create(['title' => 'Magasin']);

        // 1. Validation error if start_ref > end_ref
        $response = $this->actingAs($user)->post('/articles/bulk-create', [
            'material_id' => $material->id,
            'espace_id' => $espace->id,
            'start_ref' => 100,
            'end_ref' => 50,
        ]);
        $response->assertSessionHasErrors(['end_ref']);

        // 2. Validation error if range exceeds 500
        $response = $this->actingAs($user)->post('/articles/bulk-create', [
            'material_id' => $material->id,
            'espace_id' => $espace->id,
            'start_ref' => 100,
            'end_ref' => 700,
        ]);
        $response->assertSessionHasErrors(['end_ref']);
    }
}
