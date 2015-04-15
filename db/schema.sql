create table if not exists members (
    id                 integer   primary key,
    id_token_encripted text      not null,
    openid_provider    text      not null,
    verified           boolean   default 0,
    admin              boolean   default 0,
    email              text      not null,
    name               text      not null,
    nickname           text,
    avatar             text,
    birthday           timestamp,
    alumni             boolean,
    join_date          timestamp,
    entrance           timestamp,
    graduation         timestamp,
    created_at         timestamp default current_timestamp,
    updated_at         timestamp
);
