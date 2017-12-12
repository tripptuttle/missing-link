<?php
/**
 * Created by PhpStorm.
 * User: ttuttle
 * Date: 12/12/17
 * Time: 12:17 AM
 */


class util
{
    public static $instructors, $courses, $terms;

    public function __construct()
    {
                $upOne = realpath(__DIR__ . '/..');
        self::$instructors = json_decode(file_get_contents($upOne . '/instructors.json'), true);
        self::$courses = json_decode(file_get_contents($upOne . '/courses.json'), true);
        self::$terms = json_decode(file_get_contents($upOne . '/terms.json'), true);

    }

    public function latest($data)
    {
        switch ($type) {
            case "instructors":
                $fname = 'instructors/instructors.json';
                break;
            case "courses":
                $fname = 'courses/courses.json';
                break;
            case "terms":
                $fname = 'terms/terms.json';
                break;
            default:
                return false;
                break;
        }
        $data = json_decode(file_get_contents($fname));
        return $data;
    }
}