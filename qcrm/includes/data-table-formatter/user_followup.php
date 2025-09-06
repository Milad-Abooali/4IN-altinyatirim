<?php

// Action
function c_3 ($data, $row, $col) {
    return '<button class="btn btn-outline-primary btn-sm mr-2 edit-followup" data-user="'.$data.'">Edit</button><button class="btn btn-danger btn-sm del-followup" data-user="'.$data.'">Remove</button>';
}