
-- Insertando Categorías
INSERT INTO tab_categorias (id_categoria, nom_categoria) VALUES
(1, 'Hogar y Decoración'),
(2, 'Moda y Accesorios'),
(3, 'Joyería y Bisutería'),
(4, 'Mesa y Cocina'),
(5, 'Juguetería e Infantil'),
(6, 'Instrumentos Musicales'),
(7, 'Papelería y Oficina'),
(8, 'Souvenirs y Regalos'),
(9, 'Arte Religioso'),
(10, 'Mobiliario');

-- Insertando Materias Primas
INSERT INTO tab_materia_prima (id_materia, nom_materia) VALUES
(1, 'Arcilla y Cerámica'),
(2, 'Madera'),
(3, 'Lana y Fibras Textiles'),
(4, 'Caña Flecha'),
(5, 'Iraca'),
(6, 'Cuero y Piel'),
(7, 'Totumo'),
(8, 'Semillas y Frutos Secos'),
(9, 'Vidrio'),
(10, 'Metales Preciosos (Oro, Plata)'),
(11, 'Cobre y Bronce'),
(12, 'Fique'),
(13, 'Guadua y Bambú'),
(14, 'Piedra'),
(15, 'Papel y Cartón');

-- Insertando Oficios
INSERT INTO tab_oficios (id_oficio, nom_oficio) VALUES
(1, 'Tejeduría'),
(2, 'Alfarería y Cerámica'),
(3, 'Talla en Madera'),
(4, 'Joyería'),
(5, 'Marroquinería (Trabajo en Cuero)'),
(6, 'Cestería'),
(7, 'Bordado y Costura'),
(8, 'Ebanistería'),
(9, 'Luthería (Instrumentos)'),
(10, 'Trabajo en Vidrio'),
(11, 'Orfebrería'),
(12, 'Talla en Piedra'),
(13, 'Trabajo en Barniz de Pasto');

-- Insertando Colores
INSERT INTO tab_color (id_color, nom_color) VALUES
('#FFFFFF', 'Blanco'),
('#000000', 'Negro'),
('#FF0000', 'Rojo'),
('#0000FF', 'Azul'),
('#FFFF00', 'Amarillo'),
('#008000', 'Verde'),
('#FFA500', 'Naranja'),
('#800080', 'Morado'),
('#A52A2A', 'Café / Marrón'),
('#F5F5DC', 'Beige / Crema'),
('#808080', 'Gris'),
('#FFD700', 'Dorado'),
('#C0C0C0', 'Plateado'),
('#FFC0CB', 'Rosado'),
('#00FFFF', 'Turquesa / Cian'),
('MULTICOLOR', 'Multicolor');
