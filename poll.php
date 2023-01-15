<?php

class Poll{
    public $id;
    public $title;
    public $options;
    public $isMultiple;
    public $start;
    public $end;
    public $answers;
    public $voted;
    

    function __construct($i,$t,$o,$s,$e,$iM) {
        $this->id = $i;
        $this->start = $s;
        $this->end = $e;
        $this->title = $t;
        $this->options = $o;
        $this->isMultiple = $iM;
    }
}