<?php

namespace Unicorn\Db;

use PDO;
use Unicorn\Container\ConfigFile;

class DefaultPdoDatabaseConfig {
    public function __construct(
        #[ConfigFile('database.yaml')] private PdoConfig $config
    ) {
    }

    public function database(): SqlDatabase {
        return new SqlDatabase(new PDO(
            $this->config->url,
            $this->config->username,
            $this->config->password
        ));
    }
}