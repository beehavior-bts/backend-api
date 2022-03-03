CREATE TABLE IF NOT EXISTS prc2022.accounts (
    id          INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    username    VARCHAR(24) NOT NULL,
    password    VARCHAR(100) NOT NULL,
    is_admin    BOOLEAN NOT NULL DEFAULT FALSE,
    phone       VARCHAR(12) DEFAULT NULL,
    updated_on  TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_on  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB CHARSET=UTF8 COLLATE utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS prc2022.hives (
    id          INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    uid         VARCHAR(16) UNIQUE NOT NULL,
    name        VARCHAR(24) NOT NULL,
    f_owner     INT NOT NULL,
    FOREIGN KEY (id) 
        REFERENCES accounts(id)
) ENGINE=INNODB CHARSET=UTF8 COLLATE utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS prc2022.metrics (
    id          INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    f_hive      INT NOT NULL,
    humidity    INT NOT NULL, -- max: 100, min: 0, unity: %
    temperature FLOAT NOT NULL, -- unity: °C
    mass        FLOAT NOT NULL, -- unity: Kg
    created_on  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (f_hive) 
        REFERENCES hives(id)
) ENGINE=INNODB CHARSET=UTF8 COLLATE utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS prc2022.alerts (
    id          INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    f_hive      INT NOT NULL,
    rule        ENUM('HUMIDITY_LESS_THAN', 'HUMIDITY_MORE_THAN', 'TEMPERATURE_LESS_THAN', 'TEMPERATURE_MORE_THAN', 'MASS_LESS_THAN', 'MASS_MORE_THAN') NOT NULL,
    value       FLOAT NOT NULL,
    last_notify TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (f_hive)
        REFERENCES hives(id)
) ENGINE=INNODB CHARSET=UTF8 COLLATE utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS prc2022.keypairs (
    id          INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    type        VARCHAR(50) NOT NULL UNIQUE,
    public_key  BLOB NOT NULL,
    private_key BLOB NOT NULL,
    created_on  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB CHARSET=UTF8 COLLATE utf8_unicode_ci;