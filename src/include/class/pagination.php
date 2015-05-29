<?php
/**
 * Created by Daniel Vidmar.
 * Date: 8/12/14
 * Time: 11:34 PM
 * Version: Beta 1
 * Last Modified: 8/12/14 at 11:34 PM
 * Last Modified by Daniel Vidmar.
 */

class Pagination {

    public $return = "?";
    public $startingValue = 0;
    public $items = 10;
    public $page = 1;
    public $totalPages = 1;
    public $columnString = "*";
    public $table = "";
    public $pageString = "";

    function __construct($t, $c, $p, $i = 10, $r = "?") {
        $this->table = $t;
        $this->columnString = $c;
        $this->page = $p;
        $this->items = $i;
        $this->return = $r;
        $this->prepareValues();
        $this->buildPageString();
    }

    public function paginate() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT ".$this->columnString." FROM `".$this->table."` LIMIT ".$this->startingValue.", ".$this->items);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $out = "";
            $out .= "<tr>";
            $columns = explode(", ", $this->columnString);
            foreach($columns as &$column) {
                $out .= "<td>".$row[$column]."</td>";
            }
            $out .= "</tr>";
            echo $out;
        }
    }

    public function paginateReturn() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT ".$this->columnString." FROM `".$this->table."` LIMIT ".$this->startingValue.", ".$this->items);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function prepareValues() {
        global $pdo;

        $stmt1 = $pdo->prepare("SELECT Count(*) FROM `".$this->table."`");
        $stmt1->execute();
        $count = intval($stmt1->fetchColumn());

        $this->startingValue = (($this->page - 1) * $this->items);
        $this->totalPages = ceil($count / $this->items);
    }

    public function buildPageString() {
        $this->pageString = "";
        $this->pageString .= "<div id='pages'>";
        $this->pageString .= "<strong><a href='".$this->return."pn=1'>First</a></strong>";
        for($i = 1; $i <= $this->totalPages; $i++) {
            $active = ($i == $this->page) ? "class='active'" : "";
            $this->pageString .= "<a ".$active." href='".$this->return."pn=".$i."'>".$i."</a>";
        }
        $this->pageString .= "<strong><a href='".$this->return."pn=".$this->totalPages."'>Last</a></strong>";
        $this->pageString .= "</div>";
    }
}
?>