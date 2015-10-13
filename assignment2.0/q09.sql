SELECT fldBuilding, fldNumStudents FROM tblSections WHERE fldDays LIKE "%W%" GROUP BY fldBuilding ORDER BY fldNumStudents DESC
