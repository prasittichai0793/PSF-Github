<?php
class class_conn {

    public $db_server = "localhost";
    public $db_username = "root";
    public $db_password = "12345678";
    public $db_database = "db_porsiriyontforklift";

    public function connect() {
        $con = mysqli_connect($this->db_server, $this->db_username, $this->db_password, $this->db_database);

        if ($con) {
            mysqli_set_charset($con, "utf8");
        } else {
            die("การเชื่อมต่อล้มเหลว: " . mysqli_connect_error());
        }

        return $con;
    }
}
?>