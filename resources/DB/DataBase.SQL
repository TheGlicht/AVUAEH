-- Tablas para los Alumnos
-- Tabla para registro alumno
CREATE TABLE Alumno(
    id_Alumno INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(50),
    pass VARCHAR(100)
);

-- Tabla para datos alumno
CREATE TABLE DatosA(
    id_DatosA INT AUTO_INCREMENT PRIMARY KEY,
    nombreCompleto VARCHAR(50),
    semestre INT,
    grupo INT,
    id_materias INT,
    rutaPDF VARCHAR(255)
);

-- Tabla los eventos
CREATE TABLE Eventos(
    id_Evento INT AUTO_INCREMENT PRIMARY KEY,
    tituloEvento VARCHAR(50),
    descripcion VARCHAR(255),
    fechaEvento DATE
);

-- Tabla para Vales
CREATE TABLE Vales(
    id_vales INT AUTO_INCREMENT PRIMARY KEY,
    id_materias INT,
    id_
)

-- Tabla para materias estas para materias en general
CREATE TABLE Materias(
    id_materias INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    laboratorio BOOLEAN
);
