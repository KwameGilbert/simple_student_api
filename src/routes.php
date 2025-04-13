<?php
use Slim\App;
use Slim\Psr7\Response;

return function (App $app) {
    
    // Path to our mock "DB" file
    $dataFile = __DIR__ . '/../data/students.json';

    // Utility function to load student data
    $loadData = function() use ($dataFile) {
        if (!file_exists($dataFile)) {
            file_put_contents($dataFile, json_encode([]));
        }
        $json = file_get_contents($dataFile);
        return json_decode($json, true);
    };

    // Utility function to save student data
    $saveData = function(array $data) use ($dataFile) {
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
    };

    // Route to get all students
    $app->get('/students', function ($request, $response) use ($loadData) {
        $students = $loadData();
        $response->getBody()->write(json_encode($students));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Route to add a new student
    $app->post('/students', function ($request, $response) use ($loadData, $saveData) {
        $body = $request->getParsedBody();

        // Example validation (should be extended in production)
        if (!isset($body['name']) || !isset($body['email'])) {
            $error = ['error' => 'Missing required fields (name, email).'];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $students = $loadData();
        // Simple auto id generation; in production, use a robust method
        $id = count($students) ? end($students)['id'] + 1 : 1;
        $student = [
            'id' => $id,
            'name' => $body['name'],
            'email' => $body['email']
        ];
        $students[] = $student;
        $saveData($students);

        $response->getBody()->write(json_encode($student));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    });
};