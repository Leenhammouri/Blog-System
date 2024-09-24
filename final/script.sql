SET SQL_SAFE_UPDATES = 0;

show databases;
create database blog;
use blog;
show tables;

desc users;

desc posts;

create table users(user_id int auto_increment  primary key , email varchar(50) , fname varchar(50) , lname varchar(50) , password varchar(255) , role enum ('admin' , 'author'));
create table posts(post_id int auto_increment primary key, post_title varchar(255) , post_content text , post_tag varchar(20) , created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP , author_id int references users(user_id));

insert into users(email ,fname , lname , password , role ) values ('admin@admin.com' , 'admin' , 'admin' , 'admin' , 'admin');


select * from users;	
select * from posts;


select count(*) from users;


drop table posts;
drop table users;

SELECT role FROM users WHERE email = 'admin@admin.com';
update users set role = 'admin' where email = 'admin@admin.com';

update users set role = 'admin' where email = 'hammourileen14@gmail.com';
SELECT * FROM users WHERE email = 'admin@admin.com';
commit;


delete from users where email = 'admin@admin.com';

SELECT post_id, post_title, post_content, post_tag, created_at, posts.author_id, CONCAT(fname, ' ', lname) AS author_name 
        FROM posts 
        JOIN users ON posts.author_id = users.user_id 
        ORDER BY created_at DESC