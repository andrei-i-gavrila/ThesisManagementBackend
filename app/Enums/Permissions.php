<?php


namespace App\Enums;


interface Permissions
{
    const MANAGE_SESSIONS = "Manage sessions";
    const MANAGE_PROFESSORS = "Manage professors";

    const MANAGE_STUDENTS = "Manage students";
    const MANAGE_KEYWORDS = "Manage keywords";

    const SEE_LIST_OF_PAPERS = "See papers";
    const SEE_EVALUATORS = "See evaluators";

    const MANAGE_THESIS_PAPERS = "Manage thesis papers";
    const DISCUSS_PAPERS = "Discuss papers";
    const SEE_COMMITTEES = "See committees";

    const MANAGE_GRADING_SCHEMES = "Manage grading schemes";

    const GRADE = "Grade";

}