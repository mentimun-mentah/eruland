docker-compose exec -T mysql mysql -ueruland -psecret eruland -e "LOAD DATA LOCAL INFILE '/home/shipping_provinces.csv' into table shipping_provinces FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS;"

docker-compose exec -T mysql mysql -ueruland -psecret eruland -e "LOAD DATA LOCAL INFILE '/home/shipping_cities.csv' into table shipping_cities FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS;"

docker-compose exec -T mysql mysql -ueruland -psecret eruland -e "LOAD DATA LOCAL INFILE '/home/shipping_subdistricts.csv' into table shipping_subdistricts FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS;"
