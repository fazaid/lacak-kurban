<?php

namespace Tests\Feature;

use App\Models\Sacrifice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSacrificeTest extends TestCase
{
    use RefreshDatabase;

    // ── Access control ────────────────────────────────────────────────────────

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    public function test_staff_can_access_dashboard(): void
    {
        $this->actingAs($this->staff())
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    public function test_viewer_can_access_dashboard(): void
    {
        $this->actingAs($this->viewer())
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
    }

    // ── Sacrifice list ────────────────────────────────────────────────────────

    public function test_all_roles_can_view_sacrifice_list(): void
    {
        foreach ([$this->admin(), $this->staff(), $this->viewer()] as $user) {
            $this->actingAs($user)
                ->get(route('admin.sacrifices.index'))
                ->assertStatus(200);
        }
    }

    // ── Create sacrifice ──────────────────────────────────────────────────────

    public function test_viewer_cannot_access_create_page(): void
    {
        $this->actingAs($this->viewer())
            ->get(route('admin.sacrifices.create'))
            ->assertStatus(403);
    }

    public function test_admin_can_access_create_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.sacrifices.create'))
            ->assertStatus(200);
    }

    public function test_staff_can_access_create_page(): void
    {
        $this->actingAs($this->staff())
            ->get(route('admin.sacrifices.create'))
            ->assertStatus(200);
    }

    public function test_admin_can_store_sacrifice(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.sacrifices.store'), $this->validSacrificeData())
            ->assertRedirect();

        $this->assertDatabaseHas('sacrifices', ['donor_name' => 'Ahmad Rizky']);
    }

    public function test_staff_can_store_sacrifice(): void
    {
        $this->actingAs($this->staff())
            ->post(route('admin.sacrifices.store'), $this->validSacrificeData(['donor_name' => 'Budi Santoso']))
            ->assertRedirect();

        $this->assertDatabaseHas('sacrifices', ['donor_name' => 'Budi Santoso']);
    }

    public function test_viewer_cannot_store_sacrifice(): void
    {
        $this->actingAs($this->viewer())
            ->post(route('admin.sacrifices.store'), $this->validSacrificeData())
            ->assertStatus(403);
    }

    public function test_store_requires_donor_name(): void
    {
        $data = $this->validSacrificeData(['donor_name' => '']);

        $this->actingAs($this->admin())
            ->post(route('admin.sacrifices.store'), $data)
            ->assertSessionHasErrors('donor_name');
    }

    public function test_store_rejects_unta_for_nusantara(): void
    {
        $data = $this->validSacrificeData([
            'sacrifice_type' => 'nusantara',
            'animal_type' => 'unta',
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.sacrifices.store'), $data)
            ->assertSessionHasErrors('animal_type');
    }

    public function test_store_rejects_domba_with_collective_sharing(): void
    {
        $data = $this->validSacrificeData([
            'animal_type' => 'domba',
            'sharing_type' => 'collective',
            'share_ratio' => 1,
        ]);

        $this->actingAs($this->admin())
            ->post(route('admin.sacrifices.store'), $data)
            ->assertSessionHasErrors('sharing_type');
    }

    // ── Show & edit sacrifice ─────────────────────────────────────────────────

    public function test_all_roles_can_view_sacrifice_detail(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        foreach ([$this->admin(), $this->staff(), $this->viewer()] as $user) {
            $this->actingAs($user)
                ->get(route('admin.sacrifices.show', $sacrifice))
                ->assertStatus(200);
        }
    }

    public function test_admin_can_edit_sacrifice(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->admin())
            ->get(route('admin.sacrifices.edit', $sacrifice))
            ->assertStatus(200);
    }

    public function test_viewer_cannot_edit_sacrifice(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->viewer())
            ->get(route('admin.sacrifices.edit', $sacrifice))
            ->assertStatus(403);
    }

    // ── Delete sacrifice ──────────────────────────────────────────────────────

    public function test_admin_can_delete_sacrifice(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.sacrifices.destroy', $sacrifice))
            ->assertRedirect(route('admin.sacrifices.index'));

        $this->assertDatabaseMissing('sacrifices', ['id' => $sacrifice->id]);
    }

    public function test_staff_cannot_delete_sacrifice(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->staff())
            ->delete(route('admin.sacrifices.destroy', $sacrifice))
            ->assertStatus(403);

        $this->assertDatabaseHas('sacrifices', ['id' => $sacrifice->id]);
    }

    public function test_viewer_cannot_delete_sacrifice(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->viewer())
            ->delete(route('admin.sacrifices.destroy', $sacrifice))
            ->assertStatus(403);
    }

    // ── Update progress ───────────────────────────────────────────────────────

    public function test_admin_can_update_progress(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->admin())
            ->put(route('admin.sacrifices.progress.update', $sacrifice), [
                'status_purchase' => 'completed',
                'date_purchase_completed' => '2025-06-01',
                'status_slaughter' => 'pending',
                'date_slaughter_completed' => null,
                'status_on_way' => 'pending',
                'date_on_way_completed' => null,
                'status_distribution' => 'pending',
                'date_distribution_completed' => null,
                'status_report' => 'pending',
                'date_report_completed' => null,
            ])
            ->assertRedirect(route('admin.sacrifices.show', $sacrifice));

        $this->assertEquals('completed', $sacrifice->fresh()->status_purchase);
    }

    public function test_viewer_cannot_update_progress(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->actingAs($this->viewer())
            ->get(route('admin.sacrifices.progress.edit', $sacrifice))
            ->assertStatus(403);
    }

    // ── Certificate generation ────────────────────────────────────────────────

    public function test_admin_can_generate_certificate(): void
    {
        $sacrifice = Sacrifice::factory()->create(['certificate_generated_at' => null]);

        $this->actingAs($this->admin())
            ->post(route('admin.sacrifices.certificate.generate', $sacrifice));

        $this->assertNotNull($sacrifice->fresh()->certificate_generated_at);
    }

    public function test_viewer_cannot_generate_certificate(): void
    {
        $sacrifice = Sacrifice::factory()->create(['certificate_generated_at' => null]);

        $this->actingAs($this->viewer())
            ->post(route('admin.sacrifices.certificate.generate', $sacrifice))
            ->assertStatus(403);

        $this->assertNull($sacrifice->fresh()->certificate_generated_at);
    }

    // ── Search filter ─────────────────────────────────────────────────────────

    public function test_search_filter_shows_matching_donor(): void
    {
        Sacrifice::factory()->create(['donor_name' => 'Ahmad Rizky']);
        Sacrifice::factory()->create(['donor_name' => 'Budi Santoso']);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.sacrifices.index', ['search' => 'Ahmad']));

        $response->assertStatus(200)
            ->assertSee('Ahmad Rizky')
            ->assertDontSee('Budi Santoso');
    }

    public function test_sacrifice_type_filter_works(): void
    {
        Sacrifice::factory()->create(['sacrifice_type' => 'palestina', 'donor_name' => 'Donor Palestina']);
        Sacrifice::factory()->create(['sacrifice_type' => 'nusantara', 'donor_name' => 'Donor Nusantara']);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.sacrifices.index', ['sacrifice_type' => 'palestina']));

        $response->assertStatus(200)
            ->assertSee('Donor Palestina')
            ->assertDontSee('Donor Nusantara');
    }

    // ── Reference code auto-generation ───────────────────────────────────────

    public function test_reference_code_is_auto_generated_on_store(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.sacrifices.store'), $this->validSacrificeData());

        $sacrifice = Sacrifice::where('donor_name', 'Ahmad Rizky')->first();
        $this->assertNotNull($sacrifice);
        $this->assertMatchesRegularExpression('/^NPC-\d{4}-\d{3}$/', $sacrifice->reference_code);
    }

    public function test_public_slug_is_auto_generated_on_create(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->assertNotNull($sacrifice->public_slug);
        $this->assertTrue(Sacrifice::isValidSlug($sacrifice->public_slug));
    }

    // ── Export ────────────────────────────────────────────────────────────────

    public function test_admin_can_export_csv(): void
    {
        Sacrifice::factory()->count(3)->create();

        $this->actingAs($this->admin())
            ->get(route('admin.export'))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_staff_cannot_export_csv(): void
    {
        $this->actingAs($this->staff())
            ->get(route('admin.export'))
            ->assertStatus(403);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function staff(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    private function viewer(): User
    {
        return User::factory()->create(['role' => 'viewer']);
    }

    private function validSacrificeData(array $overrides = []): array
    {
        return array_merge([
            'sacrifice_type' => 'nusantara',
            'donor_name' => 'Ahmad Rizky',
            'donor_email' => 'ahmad@example.com',
            'donor_phone' => '08123456789',
            'animal_type' => 'sapi',
            'animal_price' => 15_000_000,
            'sharing_type' => 'full',
            'share_ratio' => 1,
        ], $overrides);
    }
}
