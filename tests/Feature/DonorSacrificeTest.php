<?php

namespace Tests\Feature;

use App\Models\Sacrifice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorSacrificeTest extends TestCase
{
    use RefreshDatabase;

    // ── Home page ─────────────────────────────────────────────────────────────

    public function test_home_page_is_accessible(): void
    {
        $this->get(route('home'))->assertStatus(200);
    }

    // ── Search ────────────────────────────────────────────────────────────────

    public function test_search_requires_reference_code(): void
    {
        $this->post(route('sacrifice.search'), ['reference_code' => ''])
            ->assertSessionHasErrors('reference_code');
    }

    public function test_search_reference_code_max_length_is_20(): void
    {
        $this->post(route('sacrifice.search'), ['reference_code' => str_repeat('A', 21)])
            ->assertSessionHasErrors('reference_code');
    }

    public function test_search_with_unknown_reference_code_shows_error(): void
    {
        $this->post(route('sacrifice.search'), ['reference_code' => 'NPC-2025-999'])
            ->assertSessionHas('error');
    }

    public function test_search_with_valid_reference_code_redirects_to_detail(): void
    {
        $sacrifice = Sacrifice::factory()->create(['reference_code' => 'NPC-2025-001']);

        $this->post(route('sacrifice.search'), ['reference_code' => 'NPC-2025-001'])
            ->assertRedirect(route('sacrifice.show', $sacrifice->public_slug));
    }

    public function test_search_reference_code_is_case_insensitive(): void
    {
        $sacrifice = Sacrifice::factory()->create(['reference_code' => 'NPC-2025-001']);

        $this->post(route('sacrifice.search'), ['reference_code' => 'npc-2025-001'])
            ->assertRedirect(route('sacrifice.show', $sacrifice->public_slug));
    }

    // ── Detail page & tabs ────────────────────────────────────────────────────

    public function test_sacrifice_detail_page_is_accessible(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->get(route('sacrifice.show', $sacrifice->public_slug))
            ->assertStatus(200);
    }

    public function test_sacrifice_profile_tab_is_accessible(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->get(route('sacrifice.profile', $sacrifice->public_slug))
            ->assertStatus(200);
    }

    public function test_sacrifice_progress_tab_is_accessible(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->get(route('sacrifice.progress', $sacrifice->public_slug))
            ->assertStatus(200);
    }

    public function test_sacrifice_gallery_tab_is_accessible(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->get(route('sacrifice.gallery', $sacrifice->public_slug))
            ->assertStatus(200);
    }

    public function test_sacrifice_certificate_tab_is_accessible(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->get(route('sacrifice.certificate', $sacrifice->public_slug))
            ->assertStatus(200);
    }

    public function test_sacrifice_detail_with_nonexistent_slug_returns_404(): void
    {
        $validFormatSlug = 'sac_' . str_repeat('a', 28);

        $this->get(route('sacrifice.show', $validFormatSlug))
            ->assertStatus(404);
    }

    public function test_sacrifice_detail_with_invalid_slug_format_returns_404(): void
    {
        $this->get('/sacrifice/invalid-slug')->assertStatus(404);
    }

    // ── Detail page increments access count ───────────────────────────────────

    public function test_viewing_detail_increments_access_count(): void
    {
        $sacrifice = Sacrifice::factory()->create();

        $this->get(route('sacrifice.show', $sacrifice->public_slug));

        $this->assertEquals(1, $sacrifice->fresh()->access_count);
    }

    // ── Certificate download ──────────────────────────────────────────────────

    public function test_download_certificate_returns_403_when_not_generated(): void
    {
        $sacrifice = Sacrifice::factory()->create(['certificate_generated_at' => null]);

        $this->get(route('sacrifice.certificate.download', $sacrifice->public_slug))
            ->assertStatus(403);
    }

    public function test_print_certificate_returns_403_when_not_generated(): void
    {
        $sacrifice = Sacrifice::factory()->create(['certificate_generated_at' => null]);

        $this->get(route('sacrifice.certificate.print', $sacrifice->public_slug))
            ->assertStatus(403);
    }
}
