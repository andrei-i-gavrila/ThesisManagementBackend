<?php


use App\Enums\Permissions;
use App\Models\PaperRevision;
use App\Models\User;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('professors', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_PROFESSORS);
});

Broadcast::channel('students', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_STUDENTS);
});

Broadcast::channel('examSessions', function (User $user) {
    return $user->hasPermissionTo(Permissions::MANAGE_SESSIONS);
});

Broadcast::channel('chat.{paperRevision}', function (User $user, PaperRevision $paperRevision) {
    return $user->hasPermissionTo(Permissions::DISCUSS_PAPERS) && ($paperRevision->paper->student_id == $user->id || $user->students()->find($paperRevision->paper->student_id));
});
