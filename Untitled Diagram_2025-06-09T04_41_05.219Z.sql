CREATE TABLE `музыкант` (
	`id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`id_ансамбля` INTEGER,
	`фио` TEXT,
	`тип` TEXT,
	PRIMARY KEY(`id`)
);


CREATE TABLE `пластинка` (
	`id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`номер` INTEGER,
	`компания` TEXT,
	`адрес опт фирмы` TEXT,
	`опт цены` INTEGER,
	`розн цены` INTEGER,
	`дата выпуска` DATE,
	`продажи за год` INTEGER,
	`продажи за прошлый год` INTEGER,
	`ещё не проданные` INTEGER,
	PRIMARY KEY(`id`)
);


CREATE TABLE `произведение` (
	`id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`id_ансамбля` INTEGER,
	`id_пластинки` INTEGER,
	`название` TEXT,
	`жанр` TEXT,
	PRIMARY KEY(`id`)
);


CREATE TABLE `ансамбль` (
	`id` INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
	`название` TEXT,
	`жанр` TEXT,
	PRIMARY KEY(`id`)
);


ALTER TABLE `произведение`
ADD FOREIGN KEY(`id_ансамбля`) REFERENCES `ансамбль`(`id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `музыкант`
ADD FOREIGN KEY(`id_ансамбля`) REFERENCES `ансамбль`(`id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE `произведение`
ADD FOREIGN KEY(`id_пластинки`) REFERENCES `пластинка`(`id`)
ON UPDATE NO ACTION ON DELETE NO ACTION;