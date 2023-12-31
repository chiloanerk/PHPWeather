<?php

use app\classes\DataProcessor;
use app\classes\LocationProcessor;

session_start();

// Default values
$cityName = 'Johannesburg';
$latitude = null;
$longitude = null;

// Check if the city name is provided in the URL
if (!empty($_GET['city'])) {
    $cityName = $_GET['city'];
} elseif (isset($_GET['latitude']) && isset($_GET['longitude'])) {
    // Check if latitude and longitude are provided
    $latitude = $_GET['latitude'];
    $longitude = $_GET['longitude'];
}

// Create a LocationProcessor instance based on the available data
$locationData = new LocationProcessor($cityName, $latitude, $longitude);

// Retrieve the weather data
$weatherData = new DataProcessor($locationData->apiKey, $locationData->getLatitude(), $locationData->getLongitude());

$currentWeather = $weatherData->getLiveWeather();

$hourlyForecast = $weatherData->getHourlyWeather();

$weeklyForecast = $weatherData->getWeeklyForecast();

$messages = $weatherData->messages;

if (empty($messages)) {
    view('home/index', [
        'currentWeather' => $currentWeather,
        'hourlyForecast' => $hourlyForecast,
        'weeklyForecast' => $weeklyForecast,
        'messages' => $messages
    ]);
} else {
    $_SESSION['error_messages'] = $messages;
    var_dump($_SESSION['error_messages']);
    header('Location: /');
}
