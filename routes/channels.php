<?php


use App\Enums\Permissions;
use App\Models\User;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('professors', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_PROFESSORS);
});

Broadcast::channel('professors.*', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_PROFESSORS);
});

Broadcast::channel('students', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_STUDENTS);
});

Broadcast::channel('students.*', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_STUDENTS);
});