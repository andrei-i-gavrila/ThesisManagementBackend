<?php

namespace App\Http\Controllers;

use App\Jobs\RolesAndPermissionsInitializer;
use App\Models\ExamSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ExamSessionController extends Controller
{
    public function index()
    {
        return ExamSession::query()->latest()->get();
    }

    /**
     * @param Request $request
     * @throws ValidationException
     * @throws Exception
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|unique:exam_sessions,id'
        ]);


        ExamSession::create([
            'id' => $request->id
        ]);
    }

    /**
     * @param ExamSession $examSession
     * @throws Exception
     */
    public function delete(ExamSession $examSession)
    {
        $examSession->delete();
    }

}
