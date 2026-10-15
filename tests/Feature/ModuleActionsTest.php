<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Module;
use App\Models\Training;
use App\Models\TrainingMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ModuleActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_rename_and_delete_only_the_selected_module(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['type' => 'admin', 'status' => true]);
        $training = Training::create(['title' => 'Course', 'user_id' => $admin->id, 'active' => true]);
        $media = TrainingMedia::create(['type' => 'video/mp4', 'path' => 'media/lesson.mp4', 'size' => '10']);
        Storage::disk('public')->put($media->path, 'sample');
        $module = Module::create(['title' => 'Lesson', 'training_id' => $training->id, 'media_id' => $media->id, 'user_id' => $admin->id]);
        $media->update(['module_id' => $module->id]);
        $chapter = Chapter::create(['title' => 'Intro', 'module_id' => $module->id, 'user_id' => $admin->id, 'date' => '0:00']);

        $this->actingAs($admin)->put(route('module.update', $module), ['title' => 'Renamed'])->assertRedirect(route('training.module_show', [$training, $module]));
        $this->assertDatabaseHas('modules', ['id' => $module->id, 'title' => 'Renamed']);
        Storage::disk('public')->assertExists($media->path);
        $this->actingAs($admin)->put(route('module.update', $module), [
            'title' => 'Replacement',
            'media' => UploadedFile::fake()->create('replacement.mp4', 30 * 1024, 'video/mp4'),
        ])->assertRedirect();
        $oldPath = $media->path;
        $media->refresh();
        $this->assertNotSame($oldPath, $media->path);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($media->path);
        $this->assertDatabaseHas('chapters', ['id' => $chapter->id]);
        $this->actingAs($admin)->putJson(route('module.update', $module), ['title' => ''])->assertUnprocessable();
        $this->actingAs($admin)->delete(route('module.destroy', $module))->assertRedirect(route('training.show', $training));
        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
        $this->assertDatabaseMissing('training_media', ['id' => $media->id]);
        $this->assertDatabaseHas('trainings', ['id' => $training->id]);
        Storage::disk('public')->assertMissing($media->path);
    }

    public function test_regular_user_cannot_use_module_actions(): void
    {
        $user = User::factory()->create(['type' => 'user', 'status' => true]);
        $training = Training::create(['title' => 'Course', 'user_id' => $user->id, 'active' => true]);
        $media = TrainingMedia::create(['type' => 'video/mp4', 'path' => 'media/lesson.mp4', 'size' => '10']);
        $module = Module::create(['title' => 'Lesson', 'training_id' => $training->id, 'media_id' => $media->id, 'user_id' => $user->id]);
        $this->actingAs($user)->put(route('module.update', $module), ['title' => 'Not allowed'])->assertRedirect();
        $this->actingAs($user)->delete(route('module.destroy', $module))->assertRedirect();
        $this->assertDatabaseHas('modules', ['id' => $module->id, 'title' => 'Lesson']);
    }
}
