<?php


namespace App\Models\contracts;


class Attachment {
    private $name;
    private $data;

    /**
     * Attachment constructor.
     * @param $name
     * @param $data
     */
    public function __construct( $name, $data) {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }



}
