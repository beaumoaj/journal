<?php

/*
  Document   : BreadCrumbs
  Created on : 12-Sep-2011, 20:37:59
  Author     : David Bennett
 */

class Breadcrumbs {

    private $crumbs = array();

    public function add($name, $link) {
        $i = count($this->crumbs);
        if ($i > 0) {
            $this->crumbs[$i]->name = $name;
            $this->crumbs[$i]->link = __SITE_DIR . $link;
        }
    }

    public function draw() {
        echo "<ul id='breadcrumbs'>";

        // Print each crumb
        for ($i = 0; $i < count($this->crumbs); $i++) {
            echo "<li> &gt; ";

            // Only make hyperlink if it is not the last
            if ($i < count($this->crumbs) - 1)
                echo "<a href='{$this->crumbs[$i]->link}'>{$this->crumbs[$i]->name}</a>";
            else
                echo $this->crumbs[$i]->name;

            echo "</li>";
        }

        echo "</ul>";
    }

}

?>
