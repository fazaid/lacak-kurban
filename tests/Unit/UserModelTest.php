<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    // ── Role checks ───────────────────────────────────────────────────────────

    public function test_admin_role_checks(): void
    {
        $user = new User(['role' => 'admin']);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isStaff());
        $this->assertFalse($user->isViewer());
    }

    public function test_staff_role_checks(): void
    {
        $user = new User(['role' => 'staff']);

        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isStaff());
        $this->assertFalse($user->isViewer());
    }

    public function test_viewer_role_checks(): void
    {
        $user = new User(['role' => 'viewer']);

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isStaff());
        $this->assertTrue($user->isViewer());
    }

    // ── canWrite ──────────────────────────────────────────────────────────────

    public function test_admin_can_write(): void
    {
        $this->assertTrue((new User(['role' => 'admin']))->canWrite());
    }

    public function test_staff_can_write(): void
    {
        $this->assertTrue((new User(['role' => 'staff']))->canWrite());
    }

    public function test_viewer_cannot_write(): void
    {
        $this->assertFalse((new User(['role' => 'viewer']))->canWrite());
    }

    // ── canDelete ─────────────────────────────────────────────────────────────

    public function test_only_admin_can_delete(): void
    {
        $this->assertTrue((new User(['role' => 'admin']))->canDelete());
        $this->assertFalse((new User(['role' => 'staff']))->canDelete());
        $this->assertFalse((new User(['role' => 'viewer']))->canDelete());
    }

    // ── canExport ─────────────────────────────────────────────────────────────

    public function test_only_admin_can_export(): void
    {
        $this->assertTrue((new User(['role' => 'admin']))->canExport());
        $this->assertFalse((new User(['role' => 'staff']))->canExport());
        $this->assertFalse((new User(['role' => 'viewer']))->canExport());
    }

    // ── roleLabel ─────────────────────────────────────────────────────────────

    public function test_role_label_admin(): void
    {
        $this->assertEquals('Admin', (new User(['role' => 'admin']))->roleLabel());
    }

    public function test_role_label_staff(): void
    {
        $this->assertEquals('Staff', (new User(['role' => 'staff']))->roleLabel());
    }

    public function test_role_label_viewer(): void
    {
        $this->assertEquals('Viewer', (new User(['role' => 'viewer']))->roleLabel());
    }

    // ── roleBadgeClass ────────────────────────────────────────────────────────

    public function test_admin_badge_contains_green_color(): void
    {
        $this->assertStringContainsString('1D9E75', (new User(['role' => 'admin']))->roleBadgeClass());
    }

    public function test_staff_badge_contains_blue(): void
    {
        $this->assertStringContainsString('blue', (new User(['role' => 'staff']))->roleBadgeClass());
    }

    public function test_viewer_badge_contains_gray(): void
    {
        $this->assertStringContainsString('gray', (new User(['role' => 'viewer']))->roleBadgeClass());
    }
}
