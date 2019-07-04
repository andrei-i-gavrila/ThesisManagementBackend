<?php


namespace App\Services;


use App\Models\GradingCategory;

class GradeCalculator
{
    public static function calculateAverage($gradingCategories, $grades)
    {
        if (count($gradingCategories) === 0) return 0;

        $total = $gradingCategories->map->points->sum();

        $score = $gradingCategories->map(function (GradingCategory $category) use ($grades) {
            if ($category->subcategories && count($category->subcategories)) {
                return $category->points * self::calculateAverage($category->subcategories, $grades);
            }
            return $category->points * (isset($grades[$category->id]) ? ($grades[$category->id]->value?: 0) : 0);

        })->sum();

        return $score / $total;
    }

}