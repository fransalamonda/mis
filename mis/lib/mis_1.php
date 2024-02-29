<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mis
 * all library about this application
 *
 * @author ilham.dinov
 */
class mis {
    function print_msg($title,$type,$msg){
        if($type == "error"){$type="alert-danger";}
        if($type == "warning"){$type="alert-warning";}
        if($type == "success"){$type="alert-success";}
        $output = "<div class=\"alert ".$type."\" role=\"alert\">"
                . "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>"
                . "<b>".$title."</b>"
                . "<p>".$msg."</p>"
                . "</div>";
        echo $output;
    }
    function special_char($input){
        if(preg_match('/[^a-zA-Z0-9\-\/]/', $input)) {
            return TRUE;
        }
        else { return FALSE;}
    }
    
    /*
     * author ilham.dinov
     * 150119
     * purpose : generate list of excel columns
     */
    function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }
}
