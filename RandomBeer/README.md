# A Beer App

This is a simple web app that displays details about a random beer.
The functionality should allow a user to click on the ‘Show Another Beer’ button to view information about the next random beer.

## The Rest Api

The core of the application is a REST based API that returns details about a beer when
queried. The beer details should include:
* The beer name
* A description of the beer
* The alcohol percentage (abv)
* The brewery location

## How it's done

The app was created using a simple MVC pattern altogether with Dependency Injection. It is created using PHP7 syntax and the only dependency is MySQL database. There's no web server dependency (feel free to use either Apache or Nginx). The system is Ubuntu 16 working on Virtual Machine (Vagrant). Code is well commented. Keep the current structure of the folders to have it working properly due to using namespaces. So it should be 'localhost' -> 'RandomBeer' folder -> bootstrap 'index.php' file and then the rest of the project in another 'RandomBeer' folder.
The UI is based on JS Bootstrap library, the initial beer data (when the page loads for the first time) is fetched through a regular Controller -> Model -> View request, the subsequent beer data is pulled from the API through an AJAX call.

## DB structure with initial data:

```
CREATE TABLE `beers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `abv` float(4,2) UNSIGNED NOT NULL,
  `brewery_location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `beers` (`id`, `name`, `description`, `abv`, `brewery_location`) VALUES
(1, 'Heineken', 'Heineken Lager Beer, or simply Heineken is a pale lager beer with 5% alcohol by volume produced by the Dutch brewing company Heineken International. Heineken is well known for its signature green bottle and red star.', 5.00, 'Netherlands, Amsterdam'),
(2, 'Guinness', 'Guinness is an Irish dry stout that originated in the brewery of Arthur Guinness at St. James\'s Gate brewery in the capital city of Dublin, Ireland.', 4.80, 'Ireland, Dublin'),
(3, 'Tyskie', 'Tyskie is a Polish brand of beer, its name comes from the brewery located in the Upper Silesia town of Tychy.', 5.60, 'Poland, Tychy'),
(4, 'Corona', 'Corona Extra is a pale lager produced by Cervecería Modelo in Mexico for domestic distribution and export to all other countries besides the United States, and by Constellation Brands in Mexico for export to the United States.', 4.50, 'Mexica, Mexico City');

ALTER TABLE `beers`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `beers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

```
