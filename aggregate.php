<?php
class Aggregate {
    public function AggregateIDtoYear($id){
        $year = floor((int)$id / 100);
        return $year;
    }

    public function AggregateIDtoMonth($id){
        $month = (int)$id % 100;
        return $month;
    }

    public function MakeAggregatetable($thisrows,$lastrows,$avgrows) {
            $data = "<table>
                <tr><th></th><th>今月</th><th>前月</th><th>前月比</th><th>平均</th><th>平均比</th></tr>";
        if(empty($lastrows)){
            foreach(array_map(NULL,$thisrows,$lastrows,$avgrows) as [$thisrow,$lastrow,$avgrow]){
                $data .= "<tr><td>{$thisrow[0]}</td><td>{$thisrow[1]}</td><td>-</td><td>-</td><td>" 
                    .round($avgrow[0]) ."</td><td>" 
                    .sprintf("%+d",$thisrow[1]-$avgrow[0]) ."</td></tr>\n";
            }
        } else {
            foreach(array_map(NULL,$thisrows,$lastrows,$avgrows) as [$thisrow,$lastrow,$avgrow]){
                $data .= "<tr><td>{$thisrow[0]}</td><td>{$thisrow[1]}</td><td>{$lastrow[1]}</td><td>"
                    .sprintf("%+d",$thisrow[1]-$lastrow[1]) ."</td><td>" .round($avgrow[0]) ."</td><td>" 
                    .sprintf("%+d",$thisrow[1]-$avgrow[0]) ."</td></tr>\n";
            }
        }
        $data .= "</table>";
        return $data;
    }
}

?>