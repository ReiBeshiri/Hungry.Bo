-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 10.0.3              
-- * Generator date: Aug 17 2017              
-- * Generation date: Sun Dec 23 18:21:22 2018 
-- * LUN file: C:\Users\mattia.vincenzi2\Desktop\HUNGRY.bo.lun 
-- * Schema: LOGICA W TYPE/1 
-- ********************************************* 


-- Database Section
-- ________________ 

create database LOGICA W TYPE;
use LOGICA W TYPE;


-- Tables Section
-- _____________ 

create table Carrello (
     ID int not null,
     Totale int not null,
     constraint IDCarrello primary key (ID));

create table Cliente (
     Username varchar(30) not null,
     IDCarrello int not null,
     Password char(128) not null,
     Email varchar(50) not null,
     Salt char(128) not null,
     constraint IDCliente primary key (Username),
     constraint FKappartienea_ID unique (IDCarrello));

create table Fornitore (
     Username varchar(30) not null,
     Password char(128) not null,
     Salt char(128) not null,
     Email varchar(50) not null,
     Icona varchar(1),
     Immagine varchar(1),
     TempoArrivoCampus int not null,
     NomeLocale varchar(25) not null,
     Indirizzo varchar(25) not null,
     OraApertura date,
     OraChiusura date,
     TipoLocale varchar(25) not null,
     constraint IDCliente primary key (Username));

create table Luogo (
     Nome varchar(25) not null,
     constraint IDLuogo primary key (Nome));

create table Notifica (
     ID int not null,
     Descrizione varchar(100),
     Letta char not null,
     Destinatario varchar(25) not null,
     Mittente varchar(25) not null,
     IDOrdine int not null,
     constraint IDNotifica primary key (ID));

create table Ordine (
     ID int not null,
     Stato varchar(50) not null,
     UsernameCliente varchar(30) not null,
     LuogoConsegna varchar(25) not null,
     UsernameFornitore varchar(30) not null,
     constraint IDOrdine primary key (ID));

create table Prodotto (
     ID int not null,
     Nome varchar(25) not null,
     Prezzo int not null,
     TempoPreparazione int not null,
     Ingredienti varchar(100) not null,
     TipoProdotto varchar(25) not null,
     UsernameFornitore varchar(30) not null,
     constraint IDProdotto primary key (ID));

create table ProdottoInCarrello (
     UsernameCliente varchar(30) not null,
     IDProdotto int not null,
     ID int not null,
     qntà int not null,
     Descrizione varchar(100),
     IDCarrello int not null,
     constraint IDProdottoInCarrello primary key (IDProdotto, UsernameCliente, ID));

create table ProdottoInOrdine (
     IDProdotto int not null,
     IDOrdine int not null,
     ID int not null,
     qntà int not null,
     Descrizione varchar(100),
     constraint IDProdottoInOrdine primary key (IDOrdine, IDProdotto, ID));

create table Recensione (
     UsernameFornitore varchar(30) not null,
     ID int not null,
     Descrizione varchar(300),
     Voto int not null,
     UsernameCliente varchar(30) not null,
     constraint IDRecensione primary key (UsernameFornitore, ID));

create table TipologiaLocale (
     Nome varchar(25) not null,
     constraint IDTipologiaLocale primary key (Nome));

create table TipologiaProdotto (
     Nome varchar(25) not null,
     constraint IDTipologiaProdotto primary key (Nome));

ALTER TABLE Carrello
MODIFY ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE Prodotto
MODIFY ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE Ordine
MODIFY ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE Notifica
MODIFY ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE Recensione
MODIFY ID int(11) NOT NULL AUTO_INCREMENT;

-- Constraints Section
-- ___________________ 

alter table Cliente add constraint FKappartienea _FK
     foreign key (IDCarrello)
     references Carrello (ID);

alter table Fornitore add constraint FKappartiene
     foreign key (TipoLocale)
     references TipologiaLocale (Nome);

alter table Notifica add constraint FKha
     foreign key (IDOrdine)
     references Ordine (ID);

alter table Ordine add constraint FKeffettua
     foreign key (UsernameCliente)
     references Cliente (Username);

alter table Ordine add constraint FKconsegnatoin
     foreign key (LuogoConsegna)
     references Luogo (Nome);

alter table Ordine add constraint FKserve
     foreign key (UsernameFornitore)
     references Fornitore (Username);

alter table Prodotto add constraint FKè
     foreign key (TipoProdotto)
     references TipologiaProdotto (Nome);

alter table Prodotto add constraint FKvende
     foreign key (UsernameFornitore)
     references Fornitore (Username);

alter table ProdottoInCarrello add constraint FKcontenuto
     foreign key (IDProdotto)
     references Prodotto (ID);

alter table ProdottoInCarrello add constraint FKcompra
     foreign key (UsernameCliente)
     references Cliente (Username);

alter table ProdottoInCarrello add constraint FKcomprende
     foreign key (IDCarrello)
     references Carrello (ID);

alter table ProdottoInOrdine add constraint FKappartenenza
     foreign key (IDOrdine)
     references Ordine (ID);

alter table ProdottoInOrdine add constraint FKrelativoa
     foreign key (IDProdotto)
     references Prodotto (ID);

alter table Recensione add constraint FKriceve
     foreign key (UsernameFornitore)
     references Fornitore (Username);

alter table Recensione add constraint FKscrive
     foreign key (UsernameCliente)
     references Cliente (Username);

-- Index Section
-- _____________ 

