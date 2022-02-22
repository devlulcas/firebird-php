<?php

namespace FirebdPHP\Firebird;

use PDO;

Class Views extends FirebirdPHP {




    public function __construct()
    {
        parent::__construct();
    }

     /**
     * Show all Views in the Database
     * 
     */
    public function getViewsInDatabase(): ?string
    {
        $query = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE NOT RDB$VIEW_BLR IS NULL';
        $result = $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : "";
    }


}