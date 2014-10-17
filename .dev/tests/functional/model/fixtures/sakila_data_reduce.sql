DELETE FROM film_actor WHERE film_id > 5;
DELETE FROM film_category WHERE film_id > 5;
DELETE FROM rental WHERE inventory_id IN (SELECT inventory_id FROM inventory WHERE film_id > 5);
DELETE FROM inventory WHERE film_id > 5;
DELETE FROM film WHERE film_id > 5;
DELETE FROM actor WHERE actor_id NOT IN(SELECT actor_id FROM film_actor);
DELETE FROM payment WHERE ISNULL(rental_id) OR rental_id NOT IN (SELECT rental_id FROM rental);
DELETE FROM customer WHERE customer_id NOT IN(SELECT customer_id FROM rental);
DELETE FROM address WHERE address_id NOT IN(SELECT address_id FROM customer UNION SELECT address_id FROM store UNION SELECT address_id FROM staff);
DELETE FROM city WHERE city_id NOT IN(SELECT city_id FROM address);
DELETE FROM country WHERE country_id NOT IN(SELECT country_id FROM city);
UPDATE staff SET picture = NULL;
DROP VIEW actor_info, customer_list, film_list, nicer_but_slower_film_list, sales_by_film_category, sales_by_store, staff_list;
DROP TABLE film_text;