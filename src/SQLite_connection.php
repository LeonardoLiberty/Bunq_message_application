<?php

class MyDB extends SQLite3 {
    function __construct() {
        $this->open('user_chats_db.db');
    }
}
