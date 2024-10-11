-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 09 2024 г., 10:17
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Politeh`
--

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `total_students` int NOT NULL,
  `inplan_students` int NOT NULL,
  `present_students` int NOT NULL,
  `report_date` date NOT NULL
) ;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `total_students`, `inplan_students`, `present_students`, `report_date`) VALUES
(7, 'ИСк-114/24', 27, 0, 24, '2024-10-08'),
(8, 'ИС-312/22', 21, 1, 19, '2024-10-08'),
(9, 'ИС-312/22', 21, 1, 19, '2024-10-09'),
(10, 'МЭ-417/21', 20, 20, 30, '2024-10-08');

-- --------------------------------------------------------

--
-- Структура таблицы `honorable_students`
--

CREATE TABLE `honorable_students` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `honorable_students`
--

INSERT INTO `honorable_students` (`id`, `group_id`, `name`, `reason`) VALUES
(13, 7, 'Дубровин Петр Андреевич', 'Больничный'),
(14, 7, 'Лапин Данила Андреевич', 'Больничный'),
(15, 9, 'Матвеюк Федор Иванович', 'Больничный'),
(16, 10, 'фффффффффффффффф ', 'Заявление'),
(17, 10, 'ыфвыфв', 'Военкомат'),
(18, 10, 'выфвфыв', 'Военкомат');

-- --------------------------------------------------------

--
-- Структура таблицы `unexcused_students`
--

CREATE TABLE `unexcused_students` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `unexcused_students`
--

INSERT INTO `unexcused_students` (`id`, `group_id`, `name`, `reason`) VALUES
(7, 7, 'новикова Таисия Александровна', NULL),
(8, 8, 'Иванов Кирилл Дмитриевич', NULL),
(9, 8, 'Лазарев Алик Ильгарович', NULL),
(10, 9, 'Иванов Кирилл Дмитриевич', NULL),
(11, 10, 'фывфыв', NULL),
(12, 10, 'ыфвфыв', NULL),
(13, 10, 'ывфыв', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `honorable_students`
--
ALTER TABLE `honorable_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `unexcused_students`
--
ALTER TABLE `unexcused_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `honorable_students`
--
ALTER TABLE `honorable_students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `unexcused_students`
--
ALTER TABLE `unexcused_students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `honorable_students`
--
ALTER TABLE `honorable_students`
  ADD CONSTRAINT `honorable_students_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `unexcused_students`
--
ALTER TABLE `unexcused_students`
  ADD CONSTRAINT `unexcused_students_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
