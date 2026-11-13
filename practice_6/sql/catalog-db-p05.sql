-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 11 2026 г., 13:04
-- Версия сервера: 8.2.0
-- Версия PHP: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `practice04_catalog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` tinyint UNSIGNED NOT NULL,
  `price_minor` int UNSIGNED NOT NULL,
  `owner_id` int UNSIGNED NOT NULL DEFAULT '1'
) ;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id`, `title`, `category`, `quantity`, `price_minor`, `owner_id`) VALUES
(1, 'USB-кабель', 'device', 2, 12500, 1),
(2, 'Чехол', 'accessory', 1, 7500, 2),
(3, 'Адаптер', 'device', 0, 5000, 1),
(4, 'sdfsdfdslk', 'device', 31, 491031, 1),
(5, 'Тетрадь \"Мой \'план\'\"', 'device', 99, 9999, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
