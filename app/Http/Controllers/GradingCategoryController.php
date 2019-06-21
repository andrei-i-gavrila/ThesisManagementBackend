<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\GradingCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GradingCategoryController extends Controller
{

    /**
     * @param Request $request
     * @param ExamSession $examSession
     * @throws ValidationException
     * @throws Exception
     */
    public function saveCategory(Request $request, ExamSession $examSession)
    {
        DB::beginTransaction();
        $attributes = $this->validate($request, [
            'id' => 'nullable|exists:grading_categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'points' => 'required|numeric|gt:0',
            'parent_category_id' => 'nullable|exists:grading_categories,id'
        ]);

        $attributes['order'] = (GradingCategory::where([
                    'exam_session_id' => $examSession->id,
                    'parent_category_id' => $request->parent_category_id,
                ])->max('order') ?? 0) + 1;


        $examSession->gradingCategories()->save(new GradingCategory($attributes));
        DB::commit();
    }

    /**
     * @param Request $request
     * @param GradingCategory $gradingCategory
     * @throws ValidationException
     */
    public function updateCategory(Request $request, GradingCategory $gradingCategory)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'points' => 'required|numeric|gt:0',
        ]);

        $gradingCategory->update($attributes);
    }

    public function getCategories(ExamSession $examSession)
    {
        return $examSession->gradingCategories->load('subcategories');
    }


    /**
     * @param GradingCategory $gradingCategory
     * @throws Exception
     */
    public function deleteCategory(GradingCategory $gradingCategory)
    {
        $gradingCategory->delete();
    }

    /**
     * @param GradingCategory $gradingCategory
     * @throws Exception
     */
    public function incrementOrder(GradingCategory $gradingCategory)
    {
        $this->changeOrder($gradingCategory, 1);
    }

    /**
     * @param GradingCategory $gradingCategory
     * @param $delta
     * @throws Exception
     */
    public function changeOrder(GradingCategory $gradingCategory, $delta)
    {
        DB::beginTransaction();
        $next = GradingCategory::where([
            'exam_session_id' => $gradingCategory->exam_session_id,
            'parent_category_id' => $gradingCategory->parent_category_id,
            'order' => $gradingCategory->order + $delta
        ])->first();

        if ($next) {
            $gradingCategory->order = $gradingCategory->order + $delta;
            $next->order = $next->order - $delta;

            $gradingCategory->save();
            $next->save();
        }
        DB::commit();
    }

    /**
     * @param GradingCategory $gradingCategory
     * @throws Exception
     */
    public function decrementOrder(GradingCategory $gradingCategory)
    {
        $this->changeOrder($gradingCategory, -1);
    }


}
