<?php


namespace App\Enums;


interface Permissions
{
    public const MANAGE_SESSIONS = "Manage sessions";

    public const MANAGE_PROFESSORS = "Manage professors";

    public const MANAGE_STUDENTS = "Manage students";
    public const MANAGE_KEYWORDS = "Manage keywords";

    public const SEE_STUDENTS = "See students";
    public const SEE_EVALUATORS = "See evaluators";

    public const MANAGE_THESIS_PAPERS = "Manage thesis papers";
    public const DISCUSS_PAPERS = "Discuss papers";

}