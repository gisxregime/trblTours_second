<?php

use App\Services\GmailMailService;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function (): void {
    $otpMailer = Mockery::mock(GmailMailService::class);
    $otpMailer->shouldReceive('sendOTP')->andReturn(true);

    app()->instance(GmailMailService::class, $otpMailer);
});

it('renders the email first signup step from the register entry point', function () {
    get('/register')
        ->assertSuccessful()
        ->assertSee('Step 1 of 4: Enter your email.');
});

it('stores a signup draft and sends otp from the register entry point', function () {
    $response = post('/register', [
        'email' => 'test@example.com',
    ]);

    $draft = DB::table('signup_drafts')->where('email', 'test@example.com')->first();

    expect($draft)->not->toBeNull();
    expect($draft->otp_code)->not->toBeNull();
    expect($draft->otp_expires_at)->not->toBeNull();

    $response->assertRedirect(route('signup.otp', ['token' => $draft->token], absolute: false));
});
