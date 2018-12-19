SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `koto`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_tokens`
--

CREATE TABLE `login_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `token` char(64) NOT NULL DEFAULT '',
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` varchar(36) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caption` varchar(200) NOT NULL,
  `title` varchar(100) NOT NULL,
  `privacy` int(1) NOT NULL,
  `deleted` int(1) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `email` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'demo', '$2y$10$ft.WWvSK2m0wVd05uPO8Fu5Z7S0FMjpJgERbz1L.b1/GAF57YLQXy', 'email@domain.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `login_tokens`
--
ALTER TABLE `login_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for table `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD CONSTRAINT `login_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;
