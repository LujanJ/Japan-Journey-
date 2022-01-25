DROP TABLE IF EXISTS JJ_images;

CREATE TABLE IF NOT EXISTS JJ_images (
  image_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  filename varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  caption varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  price decimal(6,2) NOT NULL,
  details text,
  PRIMARY KEY (image_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT INTO JJ_images (image_id, filename, caption, price, details) VALUES
(1, 'basin.jpg', 'Water basin at Ryoanji temple, Kyoto', 99.00, 'This is a lovely 16 x 20 inch silkscreen print.'),
(2, 'fountains.jpg', 'Fountains in central Tokyo', 199.00, 'This beautiful image on canvas print comes as three separate 12 x 36 inch panels.'),
(3, 'kinkakuji.jpg', 'The Golden Pavilion in Kyoto', 59.00, 'This image comes as a 20 x 24 inch oil painting. '),
(4, 'maiko.jpg', 'Maiko - trainee geishas in Kyoto', 19.00, 'This is a 5 x 7 inch, glossy photo.'),
(5, 'maiko_phone.jpg', 'Every maiko should have one - a mobile, of course', 19.00, 'This is a 5 x 7 inch photo with a matte finish.'),
(6, 'menu.jpg', 'Menu outside restaurant in Pontocho, Kyoto', 55.00, 'This is an image on canvas 16 x 20 inch print.'),
(7, 'monk.jpg', 'Monk begging for alms in Kyoto', 25.00, 'This is an 8 x 10 inch glossy photo.'),
(8, 'ryoanji.jpg', 'Autumn leaves at Ryoanji temple, Kyoto', 75.0, 'This image comes as a 14 x 17 inch watercolor painting.');


