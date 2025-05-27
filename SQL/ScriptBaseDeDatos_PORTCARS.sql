-- PORTCARTS DATABASE 
-- Creado Por Blas Villalobos Romero para Proyecto Integrado de Desarrollo de Aplicaciones Multiplataforma

-- TABLA USUARIO
CREATE TABLE IF NOT EXISTS Usuario(
    DNIUsuario VARCHAR(9) PRIMARY KEY,
    username VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(15) NOT NULL,
    nombre VARCHAR(25) NOT NULL,
    apellido1 VARCHAR(25) NOT NULL,
    apellido2 VARCHAR(25) NOT NULL,
    direccion VARCHAR(40) NOT NULL,
    numeroTelefono VARCHAR(9) NOT NULL,
    apikey VARCHAR(15) NOT NULL,
);

-- TABLA ROL
CREATE TABLE IF NOT EXISTS Rol(
    IDRol INT AUTO_INCREMENT PRIMARY KEY,
    nombreRol VARCHAR(50) NOT NULL
);

-- TABLA ROLES_USUARIOS
CREATE TABLE IF NOT EXISTS Roles_Usuarios(
    IDRol INT,
    DNIUsuario VARCHAR(9),
    CONSTRAINT rolesUsuarioPK PRIMARY KEY (IDRol, DNIUsuario),
    CONSTRAINT rolesUsuarioFK1 FOREIGN KEY (IDRol) REFERENCES Rol(IDRol),
    CONSTRAINT rolesUsuarioFK2 FOREIGN KEY (DNIUsuario) REFERENCES Usuario(DNIUsuario)
);

-- TABLA VEHICULO
CREATE TABLE IF NOT EXISTS Vehiculo(
    VIN VARCHAR(17) PRIMARY KEY,
    matricula VARCHAR(7) NOT NULL UNIQUE,
    fechaObtencion DATE NOT NULL,
    DNIUsuario VARCHAR(9),
    nombreModelo VARCHAR(17),
    CONSTRAINT vehiculoFK FOREIGN KEY (DNIUsuario) REFERENCES Usuario(DNIUsuario),
    CONSTRAINT vehiculoFK2 FOREIGN KEY (nombreModelo) REFERENCES Modelo(nombreModelo)
);

-- TABLA MODELO
CREATE TABLE IF NOT EXISTS Modelo (
    nombreModelo VARCHAR(17) PRIMARY KEY,
    nombreMarca VARCHAR(15),
    CONSTRAINT modeloFK FOREIGN KEY (nombreMarca) REFERENCES Marca(nombreMarca)
);

-- TABLA MARCA
CREATE TABLE IF NOT EXISTS Marca (
    nombreMarca VARCHAR(15) PRIMARY KEY,
    duenno VARCHAR(15),
    paisCreacion ENUM('Alemania', 'Japón', 'Estados Unidos', 'Corea del Sur', 'Francia', 'Italia', 'Reino Unido', 'China', 'India', 'México', 'España', 'Brasil'),

);

-- TABLA EXPORTACION
CREATE TABLE IF NOT EXISTS Exportacion (
    IDExportacion INT AUTO_INCREMENT PRIMARY KEY,
    fechaExportacion DATE,
    pais ENUM('Alemania', 'Japón', 'Estados Unidos', 'Corea del Sur', 'Francia', 'Italia', 'Reino Unido', 'China', 'India', 'México', 'España', 'Brasil'),
    estado ENUM('Aprobado','Pendiente','Denegado'),
    DNIUsuario VARCHAR(9),
    VIN VARCHAR(17),
    CONSTRAINT exportacionFK FOREIGN KEY (DNIUsuario) REFERENCES Usuario(DNIUsuario)
);

-- TABLA IMPORTACION
CREATE TABLE IF NOT EXISTS Importacion (
    IDImportacion INT AUTO_INCREMENT PRIMARY KEY,
    fechaImportacion DATE,
    pais ENUM('Alemania', 'Japón', 'Estados Unidos', 'Corea del Sur', 'Francia', 'Italia', 'Reino Unido', 'China', 'India', 'México', 'España', 'Brasil'),
    estado ENUM("Aprobado","Pendiente","Denegado"),
    DNIUsuario VARCHAR(9),
    VIN VARCHAR(17),
    CONSTRAINT importacionFK FOREIGN KEY (DNIUsuario) REFERENCES Usuario(DNIUsuario)
);

-- TABLA TRANSFERENCIA
CREATE TABLE IF NOT EXISTS Transferencia (
    IDTransferencia INT AUTO_INCREMENT PRIMARY KEY,
    fechaTransferencia DATE
);

-- TABLA TRANSFERENCIA_USUARIO
CREATE TABLE IF NOT EXISTS Transferencia_Usuario (
    IDTransferencia INT PRIMARY KEY,
    DNI_Propietario VARCHAR(9),
    DNI_Receptor VARCHAR(9),
    VIN VARCHAR(17),
    CONSTRAINT transferenciaUsuarioFK FOREIGN KEY (IDTransferencia) REFERENCES Transferencia(IDTransferencia),
    CONSTRAINT transferenciaUsuarioFK2 FOREIGN KEY (DNI_Propietario) REFERENCES Usuario(DNIUsuario),
    CONSTRAINT transferenciaUsuarioFK3 FOREIGN KEY (DNI_Receptor) REFERENCES Usuario(DNIUsuario)
);