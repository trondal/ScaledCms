composer:
	#Update PHP modules
	composer update

db:
	#Empty database
	@@cd bin && php doctrine.php migrations:migrate 0
	# Rebuild database
	@@cd bin && php doctrine.php migrations:migrate