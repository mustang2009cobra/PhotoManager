delimiter $$

CREATE TABLE `owners` (
  `OwnerID` char(12) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Username` char(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Password` char(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `First Name` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Last Name` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`OwnerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$