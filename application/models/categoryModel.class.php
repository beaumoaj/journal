<?php

/*
  Document   : indexModel
  Created on : 12-Sep-2011, 22:34:59
  Author     : David Bennett
 */

class categoryModel extends BaseModel {

    private function truncate($str) {
        return (strlen($str) > 13) ? substr($str, 0, 10) . '...' : $str;
    }


    public function index() {
        $model = new Registry();
        $result = $this->registry->db->select("classifications order by value asc");
        //$result = $this->registry->db->run("select * from classifications order by value asc");
        $model->classifications = $result;
        return $model;
    }

}

?>
