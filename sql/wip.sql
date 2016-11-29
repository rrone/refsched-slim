SELECT DISTINCT Referee FROM (
    SELECT cr AS Referee from `rs_games` WHERE `projectKey`='2016U16U19Chino'
    UNION
    SELECT ar1 AS Referee from `rs_games` WHERE `projectKey`='2016U16U19Chino'
    UNION
    SELECT ar2 AS Referee from `rs_games` WHERE `projectKey`='2016U16U19Chino'
   ) as t;
  
  
  SELECT DISTINCT SUBSTR(division,1,3) as uDiv FROM `rs_games` WHERE `projectKey`='2016U16U19Chino';

  SELECT DISTINCT date FROM `rs_games` WHERE `projectKey`='2016U16U19Chino';

SELECT * FROM (
	SELECT cr as ref, date, division, COUNT(cr) FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY cr, ar1, ar2, date, division
    UNION
	SELECT ar1 as ref, date, division, COUNT(ar1) FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY ar1, ar2, date, division
	UNION
	SELECT ar2 as ref, date, division, COUNT(ar2) FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY ar2, date, division
) as t WHERE ref = 'Al Prado';

SELECT * FROM (
	SELECT cr as ref, date, time, division, COUNT(cr) as cr, 0 as ar1, 0 as ar2 FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY cr, date, division
    UNION
	SELECT ar1 as ref, date, time, division, 0 as cr, COUNT(ar1) as ar1, 0 as ar2 FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY ar1,  date, division
    UNION	
	SELECT ar2 as ref, date, time, division, 0 as cr, 0 as ar1, COUNT(ar2) as ar2 FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY ar2, date, division
) as r
ORDER BY ref, date, time, division;

SELECT ar2 as ref, date, division, 0 as cr, 0 as ar1, COUNT(ar2) as ar2 FROM `rs_games` WHERE `projectKey`='2016U16U19Chino'
	GROUP BY ar2, date, division
