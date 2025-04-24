-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.18-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para delasan_talentos
CREATE DATABASE IF NOT EXISTS `delasan_talentos` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `delasan_talentos`;

-- Volcando estructura para tabla delasan_talentos.asignatura
CREATE TABLE IF NOT EXISTS `asignatura` (
  `ASI_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FUN_RUN` varchar(8) NOT NULL,
  `CUR_ID` int(11) NOT NULL,
  `ASI_NOMBRE` varchar(100) NOT NULL,
  `ASI_CICLO_ESCOLAR` int(11) NOT NULL,
  PRIMARY KEY (`ASI_ID`,`FUN_RUN`),
  KEY `FK_CUR_ASI` (`CUR_ID`),
  KEY `FK_FUN_ASI` (`FUN_RUN`),
  CONSTRAINT `FK_CUR_ASI` FOREIGN KEY (`CUR_ID`) REFERENCES `curso` (`CUR_ID`),
  CONSTRAINT `FK_FUN_ASI` FOREIGN KEY (`FUN_RUN`) REFERENCES `funcionario` (`FUN_RUN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.asignatura: ~0 rows (aproximadamente)
DELETE FROM `asignatura`;

-- Volcando estructura para tabla delasan_talentos.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `CAT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CAT_NOMBRE` varchar(50) NOT NULL,
  PRIMARY KEY (`CAT_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.categoria: ~8 rows (aproximadamente)
DELETE FROM `categoria`;
INSERT INTO `categoria` (`CAT_ID`, `CAT_NOMBRE`) VALUES
	(1, '1er Ciclo'),
	(2, '2do Ciclo'),
	(3, '3er Ciclo'),
	(4, 'Administración'),
	(5, 'Convivencia'),
	(6, 'Familia'),
	(7, 'PIE'),
	(8, 'Auxiliares');

-- Volcando estructura para tabla delasan_talentos.curso
CREATE TABLE IF NOT EXISTS `curso` (
  `CUR_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUR_NIVEL` varchar(5) NOT NULL,
  `CUR_LETRA` varchar(1) NOT NULL,
  PRIMARY KEY (`CUR_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.curso: ~0 rows (aproximadamente)
DELETE FROM `curso`;

-- Volcando estructura para tabla delasan_talentos.dimension
CREATE TABLE IF NOT EXISTS `dimension` (
  `DIM_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EJE_ID` int(11) NOT NULL,
  `DIM_TEXTO` text NOT NULL,
  PRIMARY KEY (`DIM_ID`),
  KEY `FK_EJE_DIM` (`EJE_ID`),
  CONSTRAINT `FK_EJE_DIM` FOREIGN KEY (`EJE_ID`) REFERENCES `eje` (`EJE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.dimension: ~87 rows (aproximadamente)
DELETE FROM `dimension`;
INSERT INTO `dimension` (`DIM_ID`, `EJE_ID`, `DIM_TEXTO`) VALUES
	(1, 11, 'Coopera en la organización del tiempo y del espacio educativo.'),
	(2, 11, 'Estimula y favorece los espacios para el desarrollo de la creatividad de los estudiantes.'),
	(3, 11, 'Prepara material didáctico con anticipación para la actividad a realizar con los estudiantes.'),
	(4, 12, 'Motiva constantemente la formación de hábitos de los estudiantes, en todas las asignaturas.'),
	(5, 12, 'Apoya en forma constante a los docentes, en las normas de la sala  y el aprendizaje.'),
	(6, 12, 'Aplica estrategias pertinentes para la resolución de conflictos entre estudiantes.'),
	(7, 12, 'Establece o propicia interacciones afectivas que favorecen al aprendizaje.'),
	(8, 12, 'Mantiene una buena disposición para cooperar con el proceso de aprendizaje con los estudiantes  y el profesor.'),
	(9, 13, 'Apoya en las instrucciones a los estudiantes para la realización de actividades.'),
	(10, 13, 'Se comunica espontáneamente con los estudiantes y  está atenta a sus peticiones y necesidades.'),
	(11, 13, 'Aprovecha las instancias de su quehacer para corregir cordialmente a los estudiantes.'),
	(12, 13, 'Interviene espontáneamente en el refuerzo pedagógico de los niños/as'),
	(13, 14, 'Es consciente de que sus acciones sirven de modelo para los estudiantes.'),
	(14, 14, 'Previene y evita riesgos y accidentes.'),
	(15, 14, 'Se preocupa de la formación valórica y espiritual de los estudiantes, en forma permanente.'),
	(16, 14, 'Aplica conocimientos y técnicas aprendidas en el aula.'),
	(17, 14, 'Aprovecha los recursos existentes conforme a las actividades planteadas.'),
	(18, 14, 'Demuestra iniciativa y /o proactividad en su rol de asistente.'),
	(19, 14, 'Reacciona con seguridad frente a situaciones imprevistas.'),
	(20, 21, 'Presenta el objetivo donde se explicita la habilidad/destreza que orientará el desarrollo de la clase, intencionándolo como meta compartida con los estudiantes.'),
	(21, 21, 'Utiliza estrategias de clima de aula para señalar instrucciones claras y precisas.'),
	(22, 21, 'Explicita un método o estrategia de aprendizaje que potencia el desarrollo de la destreza.'),
	(23, 21, 'Utiliza estrategias de gestión del tiempo como “tiempo visible y consensuado” o “visible y asignado".'),
	(24, 21, 'Se observa la utilización de adecuaciones curriculares para cubrir las necesidades de todos los estudiantes.'),
	(25, 22, 'Aplica una estrategia de clima de aula al inicio de la clase (umbral).'),
	(26, 22, 'Implementa en forma efectiva, al menos dos rutinas o estrategias que potencian la gestión de un buen clima de aprendizaje (Hacer ahora, factor alegría, 100%, otros).'),
	(27, 22, 'Refuerza positivamente a los estudiantes, fortaleciendo su autoestima.'),
	(28, 22, 'Promueve un estilo de trabajo donde se dé la oportunidad de ejercitar virtudes específicas como: responsabilidad, respeto, tolerancia y solidaridad.'),
	(29, 22, 'Establece y mantiene normas consistentes y consensuadas de disciplina en el aula (5S).'),
	(30, 22, 'Procura que el ambiente físico sea seguro, limpio y propicio para el aprendizaje.'),
	(31, 22, 'Promueve altas expectativas, hace sentir a los estudiantes que son capaces.'),
	(32, 22, 'Se dirige a los estudiantes en forma respetuosa.'),
	(33, 23, 'Las estrategias utilizadas potencian el desarrollo de la destreza.'),
	(34, 23, 'Las estrategias utilizadas promueven la comprensión del contenido.'),
	(35, 23, 'Verifica la comprensión de las instrucciones entregadas con estrategias como “sin salida” u otra.'),
	(36, 23, 'Verifica la comprensión de conceptos en los diferentes momentos de la clase.'),
	(37, 23, 'Realiza preguntas claves y/o demostraciones al estudiante promoviendo el logro del objetivo.'),
	(38, 23, 'Responde las preguntas de los estudiantes de manera clara y precisa, con un buen manejo conceptual.'),
	(39, 23, 'Aplica estrategias de evaluación formativa con énfasis en la retroalimentación, congruentes con los objetivos de enseñanza.'),
	(40, 23, 'Monitorea y supervisa permanentemente el trabajo de los estudiantes.'),
	(41, 23, 'Comprueba con los estudiantes si el objetivo de aprendizaje planificado fue logrado. (cierre)'),
	(42, 23, 'Diversifica el trabajo del aula, ofreciendo múltiples estrategias de acceso al aprendizaje.'),
	(43, 24, 'Se observa un trabajo de co-docencia coordinado y colaborativo entre el docente, educador diferencial, asistente o profesional de apoyo.'),
	(44, 24, 'Cumple rigurosamente con el horario de inicio y/o término de clases.'),
	(45, 24, 'Se observa una clase preparada a través de las estrategias y/o materiales utilizados.'),
	(46, 24, 'Se observa un adecuado manejo conceptual disciplinar.'),
	(47, 31, 'Inicia puntualmente la clase'),
	(48, 31, 'Las actividades planificadas se ajustan a los tiempos disponibles.'),
	(49, 31, 'Dispone del material para dar inicio a la actividad.'),
	(50, 31, 'Utiliza los materiales de apoyo adecuados para el logro de los aprendizajes esperados.'),
	(51, 32, 'Motiva constantemente la formación de hábitos de los estudiantes.'),
	(52, 32, 'Utiliza un lenguaje adecuado a la formalidad de la clase.'),
	(53, 32, 'Aplica estrategias pertinentes para la resolución de conflictos entre estudiantes.'),
	(54, 32, 'Establece o propicia interacciones afectivas que favorecen al aprendizaje.'),
	(55, 32, 'Logra mantener el orden y la atención de los estudiantes.'),
	(56, 32, 'Enriquece el material de orientación proporcionado.'),
	(57, 33, 'Activa conocimientos o experiencias previas de los alumnos y los relaciona con los contenidos de la clase.'),
	(58, 33, 'Maneja y desarrolla los contenidos de manera clara, precisa y adecuada al nivel de los estudiantes.'),
	(59, 33, 'Aprovecha las instancias de su quehacer para corregir cordialmente a los estudiantes.'),
	(60, 33, 'Se lleva a cabo un cierre de la actividad que evalúa el logro de los objetivos.'),
	(61, 33, 'Interviene espontáneamente en el refuerzo pedagógico de los niños/as'),
	(62, 34, 'Genera un ambiente de colaboración y crítica constructiva entre los alumnos.'),
	(63, 34, 'Promueve habilidades de pensamiento como; cuestionar, reflexionar y fundamentar sus respuestas'),
	(64, 34, 'Establece un clima de relaciones interpersonales respetuosas y empáticas con sus alumnos.'),
	(65, 34, 'Promueve y permite la participación de todos los alumnos.'),
	(66, 34, 'Utiliza refuerzos positivos, verbales y no verbales para reconocer la participación de los alumnos.'),
	(67, 34, 'Resuelve adecuadamente las situaciones imprevistas, sin tensión y sin perder el control de la clase.'),
	(68, 41, 'Los procedimientos y/o recursos utilizados se adecuan al plan individual de cada estudiante'),
	(69, 41, 'Se observa un trabajo coordinado con el docente de asignatura.'),
	(70, 41, 'Utiliza estrategias de clima de aula para dar instrucciones claras y precisas'),
	(71, 41, 'El docente conoce las necesidades educativas de todos/as los/as estudiantes.'),
	(72, 41, 'La docente se presenta en clases con los recursos y materiales necesarios según lo planificado.'),
	(73, 41, 'Se observa manejo del contenido por parte de la Educadora.'),
	(74, 42, 'Promueve un trabajo colaborativo en el que desarrolló valores de responsabilidad , respeto, tolerancia y solidaridad'),
	(75, 42, 'Refuerza positivamente a los estudiantes, fortaleciendo su autoestima y su nivel de expectativa respecto a los aprendizajes'),
	(76, 42, 'Comparte con los estudiantes la estructura de la clase antes de comenzar las actividades.'),
	(77, 42, 'Distribuye a los estudiantes en grupos, considerando sus habilidades y necesidades, para trabajar el aula inclusiva'),
	(78, 43, 'Considera las características individuales de sus estudiantes para el uso de estrategias de enseñanza.'),
	(79, 43, 'Promueve el desarrollo del pensamiento crítico, creativo y la metacognición.'),
	(80, 43, 'Ajusta las estrategias de apoyos de forma oportuna, en base a las necesidades de los estudiantes.'),
	(81, 43, 'Promueve altas expectativas, participación y colaboración de todos los estudiantes en la actividad.'),
	(82, 43, 'Realiza evaluación formativa para monitorear y potenciar el aprendizaje de todos sus estudiantes.'),
	(83, 44, 'Se observa un trabajo de codocencia coordinado y colaborativo entre el docente y Educadora Diferencial'),
	(84, 44, 'Es puntual al ingresar, permanecer y retirarse del aula'),
	(85, 44, 'Selecciona material concreto y adecuado para la intervención y apoyo de los estudiantes'),
	(86, 44, 'Actúa resguardando el bienestar de todos sus estudiantes.'),
	(87, 44, 'Apoya en actividades con sello institucional, promoviendo con esto los valores en el aula (Oración, valor del mes, respeto).');

-- Volcando estructura para tabla delasan_talentos.eje
CREATE TABLE IF NOT EXISTS `eje` (
  `EJE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIPO_ID` int(11) NOT NULL,
  `EJE_NOMBRE` varchar(100) NOT NULL,
  `EJE_CORTO` varchar(50) NOT NULL,
  `EJE_POND` decimal(10,0) NOT NULL,
  PRIMARY KEY (`EJE_ID`),
  KEY `FK_TIPO_EJE` (`TIPO_ID`),
  CONSTRAINT `FK_TIPO_EJE` FOREIGN KEY (`TIPO_ID`) REFERENCES `tipo_eval` (`TIPO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.eje: ~16 rows (aproximadamente)
DELETE FROM `eje`;
INSERT INTO `eje` (`EJE_ID`, `TIPO_ID`, `EJE_NOMBRE`, `EJE_CORTO`, `EJE_POND`) VALUES
	(11, 1, 'Preparación del proceso de enseñanza y aprendizaje', 'Preparación', 25),
	(12, 1, 'Creación de un ambiente propicio para el aprendizaje', 'Ambiente', 25),
	(13, 1, 'Enseñanza para el aprendizaje de todos/as los/as estudiantes', 'Enseñanza', 25),
	(14, 1, 'Responsabilidades Profesionales', 'Responsabilidad', 25),
	(21, 2, 'Preparación del proceso de enseñanza y aprendizaje', 'Preparación', 25),
	(22, 2, 'Creación de un ambiente propicio para el aprendizaje', 'Ambiente', 25),
	(23, 2, 'Enseñanza para el aprendizaje de todos/as los/as estudiantes', 'Enseñanza', 25),
	(24, 2, 'Responsabilidades Profesionales', 'Responsabilidad', 25),
	(31, 3, 'Preparación del proceso de enseñanza y aprendizaje', 'Preparación', 25),
	(32, 3, 'Creación de un ambiente propicio para el aprendizaje', 'Ambiente', 25),
	(33, 3, 'Enseñanza para el aprendizaje de todos/as los/as estudiantes', 'Enseñanza', 25),
	(34, 3, 'Participación', 'Participación', 25),
	(41, 4, 'Preparación del proceso de enseñanza y aprendizaje', 'Preparación', 25),
	(42, 4, 'Creación de un ambiente propicio para el aprendizaje', 'Ambiente', 25),
	(43, 4, 'Enseñanza para el aprendizaje de todos/as los/as estudiantes', 'Enseñanza', 25),
	(44, 4, 'Responsabilidades Profesionales', 'Responsabilidad', 25);

-- Volcando estructura para tabla delasan_talentos.funcionario
CREATE TABLE IF NOT EXISTS `funcionario` (
  `FUN_RUN` varchar(8) NOT NULL,
  `SUC_ID` int(11) NOT NULL,
  `CAT_ID` int(11) NOT NULL,
  `FUN_PATERNO` varchar(100) NOT NULL,
  `FUN_MATERNO` varchar(100) NOT NULL,
  `FUN_NOMBRES` varchar(100) NOT NULL,
  `FUN_CLAVE` varchar(255) NOT NULL,
  `FUN_CORREO` varchar(100) NOT NULL,
  `FUN_ES_ACTIVO` tinyint(1) NOT NULL,
  `FUN_ES_FUNDACION` tinyint(1) NOT NULL,
  `FUN_ES_DOCENTE` tinyint(1) NOT NULL,
  `FUN_ES_ASISTENTE` tinyint(1) NOT NULL,
  `FUN_ES_JEFATURA` tinyint(1) NOT NULL,
  PRIMARY KEY (`FUN_RUN`),
  KEY `FK_FUN_CAT` (`CAT_ID`),
  CONSTRAINT `FK_FUN_CAT` FOREIGN KEY (`CAT_ID`) REFERENCES `categoria` (`CAT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.funcionario: ~0 rows (aproximadamente)
DELETE FROM `funcionario`;
INSERT INTO `funcionario` (`FUN_RUN`, `SUC_ID`, `CAT_ID`, `FUN_PATERNO`, `FUN_MATERNO`, `FUN_NOMBRES`, `FUN_CLAVE`, `FUN_CORREO`, `FUN_ES_ACTIVO`, `FUN_ES_FUNDACION`, `FUN_ES_DOCENTE`, `FUN_ES_ASISTENTE`, `FUN_ES_JEFATURA`) VALUES
	('10754729', 1, 4, 'Merino', 'Loyola', 'Leonardo Alejandro', '$2y$10$mG3XbwfLnsXTi9dmlFySp.FLyrYI7sKJvn5XVNy6yyHCXZSOa9Q5.', 'lmerino@espiritusanto.cl', 1, 1, 0, 0, 0);

-- Volcando estructura para tabla delasan_talentos.observacion
CREATE TABLE IF NOT EXISTS `observacion` (
  `OBS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MOM_ID` int(11) NOT NULL,
  `FUN_RUN` varchar(8) NOT NULL,
  `OBS_FUN_OBSERVADO` varchar(9) NOT NULL,
  `OBS_FECHA` date NOT NULL,
  `OBS_HORA_INICIO` time DEFAULT NULL,
  `OBS_HORA_FIN` time DEFAULT NULL,
  `OBS_COMENTARIO` text NOT NULL,
  `OBS_ACUERDO` text NOT NULL,
  `OBS_NOTA` decimal(10,0) NOT NULL,
  `OBS_ASISTENCIA` int(11) DEFAULT NULL,
  PRIMARY KEY (`OBS_ID`),
  KEY `FK_FUN_OBS` (`FUN_RUN`),
  CONSTRAINT `FK_FUN_OBS` FOREIGN KEY (`FUN_RUN`) REFERENCES `funcionario` (`FUN_RUN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.observacion: ~0 rows (aproximadamente)
DELETE FROM `observacion`;

-- Volcando estructura para tabla delasan_talentos.observacion_detalle
CREATE TABLE IF NOT EXISTS `observacion_detalle` (
  `ODE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `OBS_ID` int(11) NOT NULL,
  `DIM_ID` int(11) NOT NULL,
  `ODE_VALOR` decimal(8,0) DEFAULT NULL,
  PRIMARY KEY (`ODE_ID`),
  KEY `FK_DIM_OBS_DET` (`DIM_ID`),
  KEY `FK_OBS_DET` (`OBS_ID`),
  CONSTRAINT `FK_DIM_OBS_DET` FOREIGN KEY (`DIM_ID`) REFERENCES `dimension` (`DIM_ID`),
  CONSTRAINT `FK_OBS_DET` FOREIGN KEY (`OBS_ID`) REFERENCES `observacion` (`OBS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.observacion_detalle: ~0 rows (aproximadamente)
DELETE FROM `observacion_detalle`;

-- Volcando estructura para tabla delasan_talentos.sucursal
CREATE TABLE IF NOT EXISTS `sucursal` (
  `suc_id` int(11) NOT NULL,
  `suc_nombre` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.sucursal: ~3 rows (aproximadamente)
DELETE FROM `sucursal`;
INSERT INTO `sucursal` (`suc_id`, `suc_nombre`) VALUES
	(1, 'Talcahuano'),
	(2, 'San Antonio'),
	(3, 'Fundación');

-- Volcando estructura para tabla delasan_talentos.tipo_eval
CREATE TABLE IF NOT EXISTS `tipo_eval` (
  `TIPO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIPO_NOMBRE` varchar(25) NOT NULL,
  PRIMARY KEY (`TIPO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla delasan_talentos.tipo_eval: ~4 rows (aproximadamente)
DELETE FROM `tipo_eval`;
INSERT INTO `tipo_eval` (`TIPO_ID`, `TIPO_NOMBRE`) VALUES
	(1, 'Asistente'),
	(2, 'Docente'),
	(3, 'Orientación'),
	(4, 'PIE');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
