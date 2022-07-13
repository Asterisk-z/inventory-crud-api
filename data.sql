
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS items (
    id INT(11) NOT NULL AUTO_INCREMENT ,
    name VARCHAR(225) NOT NULL,
    price VARCHAR(225) NOT NULL,
    quantity VARCHAR(225) NOT NULL,
    userId INT(11) NOT NULL,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT ,
    name VARCHAR(225) NOT NULL,
    token VARCHAR(225) NOT NULL,
    role ENUM("admin", "user") DEFAULT "user",
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS cart (
    id INT(11) NOT NULL AUTO_INCREMENT ,
    itemsId VARCHAR(225) NOT NULL, --Array
    userId VARCHAR(225) NOT NULL,
    quantities VARCHAR(225) NOT NULL, -- Array
    prices VARCHAR(225) NOT NULL, -- Array
    status ENUM("pending", "checkout", "canceled") DEFAULT "pending",
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);