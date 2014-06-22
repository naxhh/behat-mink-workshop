<?php

namespace Kitty;

class DataSource {
    private $data;

    public function __construct() {
        $this->data = json_decode(file_get_contents(__DIR__ . '/../../var/data/kitties.json'), true);
    }

    public function __destruct() {
        file_put_contents(__DIR__ . '/../../var/data/kitties.json', json_encode($this->data));
    }

    public function get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : array();
    }

    public function set( array $kitty ) {
        $this->data[$kitty['name']] = $kitty;
    }

    public function getList() {
        return $this->data;
    }
}