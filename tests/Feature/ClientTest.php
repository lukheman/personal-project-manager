<?php

use App\Models\Client;
use App\Models\Project;

describe('Client Model', function () {

    it('can create a client with valid data', function () {
        $client = Client::factory()->create([
            'name' => 'John Doe',
            'phone' => '081234567890',
        ]);

        expect($client)->toBeInstanceOf(Client::class)
            ->and($client->name)->toBe('John Doe')
            ->and($client->phone)->toBe('081234567890');
    });

    it('can create a client with referral', function () {
        $referrer = Client::factory()->create();
        $client = Client::factory()->create([
            'referred_by_client_id' => $referrer->id,
        ]);

        expect($client->referrer)->toBeInstanceOf(Client::class)
            ->and($client->referrer->id)->toBe($referrer->id);
    });

    it('calculates referral count correctly', function () {
        $referrer = Client::factory()->create();
        Client::factory()->count(3)->create([
            'referred_by_client_id' => $referrer->id,
        ]);

        expect($referrer->referral_count)->toBe(3);
    });

    it('calculates available referral credit correctly', function () {
        $referrer = Client::factory()->create(['referral_credit_used' => 50000]);
        Client::factory()->count(2)->create([
            'referred_by_client_id' => $referrer->id,
        ]);

        // 2 referrals * 100000 - 50000 used = 150000
        expect($referrer->available_referral_credit)->toBe(150000.0);
    });

    it('can use referral credit', function () {
        $referrer = Client::factory()->create(['referral_credit_used' => 0]);
        Client::factory()->create(['referred_by_client_id' => $referrer->id]);

        $result = $referrer->useReferralCredit(50000);

        expect($result)->toBeTrue()
            ->and($referrer->refresh()->referral_credit_used)->toBe('50000.00');
    });

    it('cannot use more referral credit than available', function () {
        $referrer = Client::factory()->create(['referral_credit_used' => 0]);
        // No referrals = 0 credit available

        $result = $referrer->useReferralCredit(50000);

        expect($result)->toBeFalse();
    });

    it('can be soft deleted', function () {
        $client = Client::factory()->create();
        $clientId = $client->id;

        $client->delete();

        expect(Client::find($clientId))->toBeNull()
            ->and(Client::withTrashed()->find($clientId))->not->toBeNull();
    });

    it('can be restored after soft delete', function () {
        $client = Client::factory()->create();
        $clientId = $client->id;

        $client->delete();
        $client->restore();

        expect(Client::find($clientId))->not->toBeNull()
            ->and($client->deleted_at)->toBeNull();
    });

    it('generates initials correctly', function () {
        $client = Client::factory()->create(['name' => 'John Doe']);
        expect($client->initials)->toBe('JD');

        $singleName = Client::factory()->create(['name' => 'Madonna']);
        expect($singleName->initials)->toBe('M');
    });
});
