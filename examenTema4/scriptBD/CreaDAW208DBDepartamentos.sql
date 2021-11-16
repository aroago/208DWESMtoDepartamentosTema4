/* Crear base de datos DAW208DBDepartamentos*/

CREATE DATABASE IF NOT EXISTS DB208DWESProyectoTema4;

/* Usar base de datos DAW208DBDepartamentos*/

USE DB208DWESProyectoTema4;

/* Crear tabla Departamento con los campos (PK)CodDepartamento (3 letras mayusculas), DescDepartamento (max. 255 caracteres),FechaBaja, VolumenNegocio (float-€)*/

    CREATE TABLE Departamento (
            CodDepartamento VARCHAR(3) PRIMARY KEY,
            DescDepartamento VARCHAR(255) NOT NULL,
            FechaBaja DATE NULL,
            VolumenNegocio FLOAT DEFAULT NULL
    ) ENGINE=INNODB;
/* Crear el usuario usuarioDAW208DBDepartamentos / paso*/

CREATE USER 'User208DWESProyectoTema4'@'%' identified BY 'P@ssw0rd';

/* Dar permisos al usuario usuarioDAW208DBDepartamentos*/

GRANT ALL PRIVILEGES ON DB208DWESProyectoTema4.* TO 'User208DWESProyectoTema4'@'%' WITH GRANT OPTION;
