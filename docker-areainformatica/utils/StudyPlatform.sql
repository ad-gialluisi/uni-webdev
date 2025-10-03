-- Copyright (C) 2015 Antonio Daniele Gialluisi

-- This file is part of "Area informatica"

-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.

-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.

-- You should have received a copy of the GNU General Public License
-- along with this program. If not, see <https://www.gnu.org/licenses/>.





drop database if exists StudyPlatform;
create database if not exists StudyPlatform;




/*
 * Creazione dell'utente
 * NB: Questa soluzione VALE SOLO per MySQL
 */
use mysql;

delimiter //
create procedure userCreate()
begin
    if (select 1 from user where User = 'student') = 1 then
        drop user 'student'@'localhost';
    end if;

    create user 'student'@'localhost' identified by 'studentpassword';
    grant delete, insert, select, update on StudyPlatform . * to 'student'@'localhost';
end; //
delimiter ;
    
call userCreate();
drop procedure userCreate;






use StudyPlatform;

create table if not exists User(
    id int(9) auto_increment,

    type ENUM('user', 'admin') not null default 'user',

    nickname text not null,
    email varchar(255) not null,
    registration_date date not null,
    avatar tinytext not null,
    name tinytext not null,
    surname tinytext not null,
    description tinytext,
    birth_date date not null,
    password tinytext not null,
    
    primary key(id)
);


create table if not exists Article(
    id int(9) auto_increment,
    author int(9),
    type enum('news', 'lesson') not null default 'news',
    publishment_date date not null,

    title text not null,
    content text not null,

    primary key(id),
    foreign key(author) references User(id) on delete no action
);


create table if not exists Comment(
    id int(9) auto_increment,
    article int(9),
    author int(9),
    content text,
    primary key(id),
    foreign key (author) references User(id) on delete no action,
    foreign key (article) references Article(id) on delete cascade
);


create table if not exists Tag(
    id int(9) auto_increment,
    name tinytext not null,
    primary key(id)
);


create table if not exists ArticleTags(
    article int(9),
    tag int(9),
    primary key(article, tag),
    foreign key(article) references Article(id) on delete cascade,
    foreign key(tag) references Tag(id) on delete cascade
);


create table if not exists Subject(
    id int(9) auto_increment,
    name tinytext not null,
    description tinytext not null,
    primary key(id)
);


create table if not exists Lessons(
    subject int(9),
    article int(9),
    nlesson int(9) not null,
    primary key(subject, article),
    unique key(subject, nlesson),
    foreign key(subject) references Subject(id) on delete cascade,
    foreign key(article) references Article(id) on delete cascade
);


create view News as
    select * from Article where type = 'news';

