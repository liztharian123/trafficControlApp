<?php

use App\Models\Quote;
use App\Models\User;

test('permit user cannot reach the quotes module', function () {
    $this->actingAs(User::factory()->permit()->create())
         ->get(route('quotes.index'))->assertForbidden();
});

test('a quote user cannot delete a colleague\'s quote', function () {
    $owner = User::factory()->quote()->create();
    $other = User::factory()->quote()->create();
    $quote = Quote::factory()->for($owner, 'creator')->create();

    $this->actingAs($other)->delete(route('quotes.destroy', $quote))->assertForbidden();
});
