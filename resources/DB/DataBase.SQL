-- Tablas generales

-- Tabla para materias estas para materias en general
CREATE TABLE Materias(
    id_materias INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    laboratorio BOOLEAN
);

-- Tabla para programar practicas de laboratorio
CREATE TABLE PracticaLab(
    id_practica INT AUTO_INCREMENT PRIMARY KEY,
    id_materias INT, -- solo la que cuente con laboratorio
    grupo INT,
    id_lab INT, --precargados en el sistema
    fehcaLab DATE,
    horaLab TIME,
    rutaPDF VARCHAR(255)
    id_kit INT, --es el que se necesita para la practica.
)

----------------------------------------------------------

-- Tablas para los Alumnos
-- Tabla para registro alumno
CREATE TABLE Alumno(
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
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
    rutaPDF VARCHAR(255),
    id_alumno INT,
    id_relacion INT
);

-- Tabla los eventos
CREATE TABLE EventosA(
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    tituloEvento VARCHAR(50),
    descripcion VARCHAR(255),
    fechaEvento DATE,
    id_alumno INT
);

-- Tabla para Vales
CREATE TABLE ValesA(
    id_vales INT AUTO_INCREMENT PRIMARY KEY,
    id_materias INT, --Solo si tienen laboratorio
    id_profesor INT, --Mostrar el nombre del profesor en automatico
    diaLab DATE,
    horaLab TIME,
    id_lab INT, -- precargados con un id dentro del sistema (sin tabla)
    id_kit INT, -- de automaticos de acuerdo a la materia,
    id_alumno INT
);

-- Tabla para Contactos
CREATE TABLE ContactosA(
    id_contacto INT AUTO_INCREMENT PRIMARY KEY,
    nombreCompleto VARCHAR (100),
    telefono INT(20),
    email VARCHAR(100),
    id_alumno INT
);

-- Tabla para relacionar alumnos con materias
CREATE TABLE AluMateria(
    id_relacion INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT,
    id_materias INT,
    calf_primer FLOAT,
    calf_second FLOAT,
    calf_ordina FLOAT
)

--------------------------------------------------------
-- Tablas para docentes
CREATE TABLE Docentes(
    id_docente INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(50),
    pass VARCHAR(100)
);

CREATE TABLE DatosD(
    id_DatosD INT AUTO_INCREMENT PRIMARY KEY,
    nombreCompleto VARCHAR(100),
    rutaPDF VARCHAR(255),
    id_materiasAsig INT
)

CREATE TABLE MateriasAsig(
    id_materuasAsig INT AUTO_INCREMENT PRIMARY KEY,
    id_materias INT,
    semestre INT,
    grupo INT
)

CREATE TABLE EventosD( --Tabla aun en veremos
    id_Evento INT AUTO_INCREMENT PRIMARY KEY,
    tituloEvento VARCHAR(50),
    descripcion VARCHAR(255),
    fechaEvento DATE,
    id_docente INT
);

CREATE TABLE EvAlumnoD( --Tabla para eventos en grupos
    id_EventoAD INT AUTO_INCREMENT PRIMARY KEY,
    tituloEvento VARCHAR(50),
    descripcion VARCHAR(255),
    horaEvento TIME,
    id_materia INT, --De acuerdo a las que tenga el progesor
    semestre INT, --De acuerdo a la materia que escoja
    grupo INT, --De acuerdo a la materia que escoja
    fechaEvento DATE,
    id_docente INT,
);

CREATE TABLE 