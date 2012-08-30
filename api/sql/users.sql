delimiter $$

CREATE TABLE `users` (
  `UserID` char(12) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Username` char(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Password` char(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `EmailAddress` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `FirstName` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `LastName` char(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$