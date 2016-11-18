SELECT DISTINCT Referee FROM (
    SELECT cr AS Referee from `rs_games` WHERE `projectKey`='2016U16U19Chino'
    UNION
    SELECT ar1 AS Referee from `rs_games` WHERE `projectKey`='2016U16U19Chino'
    UNION
    SELECT ar2 AS Referee from `rs_games` WHERE `projectKey`='2016U16U19Chino'
   ) as t