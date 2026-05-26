<?php

namespace Tests\Unit;

use App\Models\Sacrifice;
use Tests\TestCase;

class SacrificeModelTest extends TestCase
{
    // ── getProgressPercentage ────────────────────────────────────────────────

    public function test_progress_is_zero_when_all_pending(): void
    {
        $sacrifice = $this->makeSacrifice('pending');

        $this->assertEquals(0, $sacrifice->getProgressPercentage());
    }

    public function test_progress_is_100_when_all_completed(): void
    {
        $sacrifice = $this->makeSacrifice('completed');

        $this->assertEquals(100, $sacrifice->getProgressPercentage());
    }

    public function test_progress_increments_20_per_completed_step(): void
    {
        $sacrifice = $this->makeSacrifice('pending');
        $sacrifice->status_purchase = 'completed';

        $this->assertEquals(20, $sacrifice->getProgressPercentage());
    }

    public function test_progress_is_60_when_three_steps_completed(): void
    {
        $sacrifice = $this->makeSacrifice('pending');
        $sacrifice->status_purchase = 'completed';
        $sacrifice->status_slaughter = 'completed';
        $sacrifice->status_on_way = 'completed';

        $this->assertEquals(60, $sacrifice->getProgressPercentage());
    }

    // ── isFullyCompleted ─────────────────────────────────────────────────────

    public function test_is_fully_completed_when_all_statuses_completed(): void
    {
        $this->assertTrue($this->makeSacrifice('completed')->isFullyCompleted());
    }

    public function test_is_not_fully_completed_when_one_step_pending(): void
    {
        $sacrifice = $this->makeSacrifice('completed');
        $sacrifice->status_report = 'pending';

        $this->assertFalse($sacrifice->isFullyCompleted());
    }

    public function test_is_not_fully_completed_when_all_pending(): void
    {
        $this->assertFalse($this->makeSacrifice('pending')->isFullyCompleted());
    }

    // ── getStatusLabel ───────────────────────────────────────────────────────

    public function test_status_label_is_menunggu_at_zero_percent(): void
    {
        $this->assertEquals('Menunggu', $this->makeSacrifice('pending')->getStatusLabel());
    }

    public function test_status_label_is_selesai_at_100_percent(): void
    {
        $this->assertEquals('Selesai', $this->makeSacrifice('completed')->getStatusLabel());
    }

    public function test_status_label_is_sedang_berjalan_in_progress(): void
    {
        $sacrifice = $this->makeSacrifice('pending');
        $sacrifice->status_purchase = 'completed';

        $this->assertEquals('Sedang Berjalan', $sacrifice->getStatusLabel());
    }

    // ── getStatusColor ───────────────────────────────────────────────────────

    public function test_status_color_is_green_when_completed(): void
    {
        $sacrifice = $this->makeSacrifice('pending');
        $sacrifice->status_purchase = 'completed';

        $this->assertEquals('green', $sacrifice->getStatusColor('purchase'));
    }

    public function test_status_color_is_yellow_when_pending(): void
    {
        $sacrifice = $this->makeSacrifice('pending');

        $this->assertEquals('yellow', $sacrifice->getStatusColor('purchase'));
    }

    public function test_status_color_is_gray_for_unknown_key(): void
    {
        $sacrifice = $this->makeSacrifice('pending');

        // Passing an unknown key means $statusType itself is used as the status value
        $this->assertEquals('gray', $sacrifice->getStatusColor('unknown_key'));
    }

    // ── getSacrificeTypeLabel ─────────────────────────────────────────────────

    public function test_sacrifice_type_label_palestina(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->sacrifice_type = 'palestina';

        $this->assertEquals('Palestina', $sacrifice->getSacrificeTypeLabel());
    }

    public function test_sacrifice_type_label_nusantara(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->sacrifice_type = 'nusantara';

        $this->assertEquals('Nusantara', $sacrifice->getSacrificeTypeLabel());
    }

    // ── getAnimalTypeLabel ────────────────────────────────────────────────────

    public function test_animal_type_label_unta(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->animal_type = 'unta';

        $this->assertEquals('Unta', $sacrifice->getAnimalTypeLabel());
    }

    public function test_animal_type_label_sapi(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->animal_type = 'sapi';

        $this->assertEquals('Sapi', $sacrifice->getAnimalTypeLabel());
    }

    public function test_animal_type_label_domba(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->animal_type = 'domba';

        $this->assertEquals('Domba', $sacrifice->getAnimalTypeLabel());
    }

    // ── getValidAnimalsForType ────────────────────────────────────────────────

    public function test_palestina_allows_unta_sapi_domba(): void
    {
        $animals = Sacrifice::getValidAnimalsForType('palestina');

        $this->assertContains('unta', $animals);
        $this->assertContains('sapi', $animals);
        $this->assertContains('domba', $animals);
    }

    public function test_nusantara_excludes_unta(): void
    {
        $animals = Sacrifice::getValidAnimalsForType('nusantara');

        $this->assertNotContains('unta', $animals);
        $this->assertContains('sapi', $animals);
        $this->assertContains('domba', $animals);
    }

    // ── validateAnimalTypeForSacrificeType ────────────────────────────────────

    public function test_unta_valid_for_palestina(): void
    {
        $this->assertTrue(Sacrifice::validateAnimalTypeForSacrificeType('palestina', 'unta'));
    }

    public function test_unta_invalid_for_nusantara(): void
    {
        $this->assertFalse(Sacrifice::validateAnimalTypeForSacrificeType('nusantara', 'unta'));
    }

    public function test_sapi_valid_for_both_types(): void
    {
        $this->assertTrue(Sacrifice::validateAnimalTypeForSacrificeType('palestina', 'sapi'));
        $this->assertTrue(Sacrifice::validateAnimalTypeForSacrificeType('nusantara', 'sapi'));
    }

    // ── validateSharingCombination ────────────────────────────────────────────

    public function test_domba_cannot_be_collective(): void
    {
        $this->assertFalse(Sacrifice::validateSharingCombination('domba', 'collective'));
    }

    public function test_domba_can_be_full(): void
    {
        $this->assertTrue(Sacrifice::validateSharingCombination('domba', 'full'));
    }

    public function test_sapi_can_be_collective(): void
    {
        $this->assertTrue(Sacrifice::validateSharingCombination('sapi', 'collective'));
    }

    public function test_unta_can_be_collective(): void
    {
        $this->assertTrue(Sacrifice::validateSharingCombination('unta', 'collective'));
    }

    // ── getShareRatioForAnimal ────────────────────────────────────────────────

    public function test_share_ratio_unta_is_10(): void
    {
        $this->assertEquals(10, Sacrifice::getShareRatioForAnimal('unta'));
    }

    public function test_share_ratio_sapi_is_7(): void
    {
        $this->assertEquals(7, Sacrifice::getShareRatioForAnimal('sapi'));
    }

    public function test_share_ratio_domba_is_1(): void
    {
        $this->assertEquals(1, Sacrifice::getShareRatioForAnimal('domba'));
    }

    // ── getTotalPrice ─────────────────────────────────────────────────────────

    public function test_total_price_returns_animal_price_as_is(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->animal_price = 10_000_000;

        $this->assertEquals(10_000_000.0, $sacrifice->getTotalPrice());
    }

    public function test_total_price_collective_returns_animal_price_as_is(): void
    {
        // animal_price sudah merupakan kontribusi donatur (misal 1/7 dari harga sapi)
        $sacrifice = new Sacrifice();
        $sacrifice->animal_price = 4_000_000;
        $sacrifice->sharing_type = 'collective';
        $sacrifice->animal_type = 'sapi';

        $this->assertEquals(4_000_000.0, $sacrifice->getTotalPrice());
    }

    public function test_total_price_returns_zero_when_no_price(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->animal_price = null;

        $this->assertEquals(0.0, $sacrifice->getTotalPrice());
    }

    // ── hasCertificate ────────────────────────────────────────────────────────

    public function test_has_certificate_false_when_null(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->certificate_generated_at = null;

        $this->assertFalse($sacrifice->hasCertificate());
    }

    public function test_has_certificate_true_when_set(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->certificate_generated_at = now();

        $this->assertTrue($sacrifice->hasCertificate());
    }

    // ── isValidSlug ───────────────────────────────────────────────────────────

    public function test_valid_slug_with_hex_chars(): void
    {
        $this->assertTrue(Sacrifice::isValidSlug('sac_' . str_repeat('a', 28)));
        $this->assertTrue(Sacrifice::isValidSlug('sac_' . str_repeat('0', 28)));
        $this->assertTrue(Sacrifice::isValidSlug('sac_' . str_repeat('f', 28)));
    }

    public function test_invalid_slug_missing_prefix(): void
    {
        $this->assertFalse(Sacrifice::isValidSlug(str_repeat('a', 32)));
    }

    public function test_invalid_slug_too_short(): void
    {
        $this->assertFalse(Sacrifice::isValidSlug('sac_short'));
    }

    public function test_invalid_slug_non_hex_chars(): void
    {
        $this->assertFalse(Sacrifice::isValidSlug('sac_' . str_repeat('g', 28)));
    }

    // ── getShareInfo ──────────────────────────────────────────────────────────

    public function test_share_info_full(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->sharing_type = 'full';
        $sacrifice->animal_type = 'sapi';

        $info = $sacrifice->getShareInfo();

        $this->assertEquals('full', $info['type']);
        $this->assertEquals('Penuh', $info['label']);
    }

    public function test_share_info_collective_sapi(): void
    {
        $sacrifice = new Sacrifice();
        $sacrifice->sharing_type = 'collective';
        $sacrifice->animal_type = 'sapi';
        $sacrifice->share_ratio = 2;

        $info = $sacrifice->getShareInfo();

        $this->assertEquals('collective', $info['type']);
        $this->assertEquals(2, $info['numerator']);
        $this->assertEquals(7, $info['denominator']);
        $this->assertEquals('Kolektif 2/7', $info['label']);
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function makeSacrifice(string $status): Sacrifice
    {
        $sacrifice = new Sacrifice();
        $sacrifice->status_purchase = $status;
        $sacrifice->status_slaughter = $status;
        $sacrifice->status_on_way = $status;
        $sacrifice->status_distribution = $status;
        $sacrifice->status_report = $status;

        return $sacrifice;
    }
}
