/*  
    Situación:
    Usuarios se le asignan tokens (puntos) por un evento (event_name), pero a veces
    se le asignan más de una vez tokens por el mismo evento. Esto no debería pasar.
    Así que se debe obtener el primer registro ingresado por evento y sumar cuántos
    token tiene un usuario (current_user_id)

    (2) Con los registros encontrados en el subquery sumo sus cantidad de tokens de
    cada uno de ellos para obtener un total de "token del primer registro".
*/
SELECT SUM(token_quantity) score
FROM wp_events e1
LEFT JOIN wp_users u ON u.id = e1.user_id
WHERE e1.event_id IN (
    /*
        (1) Obtengo los registros de un usuario (current_user_id) que tengan el event_name
        repetido pero solo retorno el ID del que fue ingresado de primero (MIN).
    */
    SELECT MIN(e2.event_id) event_id
    FROM wp_events e2 
    WHERE e2.user_id = '{$current_user_id}' AND e2.event_name LIKE e1.event_name
    GROUP BY e2.event_name
)
GROUP BY e1.user_id
ORDER BY score DESC;



-- A continuación se muestra una variante de la misma query pero para obtener la información 
-- de TODOS los usuarios ordenada por el mas reciente (MAX, ORDER BY ASC)

SELECT user_id, SUM(token_quantity) score, MAX(e1.creation_time) antique
FROM wp_events e1
WHERE e1.event_id IN (
    SELECT MIN(e2.event_id) event_id
    FROM wp_events e2 
    WHERE e2.user_id = e1.user_id AND e2.event_name LIKE e1.event_name
    GROUP BY e2.event_name
)
GROUP BY e1.user_id
ORDER BY score DESC, antique ASC;


--
-- Estructura de la tabla `wp_events`
--

CREATE TABLE `wp_events` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(50) NOT NULL,
  `token_name` varchar(50) DEFAULT NULL,
  `token_quantity` int(11) DEFAULT NULL,
  `extra_info` varchar(50) DEFAULT NULL,
  `creation_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;