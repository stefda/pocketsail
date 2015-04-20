DROP TABLE IF EXISTS poi;

CREATE TABLE IF NOT EXISTS poi (
    id SERIAL primary key,
    url varchar(255) NOT NULL,
    nearId integer DEFAULT NULL,
    countryId integer DEFAULT NULL,
    userId integer NOT NULL,
    name varchar(255) NOT NULL,
    label varchar(255) NOT NULL,
    cat varchar(20) NOT NULL,
    sub varchar(20) NOT NULL,
    --lat float NOT NULL,
    --lng float NOT NULL,
    latlng point NOT NULL,
    border polygon DEFAULT NULL,
    attrs text NOT NULL,
    rank float NOT NULL DEFAULT 1,
    timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX ON poi (cat, sub);
--CREATE INDEX ON poi (lat, lng);
CREATE INDEX point_idx ON poi USING gin(latlng);
CREATE INDEX ON poi (url);