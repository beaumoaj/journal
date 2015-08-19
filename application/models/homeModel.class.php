<?php
/*
    Document   : indexModel
    Created on : 12-Sep-2011, 22:34:59
    Author     : David Bennett
*/

class homeModel extends BaseModel
{
    public function index()
    {
        $model = new Registry();

        $model->title = 'Computer Science @ Aston: Learning Journal';

        $model->sidebarContent = '';

        $model->content = '';
        return $model;
    }
}
?>
