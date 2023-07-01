<?php

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\JWTAuth as JWTAuthJWTAuth;

test('usuário pode se logar', function () {

    $user = User::factory()->create();

    $response = $this->postJson('/v1/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['authorization']);
});

test('usuário não pode fazer login com credenciais inválidas', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/v1/login', [
        'email' => $user->email,
        'password' => 'senha_incorreta'
    ]);

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);
    $response->assertJson(['message' => 'The login credentials provided are invalid.']);
});

test('usuário pode se deslogar', function () {
    Auth::login(User::factory()->create());

    $response = $this->postJson('/v1/logout');

    $response->assertStatus(200);
    $response->assertJsonStructure(['message']);
    expect($response->json('message'))->toBe("User logged out successfully.");

});

test('usuário não autenticado não pode acessar rota protegida', function () {
    $response = $this->getJson('/v1/user');

    $response->assertStatus(401);
    $response->assertJsonStructure(['message']);
    $response->assertJson(['message' => 'Unauthenticated.']);
});

test('usuário logado obtém informações corretas ao acessar a rota /v1/authUser', function () {
    Auth::login(User::factory()->create());

    $response = $this->getJson('/v1/authUser');

    $response->assertStatus(200);
    $response->assertJson([
        'user' => [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'type' => Auth::user()->type,
            'created_at' => Auth::user()->created_at->toISOString(),
            'updated_at' => Auth::user()->updated_at->toISOString(),
        ]
        ]);
});

test('apenas administradores podem excluir um User', function () {
    // Criar um usuário com papel de administrador
    $admin = User::factory()->isAdmin()->create();

    // Criar um usuário com papel de moderador
    $moderator = User::factory()->isMod()->create();

    // Criar um usuário sem papel de moderador ou administrador
    $regularUser = User::factory()->isRegular()->create();

    // Criar uma despesa associada ao usuário regular
    $regularUserToDelete = User::factory()->isRegular()->create();

    // Autenticar como o usuário administrador
    Auth::login($admin);

    // Tentar excluir a despesa associada ao usuário regular como o usuário administrador
    $response = $this->deleteJson('/v1/user/' . $regularUserToDelete->id);

    // Verificar que a resposta tem status 200 (OK)
    $response->assertStatus(200);

    // Criar uma despesa associada ao usuário regular
    $regularUserToDelete = User::factory()->isRegular()->create();

    // Autenticar como o usuário moderador
    Auth::login($moderator);

    // Tentar excluir a despesa associada ao usuário regular como o usuário moderador
    $response = $this->deleteJson('/v1/user/' . $regularUserToDelete->id);

    // Verificar que a resposta tem status 200 (OK)
    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'You are not allowed to delete users.'
    ]);

    // Criar uma despesa associada ao usuário regular
    $regularUserToDelete = User::factory()->isRegular()->create();

    // Autenticar como o usuário regular
    Auth::login($regularUser);

    // Tentar excluir a despesa associada ao usuário regular como o usuário regular
    $response = $this->deleteJson('/v1/user/' . $regularUserToDelete->id);

    // Verificar que a resposta tem status 403 (Forbidden)
    $response->assertStatus(403);
    $response->assertJson([
        'message' => 'You are not allowed to delete users.'
    ]);
});

test('login retorna erros de validação quando os campos obrigatórios estão ausentes', function () {
    User::factory()->create();

    $response = $this->postJson(route('login'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
    $response->assertJson(['message' => 'The email field is required. (and 1 more error)']);
});
