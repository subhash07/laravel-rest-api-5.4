<?php

namespace Tests\Feature;

use App\Models\Cms;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    public function testsArticlesAreCreatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'title' => 'Lorem',
            'content' => 'Ipsum',
        ];

        $this->json('POST', '/api/cms', $payload, $headers)
            ->assertStatus(200)
            ->assertJson([ 'id' => 1, 'title' => 'Lorem', 'body' => 'Ipsum' ]);
    }

    public function testsArticlesAreUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $article = factory(Cms::class)->create([
            'title' => 'First Article',
            'content' => 'First Body',
        ]);

        $payload = [
            'title' => 'Lorem',
            'content' => 'Ipsum',
        ];

        $response = $this->json('PUT', '/api/cms/' . $article->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJson([ 'id' => 1, 'title' => 'Lorem', 'content' => 'Ipsum' ]);
    }

    public function testsArtilcesAreDeletedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $article = factory(Cms::class)->create([
            'title' => 'First Cms',
            'content' => 'First Body',
        ]);

        $this->json('DELETE', '/api/cms/' . $article->id, [], $headers)
            ->assertStatus(204);
    }

    public function testArticlesAreListedCorrectly()
    {
        factory(Article::class)->create([
            'title' => 'First Article',
            'content' => 'First Body'
        ]);

        factory(Article::class)->create([
            'title' => 'Second Article',
            'content' => 'Second Body'
        ]);

        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', '/api/cms', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                [ 'title' => 'First Article', 'content' => 'First Body' ],
                [ 'title' => 'Second Article', 'content' => 'Second Body' ]
            ])
            ->assertJsonStructure([
                '*' => ['id', 'content', 'title', 'created_at', 'updated_at'],
            ]);
    }

    public function testUserCantAccessArticlesWithWrongToken()
    {
        factory(Article::class)->create();
        $user = factory(User::class)->create([ 'email' => 'user@test.com' ]);
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $user->generateToken();

        $this->json('get', '/api/cms', [], $headers)->assertStatus(401);
    }

    public function testUserCantAccessArticlesWithoutToken()
    {
        factory(Article::class)->create();

        $this->json('get', '/api/cms')->assertStatus(401);
    }
}
