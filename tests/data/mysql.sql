drop table if exists users;

create table users (
    id int auto_increment primary key,
    name varchar(64) not null,
    email varchar(256) not null,
    created datetime not null,
    deleted datetime null,
    notes text null
);

create unique index users_email_uindex on users (email);
create unique index users_name_uindex on users (name);