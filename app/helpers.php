<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('getCategoriesName')) {
  function getCategoriesName($note_id)
  {
    $qq = DB::table('note_categories_pivots')
    ->join('note_categories', 'note_categories_pivots.category_id', '=', 'note_categories.id')
    ->where("note_categories_pivots.note_id",$note_id)
    ->orderBy("note_categories.title")
    ->get();
    $result = "";
    foreach ($qq as $row) {
      $result .= "#".$row->title." ";
    }
    return rtrim($result," ");
  }
}
if (!function_exists('getDateForHumans')) {
  function getDateForHumans($date)
  {
    // $myDate = date("m/d/Y",strtotime($date));
    // $result = Carbon::createFromFormat('m/d/Y', $myDate)->diffForHumans();
    if (str_contains($date,":")) {
      $result = date("d.m.Y H:i", strtotime($date));
    }else {
      $result = date("d.m.Y", strtotime($date));
    }
    

    return empty($date) ? "" : $result;
  }
}