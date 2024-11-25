CREATE DATABASE Ecommerce;
USE Ecommerce;

CREATE TABLE Cliente(
    Email VARCHAR(256) NOT NULL PRIMARY KEY,
    Nome_Cliente VARCHAR(20) NOT NULL,
    Sobrenome VARCHAR(40) NOT NULL,
    Senha VARCHAR(32) NOT NULL
);

CREATE TABLE Produtos(
    ISBN13 BIGINT NOT NULL PRIMARY KEY,
    Nome_Produto VARCHAR(50) NOT NULL,
    Preco DOUBLE NOT NULL
);

INSERT INTO Produtos (Nome_Produto, ISBN13, Preco) VALUES
    ('Em busca de nós mesmos', 9788568014455, 29.90),
    ('Os doze trabalhos de Hércules', 9786550472764, 24.90),
    ('A felicidade é inútil', 9786550470227, 24.90),
    ('Epaminondas: O gato explicador', 9786550471408, 34.90),    
    ('Projeto De Vida', 9786550472689, 34.90);
