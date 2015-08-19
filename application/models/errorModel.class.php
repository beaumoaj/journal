<?php
/*
    Document   : errorModel
    Created on : 14-Sep-2011, 16:05:59
    Author     : David Bennett
*/

class errorModel extends BaseModel
{
    public function error404()
    {
        $model = new Registry();

        $model->title = 'Error 404';

        $model->sidebarContent = '';

        $model->content = '<p>The requested page could not be found.</p>';
        
        return $model;
    }
}
?>
