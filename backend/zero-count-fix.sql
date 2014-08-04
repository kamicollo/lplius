INSERT INTO plusperson (plusperson_id, followers) 
	SELECT A.plusperson_id, A.followers
	FROM 
		(
		SELECT plusperson_id, followers, `time`
		FROM plusfollowers
		WHERE plusperson_id IN (SELECT plusperson_id FROM `plusperson` WHERE followers = 0) 
			AND followers > 0
		)as A,
		(
		SELECT plusperson_id, MAX(`time`) as 'time' 
		FROM plusfollowers
		WHERE plusperson_id IN (SELECT plusperson_id FROM `plusperson` WHERE followers = 0)
			AND followers > 0
		GROUP BY plusperson_id
		) AS B
	WHERE A.plusperson_id = B.plusperson_id AND A.time = B.time
ON DUPLICATE KEY UPDATE followers = VALUES(followers)
