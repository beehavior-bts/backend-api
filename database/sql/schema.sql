-- SELECT 'CREATE DATABASE beehavior ENCODING ''UTF8'''
-- WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'beehavior')\gexec

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE TYPE alert_rule AS ENUM ('HUMIDITY_LESS_THAN', 'HUMIDITY_MORE_THAN', 'TEMPERATURE_LESS_THAN', 'TEMPERATURE_MORE_THAN', 'MASS_LESS_THAN', 'MASS_MORE_THAN');

CREATE TABLE IF NOT EXISTS accounts (
    id          UUID PRIMARY KEY NOT NULL DEFAULT uuid_generate_v4(),
    email       VARCHAR(150) NOT NULL UNIQUE,
    username    VARCHAR(24) NOT NULL,
    password    VARCHAR(100) NOT NULL,
    is_admin    BOOLEAN NOT NULL DEFAULT FALSE,
    phone       VARCHAR(12) DEFAULT NULL, -- format: +33456987423
    updated_on  TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT (NOW() AT TIME ZONE 'utc'),
    created_on  TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT (NOW() AT TIME ZONE 'utc')
);

CREATE TABLE IF NOT EXISTS hives (
    id          INT GENERATED BY DEFAULT AS IDENTITY UNIQUE, -- doit etre changé par le numéro de série ou l'identifiant de l'appareil emetteur
    name        VARCHAR(24) NOT NULL,
    f_owner     UUID NOT NULL,
    CONSTRAINT fk_account
        FOREIGN KEY (f_owner)
            REFERENCES accounts(id)
);

CREATE TABLE IF NOT EXISTS metrics (
    id          INT GENERATED BY DEFAULT AS IDENTITY UNIQUE,
    f_hive      INT NOT NULL,
    humidity    INT NOT NULL,
    temperature INT NOT NULL,
    mass        INT NOT NULL,
    created_on  TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT (NOW() AT TIME ZONE 'utc'),
    CONSTRAINT fk_hive
        FOREIGN KEY (f_hive)
            REFERENCES hives(id)
);

CREATE TABLE IF NOT EXISTS alerts (
    id          INT GENERATED BY DEFAULT AS IDENTITY UNIQUE,
    f_hive      INT NOT NULL,
    rule        alert_rule NOT NULL,
    value       INT NOT NULL,
    CONSTRAINT fk_hive
        FOREIGN KEY (f_hive)
            REFERENCES hives(id)
);

CREATE TABLE IF NOT EXISTS keypairs (
    id          INT GENERATED BY DEFAULT AS IDENTITY UNIQUE,
    type        VARCHAR(50) NOT NULL UNIQUE,
    public_key  BYTEA NOT NULL,
    private_key BYTEA NOT NULL,
    updated_on  TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT (NOW() AT TIME ZONE 'utc'),
    created_on  TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT (NOW() AT TIME ZONE 'utc')
);
