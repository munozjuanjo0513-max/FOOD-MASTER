CREATE DATABASE IF NOT EXISTS foodmaster CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foodmaster;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS restaurantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  tipo VARCHAR(30) NOT NULL,
  imagen VARCHAR(500),
  descripcion VARCHAR(255),
  calificacion DECIMAL(2,1) NOT NULL DEFAULT 0,
  espera_min INT NOT NULL DEFAULT 0,
  ubicacion VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS publicaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  texto TEXT NOT NULL,
  categoria VARCHAR(30) NOT NULL DEFAULT 'general',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS likes (
  usuario_id INT NOT NULL,
  publicacion_id INT NOT NULL,
  PRIMARY KEY (usuario_id, publicacion_id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id) ON DELETE CASCADE
);

-- Datos de ejemplo (los usuarios demo no pueden iniciar sesión)
INSERT INTO restaurantes (nombre,tipo,imagen,descripcion,calificacion,espera_min,ubicacion) VALUES
('Burger Lab','burger','https://images.unsplash.com/photo-1551782450-a2132b4ba21d?q=80&w=800','Hamburguesas artesanales',4.6,20,'C. Principal 45'),
('Sushi Yoko','sushi','https://images.unsplash.com/photo-1546069901-eacef0df6022?q=80&w=800','Sushi fresco y creativo',4.7,10,'Av. Mar 200'),
('La Rolling Pizza','pizza','https://www.laespanolaaceites.com/wp-content/uploads/2019/06/pizza-con-chorizo-jamon-y-queso-1080x671.jpg','Pizzas al horno',4.8,45,'Plaza Central 9');

INSERT INTO usuarios (email,password_hash) VALUES ('ana@demo.local','sin-acceso'),('javi@demo.local','sin-acceso');
INSERT INTO publicaciones (usuario_id,titulo,texto,categoria) VALUES
(1,'Gran Burger Lab','La BBQ es top.','burger'),
(2,'Sushi fresco','Recomendado el roll tempura.','sushi');
