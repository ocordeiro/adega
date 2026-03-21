<?php

namespace Tests\Feature\Api;

use App\Models\Ad;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdApiTest extends TestCase
{
    private string $token = 'test-api-token';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.api.token' => $this->token]);
        Storage::fake('s3');
    }

    private function apiHeaders(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token];
    }

    // ── Autenticação ────────────────────────────────────────

    public function test_returns_401_without_token(): void
    {
        $response = $this->getJson('/api/v1/anuncios');

        $response->assertStatus(401);
    }

    public function test_returns_401_with_wrong_token(): void
    {
        $response = $this->getJson('/api/v1/anuncios', [
            'Authorization' => 'Bearer wrong-token',
        ]);

        $response->assertStatus(401);
    }

    // ── Listagem ────────────────────────────────────────────

    public function test_returns_empty_when_no_ads(): void
    {
        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_returns_active_video_ads_with_media(): void
    {
        $ad = Ad::create([
            'title'      => 'Promo Verão',
            'media_type' => 'video',
            'is_active'  => true,
            'sort_order' => 1,
        ]);
        $ad->addMedia(UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4'))
            ->toMediaCollection('video', 's3');

        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Promo Verão')
            ->assertJsonPath('data.0.media_type', 'video');

        $this->assertNotEmpty($response->json('data.0.media_url'));
        $this->assertNotEmpty($response->json('data.0.video_url'));
    }

    public function test_returns_active_image_ads_with_media(): void
    {
        $ad = Ad::create([
            'title'            => 'Banner Natal',
            'media_type'       => 'image',
            'display_duration' => 15,
            'is_active'        => true,
            'sort_order'       => 1,
        ]);
        $ad->addMedia(UploadedFile::fake()->image('banner.jpg', 1920, 1080))
            ->toMediaCollection('image', 's3');

        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Banner Natal')
            ->assertJsonPath('data.0.media_type', 'image')
            ->assertJsonPath('data.0.display_duration', 15);

        $this->assertNotEmpty($response->json('data.0.media_url'));
        $this->assertNull($response->json('data.0.video_url'));
    }

    public function test_excludes_inactive_ads(): void
    {
        $ad = Ad::create([
            'title'      => 'Inativo',
            'media_type' => 'video',
            'is_active'  => false,
            'sort_order' => 1,
        ]);
        $ad->addMedia(UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4'))
            ->toMediaCollection('video', 's3');

        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_excludes_ads_without_media(): void
    {
        Ad::create([
            'title'      => 'Sem Mídia',
            'media_type' => 'video',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_ads_are_ordered_by_sort_order(): void
    {
        $ad2 = Ad::create(['title' => 'Segundo', 'media_type' => 'video', 'is_active' => true, 'sort_order' => 2]);
        $ad1 = Ad::create(['title' => 'Primeiro', 'media_type' => 'video', 'is_active' => true, 'sort_order' => 1]);
        $ad3 = Ad::create(['title' => 'Terceiro', 'media_type' => 'image', 'display_duration' => 10, 'is_active' => true, 'sort_order' => 3]);

        $ad2->addMedia(UploadedFile::fake()->create('v2.mp4', 1024, 'video/mp4'))->toMediaCollection('video', 's3');
        $ad1->addMedia(UploadedFile::fake()->create('v1.mp4', 1024, 'video/mp4'))->toMediaCollection('video', 's3');
        $ad3->addMedia(UploadedFile::fake()->image('img.jpg', 800, 600))->toMediaCollection('image', 's3');

        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.title', 'Primeiro')
            ->assertJsonPath('data.1.title', 'Segundo')
            ->assertJsonPath('data.2.title', 'Terceiro');
    }

    public function test_mixed_media_types_returned_correctly(): void
    {
        $video = Ad::create(['title' => 'Vídeo', 'media_type' => 'video', 'is_active' => true, 'sort_order' => 1]);
        $image = Ad::create(['title' => 'Imagem', 'media_type' => 'image', 'display_duration' => 20, 'is_active' => true, 'sort_order' => 2]);

        $video->addMedia(UploadedFile::fake()->create('v.mp4', 1024, 'video/mp4'))->toMediaCollection('video', 's3');
        $image->addMedia(UploadedFile::fake()->image('i.jpg', 800, 600))->toMediaCollection('image', 's3');

        $response = $this->getJson('/api/v1/anuncios', $this->apiHeaders());

        $response->assertOk()->assertJsonCount(2, 'data');

        $data = $response->json('data');

        // Video ad
        $this->assertEquals('video', $data[0]['media_type']);
        $this->assertNotEmpty($data[0]['media_url']);
        $this->assertNotEmpty($data[0]['video_url']);
        $this->assertNull($data[0]['display_duration']);

        // Image ad
        $this->assertEquals('image', $data[1]['media_type']);
        $this->assertNotEmpty($data[1]['media_url']);
        $this->assertNull($data[1]['video_url']);
        $this->assertEquals(20, $data[1]['display_duration']);
    }
}
