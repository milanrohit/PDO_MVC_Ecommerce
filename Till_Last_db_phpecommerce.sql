-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 11:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpecommerce`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `adminmaster_view` ()   BEGIN
    DECLARE view_count INT;

    -- Check if the view exists
    SELECT COUNT(*)
    INTO view_count
    FROM information_schema.views
    WHERE table_schema = 'phpecommerce'
    AND table_name = 'adminmaster';

    -- Create the view if it does not exist
    IF view_count = 0 THEN
        CREATE VIEW adminmaster_view AS
        SELECT *
        FROM adminmaster;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_admin_view` ()   BEGIN
    DECLARE view_count INT;

    -- Check if the view exists
    SELECT COUNT(*)
    INTO view_count
    FROM information_schema.views
    WHERE table_schema = 'phpecommerce'
    AND table_name = 'adminmaster';

    -- Create the view if it does not exist
    IF view_count = 0 THEN
        CREATE VIEW adminmaster_view AS
        SELECT *
        FROM adminmaster;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ManageProduct` (IN `p_Product_Id` INT, IN `p_Product_CategorieId` INT, IN `p_Product_Name` VARCHAR(255), IN `p_Product_Mrp` DECIMAL(10,2), IN `p_Product_SellPrice` DECIMAL(10,2), IN `p_Product_Qty` INT, IN `p_Product_ShortDesc` TEXT, IN `p_Product_LongDesc` TEXT, IN `p_Product_MetaTitle` VARCHAR(255), IN `p_Product_MetaDesc` TEXT, IN `p_Product_Status` ENUM('Y','N','D'), OUT `p_Message` VARCHAR(255))   BEGIN
    DECLARE
        v_ProductCount INT ;
        -- Check for duplicate product name
    SELECT
        COUNT(*)
    INTO v_ProductCount
FROM
    ProductMaster
WHERE
    Product_Name = p_Product_Name AND Product_Id != p_Product_Id ; IF v_ProductCount > 0 THEN
SET
    p_Message = 'Duplicate product name found.' ; ELSE IF p_Product_Id IS NULL THEN
    -- Insert new product
INSERT INTO ProductMaster(
    Product_CategorieId,
    Product_Name,
    Product_Mrp,
    Product_SellPrice,
    Product_Qty,
    Product_ShortDesc,
    Product_LongDesc,
    Product_MetaTitle,
    Product_MetaDesc,
    Product_Status
)
VALUES(
    p_Product_CategorieId,
    p_Product_Name,
    p_Product_Mrp,
    p_Product_SellPrice,
    p_Product_Qty,
    p_Product_ShortDesc,
    p_Product_LongDesc,
    p_Product_MetaTitle,
    p_Product_MetaDesc,
    p_Product_Status
) ;
SET
    p_Message = 'Product inserted successfully.' ; ELSE
    -- Update existing product
UPDATE
    ProductMaster
SET
    Product_CategorieId = p_Product_CategorieId,
    Product_Name = p_Product_Name,
    Product_Mrp = p_Product_Mrp,
    Product_SellPrice = p_Product_SellPrice,
    Product_Qty = p_Product_Qty,
    Product_ShortDesc = p_Product_ShortDesc,
    Product_LongDesc = p_Product_LongDesc,
    Product_MetaTitle = p_Product_MetaTitle,
    Product_MetaDesc = p_Product_MetaDesc,
    Product_Status = p_Product_Status
WHERE
    Product_Id = p_Product_Id ;
SET
    p_Message = 'Product updated successfully.' ;
END IF ;
END IF ;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `adminmaster`
--

CREATE TABLE `adminmaster` (
  `Admin_Id` int(11) NOT NULL,
  `Admin_Username` varchar(50) NOT NULL,
  `Admin_Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminmaster`
--

INSERT INTO `adminmaster` (`Admin_Id`, `Admin_Username`, `Admin_Password`) VALUES
(1, 'milan', 'milan'),
(2, 'pinal', 'pinal');

-- --------------------------------------------------------

--
-- Table structure for table `categoriesmaster`
--

CREATE TABLE `categoriesmaster` (
  `Categories_Id` int(50) NOT NULL,
  `Categories_Name` varchar(50) NOT NULL,
  `Categories_Status` enum('A','N','D') DEFAULT 'N' COMMENT 'A = ''Active'' ,N = ''Inactive'' ,D = ''Deleted''\r\n',
  `Categories_Add_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Categories_Modify_Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categoriesmaster`
--

INSERT INTO `categoriesmaster` (`Categories_Id`, `Categories_Name`, `Categories_Status`, `Categories_Add_Date`, `Categories_Modify_Date`) VALUES
(1, 'test1', 'A', '2025-02-03 10:02:39', '2025-02-03 10:02:39'),
(2, 'test2', 'N', '2025-02-03 10:02:46', '2025-02-03 10:02:46'),
(3, 'test3', 'A', '2025-02-03 10:03:03', '2025-02-03 10:03:03'),
(4, 'test4', 'N', '2025-02-03 10:03:07', '2025-02-03 10:03:07'),
(5, 'test5', 'A', '2025-02-03 10:03:11', '2025-02-03 10:03:11'),
(6, 'test6', 'A', '2025-02-03 10:03:15', '2025-02-03 10:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `contactus_id` int(4) NOT NULL,
  `contactus_name` varchar(150) NOT NULL,
  `contactus_email` varchar(100) NOT NULL,
  `contactus_mobile` int(11) NOT NULL,
  `contactus_status` enum('A','N','D') NOT NULL DEFAULT 'N',
  `contactus_comment` varchar(250) NOT NULL,
  `contactus_add_datetime` datetime NOT NULL,
  `contactus_update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`contactus_id`, `contactus_name`, `contactus_email`, `contactus_mobile`, `contactus_status`, `contactus_comment`, `contactus_add_datetime`, `contactus_update_datetime`) VALUES
(1, 'milan', 'rohit.milan3@gmail.com', 141810925, 'N', 'this is contactus comment section', '2025-02-03 15:03:19', '2025-02-03 15:03:19'),
(2, 'mayur', 'rohit.mayur@gmail.com', 638200987, 'A', 'this is contactus comment section', '2025-02-03 15:03:19', '2025-02-03 15:03:19');

-- --------------------------------------------------------

--
-- Table structure for table `productmaster`
--

CREATE TABLE `productmaster` (
  `Product_Id` int(5) NOT NULL COMMENT 'Product_Id is productmaster primary key',
  `Product_CategorieId` int(5) NOT NULL COMMENT 'This Product_CategorieId is based on Categories_Id From CategorieMaster',
  `Product_Name` varchar(50) NOT NULL,
  `Product_Mrp` int(5) NOT NULL,
  `Product_SellPrice` int(5) NOT NULL,
  `Product_Qty` int(5) NOT NULL,
  `Product_Img` varchar(255) DEFAULT NULL,
  `Product_ShortDesc` varchar(250) NOT NULL,
  `Product_LongDesc` varchar(500) NOT NULL,
  `Product_MetaTitle` varchar(250) NOT NULL,
  `Product_MetaDesc` varchar(500) NOT NULL,
  `Product_Status` enum('A','N','D') DEFAULT 'N' COMMENT 'A = ''Active'' ,N = ''Inactive'' ,D = ''Deleted''',
  `Product_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productmaster`
--

INSERT INTO `productmaster` (`Product_Id`, `Product_CategorieId`, `Product_Name`, `Product_Mrp`, `Product_SellPrice`, `Product_Qty`, `Product_Img`, `Product_ShortDesc`, `Product_LongDesc`, `Product_MetaTitle`, `Product_MetaDesc`, `Product_Status`, `Product_datetime`) VALUES
(21, 3, 'Product Long', 1212, 211, 212, 'img_67c93a648e3287.828203624336.jpg', 'Product Short Description', 'Product Long', 'Product Short Description', 'Product Meta Description', 'A', '2025-03-05 12:26:38'),
(22, 5, 'Milan333', 12, 12, 1231, 'img_67caba672d4577.7574911253064-2.jpg', 'Product Short Description', 'Product Short Description', 'Product Short Description', 'Product Short Description', 'N', '2025-03-06 09:41:12');

-- --------------------------------------------------------

--
-- Table structure for table `usersmaster`
--

CREATE TABLE `usersmaster` (
  `Users_ID` int(20) NOT NULL,
  `Users_Name` varchar(25) NOT NULL,
  `Users_Mobile` int(12) NOT NULL,
  `Users_Email` varchar(250) NOT NULL,
  `Users_Comment` mediumtext NOT NULL,
  `Users_Status` enum('A','N','D') NOT NULL DEFAULT 'N' COMMENT 'A = ''Active'', N = ''Inactive'' ,D = ''Deleted''\r\nA = ''Active'' ,N = ''Inactive'' ,D = ''Deleted''\r\nA = ''Active'' ,N = ''Inactive'' ,D = ''Deleted''\r\n',
  `Users_Add_Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminmaster`
--
ALTER TABLE `adminmaster`
  ADD PRIMARY KEY (`Admin_Id`);

--
-- Indexes for table `categoriesmaster`
--
ALTER TABLE `categoriesmaster`
  ADD PRIMARY KEY (`Categories_Id`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`contactus_id`);

--
-- Indexes for table `productmaster`
--
ALTER TABLE `productmaster`
  ADD PRIMARY KEY (`Product_Id`);

--
-- Indexes for table `usersmaster`
--
ALTER TABLE `usersmaster`
  ADD PRIMARY KEY (`Users_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminmaster`
--
ALTER TABLE `adminmaster`
  MODIFY `Admin_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categoriesmaster`
--
ALTER TABLE `categoriesmaster`
  MODIFY `Categories_Id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `contactus_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `productmaster`
--
ALTER TABLE `productmaster`
  MODIFY `Product_Id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Product_Id is productmaster primary key', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `usersmaster`
--
ALTER TABLE `usersmaster`
  MODIFY `Users_ID` int(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
