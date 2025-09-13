<?php

class Mahasiswa {
    public $id;
    public $nama;
    public $address;
}


class MataKuliah {
    public $id;
    public $nama;
}

class Enrollment {
    public $id;
    public $id_mahasiswa;
    public $id_mata_kuliah;
}

?>