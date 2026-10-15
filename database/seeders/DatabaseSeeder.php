<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Training;
use App\Models\TrainingMedia;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function formatFileSize(int|float $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes <= 0) {
            return '0 B';
        }

        $factor = floor(log($bytes, 1024));

        return sprintf(
            "%.{$decimals}f %s",
            $bytes / (1024 ** $factor),
            $units[$factor]
        );
    }

    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'user',
            'email' => 'user@user.com',
        ]);

        User::factory()->create([
            'name' => 'superAdmin',
            'email' => 'superAdmin@superAdmain.com',
            'type' => 'superAdmin',
        ]);

        $training_id = 1;
        $training_image = 'https://plus.unsplash.com/premium_photo-1663075847012-c781e0d194ce?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjR8fHRyYWluaW5nfGVufDB8fDB8fHww';
        Training::factory()->create([
            'title' => 'شرح السيستم',
            'id' => $training_id,
        ]);

        $training_image_response = Http::get($training_image);

        $mediaId = Str::orderedUuid();

        $extension = 'jpg';

        $filename = Str::slug('System Overview')
            . $mediaId
            . '.' . $extension;

        $path = 'training/' . $filename;

        Storage::disk('public')->put(
            $path,
            $training_image_response->body()
        );

        TrainingMedia::create([
            'type' => $training_image_response->header('Content-Type'),
            'size' => $this->formatFileSize(strlen($training_image_response->body()), 2),
            'path' => $path,
            'training_id' => $training_id,
            'module_id' => null,
        ]);

        $module_video =
        [
            [
                'name' => 'بوليصه شحن بضائع شرح سريع.mp4',
                'type' => 'video/mp4',
                'size' => 73998260,
            ],
            [
                'name' => 'تقارير الحسابات العامه علي التحديث الجديد.mp4',
                'type' => 'video/mp4',
                'size' => 248053918,
            ],
            [
                'name' => 'تقارير السندات المحاسبية والنقليات علي التحديث الجديد.mp4',
                'type' => 'video/mp4',
                'size' => 485888840,
            ],
            [
                'name' => 'شرح الصيانة.mp4',
                'type' => 'video/mp4',
                'size' => 351963856,
            ],
            [
                'name' => 'شرح المستودعات.mp4',
                'type' => 'video/mp4',
                'size' => 362214618,
            ],
            [
                'name' => 'شرح الموارد البشرية.mp4',
                'type' => 'video/mp4',
                'size' => 230185594,
            ],
            [
                'name' => 'شرح تسجيل العملاء.mp4',
                'type' => 'video/mp4',
                'size' => 75333035,
            ],
            [
                'name' => 'شرح تسجيل الموردين.mp4',
                'type' => 'video/mp4',
                'size' => 31301396,
            ],
            [
                'name' => 'شرح نقل السيارات بشكل سريع.mp4',
                'type' => 'video/mp4',
                'size' => 158847735,
            ],
            [
                'name' => 'نظرة عامة عن البرنامج.mp4',
                'type' => 'video/mp4',
                'size' => 77999548,
            ],
        ];

        foreach ($module_video as $video) {
            // dd(Str::slug($video['name']));

            $path = 'media/' . $video['name'];

            $media = TrainingMedia::create([
                'type' => $video['type'],
                'path' => $path,
                'size' => $video['size'],
            ]);
            $media_id = $media->id;
            $module = Module::create(
                [
                    'title' => $video['name'],
                    'media_id' => $media_id,
                    'training_id' => $training_id,
                    'user_id' => 1
                ]
            );

            $media['module_id'] = $module->id;
            $media->save();
        }

    }
}
