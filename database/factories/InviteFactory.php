<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Tests\DummyInvite;

$factory->define(Invite::class, function (Faker $faker) {
    return [
        'from_name' => $faker->name,
        'from_email' => $faker->email,
        'to_name' => $faker->name,
        'to_email' => $faker->email,
        'payload' => new DummyInvite(),
        'message' => $faker->text,
    ];
});
