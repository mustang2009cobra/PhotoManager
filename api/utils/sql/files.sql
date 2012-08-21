delimiter $$

CREATE TABLE `Files` (
  `FileID` char(36) COLLATE utf8_bin NOT NULL,
  `Name` varchar(255) COLLATE utf8_bin NOT NULL,
  `OwnerID` char(9) COLLATE utf8_bin NOT NULL,
  `Created` int(11) NOT NULL,
  `CreatedBy` char(9) COLLATE utf8_bin NOT NULL,
  `Modified` int(11) NOT NULL,
  `ModifiedBy` char(9) COLLATE utf8_bin NOT NULL,
  `Deleted` int(11) DEFAULT NULL,
  `DeletedBy` char(9) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
  `MimeType` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Size` int(11) DEFAULT NULL,
  `Type` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `Width` int(11) DEFAULT NULL,
  `Height` int(11) DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`FileID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin$$