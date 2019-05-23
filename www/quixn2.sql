use quixn2;
create table categoria(
	id int primary key AUTO_INCREMENT,
	nome varchar(255),
	nome_css varchar(255)
);
create table quiz(
	id int primary key AUTO_INCREMENT,
	idcategoria int,
	titulo varchar(255),
	descricao text
);
create table perguntas(
	id int primary key AUTO_INCREMENT,
	idquiz int,
	enunciado varchar(255)
);
create table respostas(
	id int primary key AUTO_INCREMENT,
	idperguntas int,
	resposta varchar(255),
	certa tinyint(1)
);

insert into categoria (nome, nome_css) values
('História','historia'),
('História','geografia'),
('História','ciencia'),
('História','gerais'),
('História','filmes_series'),
('História','outros')