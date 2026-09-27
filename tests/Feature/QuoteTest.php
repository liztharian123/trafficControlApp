<?php

use App\Models\Client;
use App\Models\Quote;
use App\Models\User;

test('guest is redirected to login from quotes', fn () =>
    $this->get(route('quotes.index'))->assertRedirect(route('login')));

test('quote user can create a quote', function () {
    $user = User::factory()->quote()->create();
    $client = Client::factory()->create();

    $this->actingAs($user)->post(route('quotes.store'), [
        'client_id' => $client->id, 'reference_no' => 'Q-1', 'site_address' => 'x',
        'description' => 'y', 'start_date' => '2026-10-01', 'end_date' => '2026-10-02',
        'amount' => 1500, 'status' => 'draft',
    ])->assertRedirect();

    $this->assertDatabaseHas('quotes', ['reference_no' => 'Q-1', 'created_by' => $user->id]);
});

test('negative amount is rejected', function () {
    $user  = User::factory()->quote()->create();
    $valid = Quote::factory()->make(['created_by' => $user->id])->toArray();

    $this->actingAs($user)
         ->post(route('quotes.store'), array_merge($valid, ['amount' => -5]))
         ->assertSessionHasErrors('amount');

    $this->assertDatabaseCount('quotes', 0);
});
