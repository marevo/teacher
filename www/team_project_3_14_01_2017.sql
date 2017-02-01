-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Янв 14 2017 г., 01:16
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `team_project_3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `be_absent`
--

CREATE TABLE IF NOT EXISTS `be_absent` (
  `id_be_absent` int(11) NOT NULL AUTO_INCREMENT,
  `id_time_table` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `date_absent` date NOT NULL,
  PRIMARY KEY (`id_be_absent`),
  UNIQUE KEY `studet_timeTable_date_absent` (`id_student`,`id_time_table`,`date_absent`),
  KEY `be_absent_fk0` (`id_time_table`),
  KEY `be_absent_fk1` (`id_student`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Дамп данных таблицы `be_absent`
--

INSERT INTO `be_absent` (`id_be_absent`, `id_time_table`, `id_student`, `date_absent`) VALUES
(43, 2, 1, '2017-01-13'),
(21, 18, 1, '2017-01-04'),
(51, 20, 1, '2017-01-13'),
(22, 18, 2, '2017-01-04'),
(50, 20, 2, '2017-01-13'),
(32, 1, 3, '2017-01-09'),
(23, 18, 3, '2017-01-04'),
(14, 12, 4, '2016-12-28'),
(24, 18, 4, '2017-01-04'),
(28, 18, 4, '2017-01-06'),
(25, 18, 5, '2017-01-04'),
(27, 18, 5, '2017-01-06'),
(44, 2, 6, '2017-01-13'),
(13, 12, 6, '2016-12-28'),
(26, 18, 6, '2017-01-04');

--
-- Триггеры `be_absent`
--
DROP TRIGGER IF EXISTS `PreventBeAbsentBeforeInsert`;
DELIMITER //
CREATE TRIGGER `PreventBeAbsentBeforeInsert` BEFORE INSERT ON `be_absent`
 FOR EACH ROW BEGIN
    IF EXISTS(SELECT NULL FROM scores as s 
              WHERE s.id_student=NEW.id_student AND s.date_score=NEW.date_absent 
              AND s.id_subject IN(SELECT t.id_subject FROM timetable AS t, be_absent AS b
                                  WHERE t.id_time_table=NEW.id_time_table))
              THEN
                  SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT =
                  'НЕЛЬЗЯ СТАВИТЬ ПРОГУЛ УЧЕНИКУ ЗА ЭТОТ УРОК ЕСЛИ У НЕГО ЕСТЬ ХОТЯБЫ 1 ОЦЕНКА ПО НЕМУ !!!         
ЕСЛИ ОЦЕНКИ ПОСТАВИЛИ ПО ОШИБКЕ ИХ НАДО УДАЛИТЬ, И ТОЛЬКО ТОГДА МОЖНО СТАВИТЬ ПРОПУСК ЗАНЯТИЯ!';
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `id_class` int(11) NOT NULL AUTO_INCREMENT,
  `number_class` varchar(3) NOT NULL,
  `id_boss_teacher` int(11) NOT NULL,
  PRIMARY KEY (`id_class`),
  UNIQUE KEY `number_class` (`number_class`),
  KEY `class_fk0` (`id_boss_teacher`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `class`
--

INSERT INTO `class` (`id_class`, `number_class`, `id_boss_teacher`) VALUES
(2, '1а', 4),
(3, '1б', 5),
(4, '2а', 6),
(5, '2б', 9),
(6, '3а', 8),
(7, '3б', 9);

-- --------------------------------------------------------

--
-- Структура таблицы `number_lesson`
--

CREATE TABLE IF NOT EXISTS `number_lesson` (
  `lesson_num` int(11) NOT NULL AUTO_INCREMENT,
  `time_start_lesson` time NOT NULL,
  `time_end_lesson` time NOT NULL,
  PRIMARY KEY (`lesson_num`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `number_lesson`
--

INSERT INTO `number_lesson` (`lesson_num`, `time_start_lesson`, `time_end_lesson`) VALUES
(1, '08:00:00', '08:45:00'),
(2, '08:55:00', '09:40:00'),
(3, '09:55:00', '10:40:00'),
(4, '10:50:00', '11:35:00'),
(5, '11:45:00', '12:30:00'),
(6, '12:40:00', '13:25:00'),
(7, '13:35:00', '14:20:00'),
(8, '14:40:00', '15:25:00'),
(9, '15:35:00', '16:20:00'),
(10, '16:30:00', '17:15:00'),
(11, '17:25:00', '18:10:00'),
(12, '18:15:00', '18:50:00');

-- --------------------------------------------------------

--
-- Структура таблицы `parents`
--

CREATE TABLE IF NOT EXISTS `parents` (
  `id_parent` int(11) NOT NULL AUTO_INCREMENT,
  `name_parent` varchar(120) NOT NULL,
  `job_place` varchar(150) NOT NULL,
  `phone_parent` varchar(14) NOT NULL,
  `email_parent` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_parent`),
  UNIQUE KEY `id_parent` (`id_parent`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `parents`
--

INSERT INTO `parents` (`id_parent`, `name_parent`, `job_place`, `phone_parent`, `email_parent`, `id_user`) VALUES
(1, 'Семакова Валентина', 'химволокно', '09330000', 'parent', 3),
(2, 'Семаков Семен ', '', '', '', 4),
(3, 'Мороз Павел', '', '', '', 5),
(4, 'Мороз Галина', '', '', '', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `parent_student`
--

CREATE TABLE IF NOT EXISTS `parent_student` (
  `id_parent` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  KEY `parent_student_fk0` (`id_parent`),
  KEY `parent_student_fk1` (`id_student`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `parent_student`
--

INSERT INTO `parent_student` (`id_parent`, `id_student`) VALUES
(1, 1),
(2, 1),
(1, 2),
(3, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(2) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id_role`, `role`) VALUES
(1, '1'),
(2, '2'),
(3, '3');

-- --------------------------------------------------------

--
-- Структура таблицы `scores`
--

CREATE TABLE IF NOT EXISTS `scores` (
  `id_score` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `date_score` date NOT NULL,
  `score` int(11) NOT NULL,
  `score_type` int(11) NOT NULL COMMENT 'тип оценки 1-на уроке,  2-самостоятельная, 3-контрольная',
  `id_teacher` int(11) NOT NULL,
  PRIMARY KEY (`id_score`),
  UNIQUE KEY `stud_subj_scortype_date` (`id_student`,`id_subject`,`score_type`,`date_score`),
  KEY `Scores_id_sub_Sub_id_sub_fk0` (`id_subject`),
  KEY `scores_id_tech_Teach_id_teac_fk1` (`id_teacher`),
  KEY `Score_id_stud_Stud_id_stud` (`id_student`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Дамп данных таблицы `scores`
--

INSERT INTO `scores` (`id_score`, `id_student`, `id_subject`, `date_score`, `score`, `score_type`, `id_teacher`) VALUES
(14, 2, 1, '2016-12-28', 10, 2, 4),
(17, 2, 1, '2016-12-28', 10, 1, 4),
(20, 1, 1, '2016-12-28', 5, 3, 4),
(21, 3, 1, '2016-12-28', 5, 1, 4),
(25, 2, 1, '2017-01-06', 8, 1, 4),
(26, 3, 1, '2017-01-06', 7, 1, 4),
(36, 1, 15, '2017-01-03', 10, 1, 4),
(50, 4, 8, '2017-01-13', 10, 1, 4),
(51, 3, 8, '2017-01-13', 10, 1, 4);

--
-- Триггеры `scores`
--
DROP TRIGGER IF EXISTS `PreventScoreBeforeInsert`;
DELIMITER //
CREATE TRIGGER `PreventScoreBeforeInsert` BEFORE INSERT ON `scores`
 FOR EACH ROW BEGIN
   IF EXISTS (
   SELECT NULL 
   FROM be_absent as b,timetable as t
   WHERE b.date_absent = NEW.date_score AND 
   b.id_student = NEW.id_student AND
   b.id_time_table IN(
   SELECT id_time_table 
   FROM timetable 
   where id_subject = NEW.id_subject
                   ) 
   ) THEN
          SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT=
          'НЕЛЬЗЯ ВЫСТАВИТЬ ОЦЕНКУ, ЕСЛИ УЧЕНИКА НЕ БЫЛО НА УРОКЕ !';
   END IF;
   
   IF NEW.score_type = '3' THEN 
        IF EXISTS(
        SELECT NULL 
        FROM scores 
        WHERE id_student = NEW.id_student AND id_subject = NEW.id_subject AND score_type <> NEW.score_type  AND date_score = NEW.date_score)
        THEN 
            SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT =
            'НЕЛЬЗЯ СТАВИТЬ ОЦЕНКУ ПО КОНТРОЛЬНОЙ, ТАК КАК ЗА ЭТОТ УРОК ЕСТЬ ОЦЕНКА ПО САМОСТОЯТЕЛЬНОЙ ИЛИ ЗА РАБОТУ НА УРОКЕ !';
        END IF; 
     END IF;
     IF NEW.score_type <> '3' 	THEN 
       IF EXISTS(
       SELECT NULL 
       FROM scores 
       WHERE id_student = NEW.id_student AND id_subject = NEW.id_subject AND score_type ='3'  AND date_score = NEW.date_score)
       THEN 
            SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT =
            'НЕЛЬЗЯ СТАВИТЬ ОЦЕНКУ  ЗА РАБОТУ НА УРОКЕ ИЛИ ЗА САМОСТОЯТЕЛЬНУЮ, ТАК КАК УЖЕ ЕСТЬ ОЦЕНКА ПО КОНТРОЛЬНОЙ НА ЭТОМ УРОКЕ!';
        END IF; 
     END IF;
   
   END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id_student` int(11) NOT NULL AUTO_INCREMENT,
  `name_student` varchar(120) NOT NULL,
  `day_birth_student` date NOT NULL,
  `adress_student` varchar(150) NOT NULL,
  `phone_student` varchar(14) NOT NULL,
  `email_student` varchar(30) NOT NULL,
  `id_class` int(11) NOT NULL,
  PRIMARY KEY (`id_student`),
  UNIQUE KEY `id_st_id_class` (`id_student`,`id_class`),
  KEY `students_fk0` (`id_class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id_student`, `name_student`, `day_birth_student`, `adress_student`, `phone_student`, `email_student`, `id_class`) VALUES
(1, 'Семакова Лариса', '2010-01-01', 'Чернигов, Самострова 23, кор 2, кв 3', 'null', 'null', 2),
(2, 'Семаков Максим', '2010-01-01', 'Чернигов, Самострова 23, кор 2, кв 3', 'null', 'null', 2),
(3, 'Вареник Валентин', '2010-01-02', 'Чернигов, Самострова 2, кв 5', 'null', 'null', 2),
(4, 'Ванникова Настя', '2010-02-02', 'Чернигов, Черниговская 3, кв 3', '', '', 2),
(5, 'Карпов Михаил', '2010-02-02', '', '', '', 2),
(6, 'Мороз Даша', '2010-02-02', '', '', '', 2),
(7, 'Алябьев Соловей', '2010-02-02', '', '', '', 3),
(8, 'Аванесян Муравей', '2010-02-02', '', '', '', 3),
(9, 'Курилова Женя', '2010-02-02', '', '', '', 3),
(10, 'Каноненко Вася', '2010-02-02', '', '', '', 3),
(11, 'Перовский Виктор', '2010-02-02', '', '', '', 3),
(12, 'Петровская Василиса', '2010-02-02', '', '', '', 3),
(13, 'Соколов Сергей', '2010-02-02', '', '', '', 4),
(14, 'Серпухов Вася', '2010-02-02', '', '', '', 4),
(15, 'Инночкин Петя', '2010-02-02', '', '', '', 4),
(16, 'Иванов Самуил', '2010-02-02', '', '', '', 4),
(17, 'Кошевой Олег', '2010-02-02', '', '', '', 4),
(18, 'Романов Александр', '2010-02-02', '', '', '', 4),
(19, 'Лежаков Федор', '2010-02-02', '', '', '', 5),
(20, 'Ложкин Виктор', '2010-01-01', '', '', '', 5),
(21, 'Самохина Валентина', '2010-01-01', '', '', '', 5),
(22, 'Северный Игорь', '2010-01-01', '', '', '', 5),
(23, 'Южная Марина', '2010-01-01', '', '', '', 5),
(24, 'Заика Веня', '2010-01-01', '', '', '', 5),
(25, 'Зарубин Костя', '2010-01-01', '', '', '', 6),
(26, 'Загребельный Игорь', '2010-01-01', '', '', '', 6),
(27, 'Кирсанов Илья', '2010-01-01', '', '', '', 6),
(28, 'Коржаков Потап', '2010-01-01', '', '', '', 6),
(29, 'Белый Александр', '2010-01-01', '', '', '', 6),
(30, 'Черный Андрей', '2010-01-01', '', '', '', 6),
(31, 'Венидиктов Веня', '2010-01-01', '', '', '', 7),
(32, 'Палеева Анжела', '2010-01-01', '', '', '', 7),
(33, 'Зарудный Степан', '2010-01-01', '', '', '', 7),
(34, 'Заровная Катерина', '2010-01-01', '', '', '', 7),
(35, 'Маленький Игорь', '2010-01-01', '', '', '', 7),
(36, 'Весельчаков Коля', '2010-01-01', '', '', '', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id_subject` int(11) NOT NULL AUTO_INCREMENT,
  `name_subject` varchar(30) NOT NULL,
  `class_1` int(11) DEFAULT NULL,
  `class_2` int(11) DEFAULT NULL,
  `class_3` int(11) DEFAULT NULL,
  `class_4` int(11) DEFAULT NULL,
  `class_5` int(11) DEFAULT NULL,
  `class_6` int(11) DEFAULT NULL,
  `class_7` int(11) DEFAULT NULL,
  `class_8` int(11) DEFAULT NULL,
  `class_9` int(11) DEFAULT NULL,
  `class_10` int(11) DEFAULT NULL,
  `class_11` int(11) DEFAULT NULL,
  `class_12` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_subject`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `subjects`
--

INSERT INTO `subjects` (`id_subject`, `name_subject`, `class_1`, `class_2`, `class_3`, `class_4`, `class_5`, `class_6`, `class_7`, `class_8`, `class_9`, `class_10`, `class_11`, `class_12`) VALUES
(1, 'математика', 4, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'физкультура', 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'Чтение', 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'Рисование', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'Музыка', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'Английский', 2, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'природоведение', 1, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'Письмо', 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 'хореография', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 'классный час', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 'я в мире', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 'основы здоровья', 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 'труды', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 'родная речь (укр мова)', 0, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 'литатурное чтение', 0, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE IF NOT EXISTS `teachers` (
  `id_teacher` int(11) NOT NULL AUTO_INCREMENT,
  `name_teacher` varchar(120) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_teacher`),
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id_teacher`, `name_teacher`, `id_user`) VALUES
(4, 'Максименко Мария Петровна', 1),
(5, 'Серпухова Ирина Петровна', 7),
(6, 'Воронова Ксения Валерьевна', 6),
(7, 'Киевская Людмила Васильевна', 10),
(8, 'Прокопова Ольга Ивановна', 14),
(9, 'Самохина Василиса Наумовна', 15);

-- --------------------------------------------------------

--
-- Структура таблицы `teachers_subject`
--

CREATE TABLE IF NOT EXISTS `teachers_subject` (
  `id_teacher` int(11) NOT NULL,
  `id_subjects` int(11) NOT NULL,
  KEY `teachers_subject_fk0` (`id_teacher`),
  KEY `teachers_subject_fk1` (`id_subjects`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `timetable`
--

CREATE TABLE IF NOT EXISTS `timetable` (
  `id_time_table` int(11) NOT NULL AUTO_INCREMENT,
  `week_day` int(11) NOT NULL,
  `id_subject` int(11) NOT NULL,
  `lesson_num` int(11) NOT NULL,
  `id_teacher` int(11) DEFAULT NULL,
  `id_class` int(11) NOT NULL,
  PRIMARY KEY (`id_time_table`),
  KEY `timetable_fk0` (`id_subject`),
  KEY `timetable_fk1` (`lesson_num`),
  KEY `timetable_fk2` (`id_teacher`),
  KEY `timetable_fk3` (`id_class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Дамп данных таблицы `timetable`
--

INSERT INTO `timetable` (`id_time_table`, `week_day`, `id_subject`, `lesson_num`, `id_teacher`, `id_class`) VALUES
(1, 1, 1, 1, 4, 2),
(2, 1, 8, 2, 4, 2),
(3, 1, 2, 3, 4, 2),
(4, 1, 3, 4, 4, 2),
(5, 2, 15, 1, 4, 2),
(6, 2, 1, 2, 4, 2),
(7, 2, 8, 3, 4, 2),
(8, 2, 2, 4, 4, 2),
(9, 2, 3, 5, 4, 2),
(10, 3, 8, 1, 4, 2),
(11, 3, 3, 2, 4, 2),
(12, 3, 1, 3, 4, 2),
(13, 3, 4, 4, 4, 2),
(14, 4, 9, 1, 4, 2),
(15, 4, 3, 2, 4, 2),
(16, 4, 7, 3, 4, 2),
(17, 4, 6, 4, 4, 2),
(18, 5, 1, 1, 4, 2),
(19, 5, 6, 2, 4, 2),
(20, 5, 8, 3, 4, 2),
(21, 5, 10, 4, 4, 2),
(22, 1, 8, 1, 5, 3),
(23, 1, 2, 2, 5, 3),
(24, 1, 3, 3, 5, 3),
(25, 1, 1, 4, 5, 3),
(26, 2, 1, 1, 5, 3),
(27, 2, 3, 2, 5, 3),
(28, 2, 6, 3, 5, 3),
(29, 2, 8, 4, 5, 3),
(30, 2, 15, 5, 5, 3),
(31, 3, 3, 1, 5, 3),
(32, 3, 8, 2, 5, 3),
(33, 3, 1, 3, 5, 3),
(34, 3, 7, 4, 5, 3),
(35, 4, 2, 1, 5, 3),
(36, 4, 4, 2, 5, 3),
(37, 4, 6, 3, 5, 3),
(38, 4, 9, 4, 5, 3),
(39, 5, 8, 1, 5, 3),
(40, 5, 3, 2, 5, 3),
(41, 5, 1, 3, 5, 3),
(42, 5, 10, 4, 5, 3),
(43, 1, 9, 1, 6, 4),
(44, 1, 17, 2, 6, 4),
(45, 1, 16, 3, 6, 4),
(46, 1, 1, 4, 6, 4),
(47, 1, 7, 5, 6, 4),
(48, 2, 17, 1, 6, 4),
(49, 2, 16, 2, 6, 4),
(50, 2, 1, 3, 6, 4),
(51, 2, 2, 4, 6, 4),
(52, 2, 6, 5, 6, 4),
(53, 3, 16, 1, 6, 4),
(54, 3, 17, 2, 6, 4),
(55, 3, 1, 3, 6, 4),
(56, 3, 7, 4, 6, 4),
(57, 4, 2, 1, 6, 4),
(58, 4, 15, 2, 6, 4),
(59, 4, 4, 3, 6, 4),
(60, 4, 12, 4, 6, 4),
(61, 4, 6, 5, 6, 4),
(62, 5, 16, 1, 6, 4),
(63, 5, 17, 2, 6, 4),
(64, 5, 1, 3, 6, 4),
(65, 5, 11, 4, 6, 4),
(66, 5, 10, 5, 6, 4),
(67, 1, 16, 1, 7, 5),
(68, 1, 9, 2, 7, 5),
(69, 1, 17, 3, 7, 5),
(70, 1, 7, 4, 7, 5),
(71, 1, 1, 5, 7, 5),
(72, 2, 1, 1, 7, 5),
(73, 2, 17, 2, 7, 5),
(74, 2, 16, 3, 7, 5),
(75, 2, 6, 4, 7, 5),
(76, 2, 2, 5, 7, 5),
(77, 3, 1, 1, 7, 5),
(78, 3, 7, 2, 7, 5),
(79, 3, 16, 3, 7, 5),
(80, 3, 17, 4, 7, 5),
(81, 4, 15, 1, 7, 5),
(82, 4, 2, 2, 7, 5),
(83, 4, 6, 3, 7, 5),
(84, 4, 12, 4, 7, 5),
(85, 4, 12, 5, 7, 5),
(86, 5, 17, 1, 7, 5),
(87, 5, 1, 2, 7, 5),
(88, 5, 11, 3, 7, 5),
(89, 5, 16, 4, 7, 5),
(90, 5, 10, 5, 7, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `id_role` int(11) NOT NULL,
  `date_reg` datetime NOT NULL,
  `date_last_visit` datetime NOT NULL,
  `ip_last_visit` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `INDEX_LOGIN_PASSWORD` (`login`,`password`),
  UNIQUE KEY `login` (`login`),
  KEY `id_role` (`id_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `login`, `password`, `id_role`, `date_reg`, `date_last_visit`, `ip_last_visit`) VALUES
(1, 'lt1', 'pt1', 2, '2016-11-01 00:00:00', '2016-11-01 00:00:00', 'Null'),
(3, 'lp1', 'pp1', 3, '2016-11-01 00:00:00', '2016-11-01 00:00:00', 'Null'),
(4, 'l_parent_2', 'p_parent_2', 3, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(5, 'l_parent_3', 'p_parent_3', 3, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(6, 'l_teacher_2', 'p_teacher_2', 2, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(7, 'l_teacher_3', 'l_teacher_3', 2, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(8, 'l_parent_4', 'p_parent_4', 3, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(9, 'l_parent_5', 'p_parent_5', 3, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(10, 'l_teacher_5', 'p_teacher_5', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(13, 'l_parent_6', 'p_parent_6', 3, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(14, 'l_teacher_6', 'p_teacher_6', 2, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL),
(15, 'l_teacher_7', 'p_teacher_7', 2, '2016-11-01 00:00:00', '2016-11-01 00:00:00', NULL);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `be_absent`
--
ALTER TABLE `be_absent`
  ADD CONSTRAINT `be_absent_ibfk_1` FOREIGN KEY (`id_time_table`) REFERENCES `timetable` (`id_time_table`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_fk0` FOREIGN KEY (`id_boss_teacher`) REFERENCES `teachers` (`id_teacher`);

--
-- Ограничения внешнего ключа таблицы `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_fk0` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `parent_student`
--
ALTER TABLE `parent_student`
  ADD CONSTRAINT `parent_student_fk0` FOREIGN KEY (`id_parent`) REFERENCES `parents` (`id_parent`),
  ADD CONSTRAINT `parent_student_fk1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id_student`);

--
-- Ограничения внешнего ключа таблицы `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id_subject`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`id_teacher`) REFERENCES `teachers` (`id_teacher`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scores_ibfk_3` FOREIGN KEY (`id_student`) REFERENCES `students` (`id_student`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_fk0` FOREIGN KEY (`id_class`) REFERENCES `class` (`id_class`);

--
-- Ограничения внешнего ключа таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_fk0` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `teachers_subject`
--
ALTER TABLE `teachers_subject`
  ADD CONSTRAINT `teachers_subject_fk0` FOREIGN KEY (`id_teacher`) REFERENCES `teachers` (`id_teacher`),
  ADD CONSTRAINT `teachers_subject_fk1` FOREIGN KEY (`id_subjects`) REFERENCES `subjects` (`id_subject`);

--
-- Ограничения внешнего ключа таблицы `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_fk0` FOREIGN KEY (`id_subject`) REFERENCES `subjects` (`id_subject`),
  ADD CONSTRAINT `timetable_fk1` FOREIGN KEY (`lesson_num`) REFERENCES `number_lesson` (`lesson_num`),
  ADD CONSTRAINT `timetable_fk3` FOREIGN KEY (`id_class`) REFERENCES `class` (`id_class`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
