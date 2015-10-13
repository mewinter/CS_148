SELECT fldBuilding, fldNumStudents FROM tblSections WHERE fldDays LIKE "%F%" GROUP BY fldBuilding ORDER BY fldNumStudents DESC
