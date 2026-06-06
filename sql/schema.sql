CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    cedula VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    tipo_usuario ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente',
    UNIQUE KEY ux_users_cedula (cedula)
);

CREATE TABLE flights (
    flight_code VARCHAR(20) PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    ciudad_origen VARCHAR(50) NOT NULL,
    ciudad_destino VARCHAR(50) NOT NULL,
    fecha_vuelo DATE NOT NULL
);

-- Inserte el primer administrador manualmente con un password hasheado.
-- Por ejemplo, use un script PHP separado para generar password_hash('admin123', PASSWORD_DEFAULT).
-- INSERT INTO users (username, password, cedula, nombre, apellido, telefono, tipo_usuario)
-- VALUES ('admin', '<hash>', '0000000000', 'Admin', 'Sistema', '0000000000', 'admin');

