<?php


use App\Enums\Permissions;
use App\Models\PaperRevision;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('grades.*', function(User $user) {
    return $user->hasPermissionTo(Permissions::GRADE);
});

Broadcast::channel('chat.{paperRevision}', function (User $user, PaperRevision $paperRevision) {
    return $user->hasPermissionTo(Permissions::DISCUSS_PAPERS) && ($paperRevision->paper->student_id == $user->id || $user->students()->find($paperRevision->paper->student_id));
});
